package com.sicaps.backend.controller;

import com.sicaps.backend.dto.AppointmentRequestDTO;
import com.sicaps.backend.entity.Appointment;
import com.sicaps.backend.enums.AppointmentStatus;
import com.sicaps.backend.service.AppointmentService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/appointments")
@RequiredArgsConstructor
public class AppointmentController {

    private final AppointmentService appointmentService;

    @PostMapping
    public ResponseEntity<Map<String, Object>> createAppointment(@Valid @RequestBody AppointmentRequestDTO request) {
        Map<String, Object> response = new HashMap<>();
        try {
            Appointment appointment = appointmentService.createAppointment(request);
            response.put("success", true);
            response.put("message", "Cita programada exitosamente");
            // Se debe usar un DTO de respuesta en una app real para no enviar todo el objeto en lazy loading
            response.put("data", appointment.getId()); 
            return ResponseEntity.status(HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @GetMapping
    public ResponseEntity<Map<String, Object>> getAllAppointments() {
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("data", appointmentService.getAllAppointments());
        return ResponseEntity.ok(response);
    }
    
    @PatchMapping("/{id}/status")
    public ResponseEntity<Map<String, Object>> changeStatus(@PathVariable Long id, @RequestParam AppointmentStatus status) {
        Map<String, Object> response = new HashMap<>();
        try {
            Appointment appointment = appointmentService.changeStatus(id, status);
            response.put("success", true);
            response.put("message", "Estado de la cita actualizado");
            response.put("data", appointment.getId());
            return ResponseEntity.ok(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }
}
