package com.sicaps.backend.controller;

import com.sicaps.backend.dto.ProfessionalRequestDTO;
import com.sicaps.backend.entity.Professional;
import com.sicaps.backend.service.ProfessionalService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/professionals")
@RequiredArgsConstructor
public class ProfessionalController {

    private final ProfessionalService professionalService;

    @PostMapping
    public ResponseEntity<Map<String, Object>> createProfessional(@Valid @RequestBody ProfessionalRequestDTO request) {
        Map<String, Object> response = new HashMap<>();
        try {
            Professional prof = professionalService.createProfessional(request);
            response.put("success", true);
            response.put("message", "Profesional creado correctamente");
            response.put("data", prof);
            return ResponseEntity.status(HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @GetMapping
    public ResponseEntity<Map<String, Object>> getAllProfessionals() {
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("data", professionalService.getAllProfessionals());
        return ResponseEntity.ok(response);
    }
}
