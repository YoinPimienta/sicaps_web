package com.sicaps.backend.controller;

import com.sicaps.backend.dto.AuthResponse;
import com.sicaps.backend.dto.LoginRequest;
import com.sicaps.backend.service.AuthService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/auth")
@RequiredArgsConstructor
public class AuthController {

    private final AuthService authService;
    private final com.sicaps.backend.service.PatientService patientService;

    @PostMapping("/login")
    public ResponseEntity<AuthResponse> login(@Valid @RequestBody LoginRequest request) {
        return ResponseEntity.ok(authService.login(request));
    }

    @PostMapping("/register")
    public ResponseEntity<java.util.Map<String, Object>> register(@Valid @RequestBody com.sicaps.backend.dto.PatientRequestDTO request) {
        java.util.Map<String, Object> response = new java.util.HashMap<>();
        try {
            com.sicaps.backend.entity.Patient patient = patientService.createPatient(request);
            response.put("success", true);
            response.put("message", "Paciente registrado exitosamente");
            return ResponseEntity.status(org.springframework.http.HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }
}
