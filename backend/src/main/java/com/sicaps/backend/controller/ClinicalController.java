package com.sicaps.backend.controller;

import com.sicaps.backend.entity.ClinicalRecord;
import com.sicaps.backend.entity.Evolution;
import com.sicaps.backend.service.ClinicalService;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/clinical")
@RequiredArgsConstructor
public class ClinicalController {

    private final ClinicalService clinicalService;

    @PostMapping("/records/{patientId}/{professionalId}")
    public ResponseEntity<Map<String, Object>> createRecord(
            @PathVariable Long patientId, 
            @PathVariable Long professionalId, 
            @RequestBody ClinicalRecord record) {
        
        Map<String, Object> response = new HashMap<>();
        try {
            ClinicalRecord saved = clinicalService.createClinicalRecord(patientId, professionalId, record);
            response.put("success", true);
            response.put("data", saved.getId());
            return ResponseEntity.status(HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @PostMapping("/evolutions/{patientId}/{professionalId}")
    public ResponseEntity<Map<String, Object>> createEvolution(
            @PathVariable Long patientId, 
            @PathVariable Long professionalId, 
            @RequestBody Evolution evolution) {
        
        Map<String, Object> response = new HashMap<>();
        try {
            Evolution saved = clinicalService.addEvolution(patientId, professionalId, evolution);
            response.put("success", true);
            response.put("data", saved.getId());
            return ResponseEntity.status(HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @PostMapping("/medications/{patientId}/{professionalId}")
    public ResponseEntity<Map<String, Object>> addMedication(
            @PathVariable Long patientId, 
            @PathVariable Long professionalId, 
            @RequestBody com.sicaps.backend.entity.Medication medication) {
        
        Map<String, Object> response = new HashMap<>();
        try {
            com.sicaps.backend.entity.Medication saved = clinicalService.addMedication(patientId, professionalId, medication);
            response.put("success", true);
            response.put("data", saved.getId());
            return ResponseEntity.status(HttpStatus.CREATED).body(response);
        } catch (IllegalArgumentException e) {
            response.put("success", false);
            response.put("message", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @GetMapping("/records/{patientId}")
    public ResponseEntity<Map<String, Object>> getRecordsByPatient(@PathVariable Long patientId) {
        Map<String, Object> response = new HashMap<>();
        try {
            response.put("success", true);
            response.put("data", clinicalService.getEvolutionsByPatient(patientId));
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            response.put("success", false);
            response.put("data", new java.util.ArrayList<>());
            return ResponseEntity.ok(response);
        }
    }
}
