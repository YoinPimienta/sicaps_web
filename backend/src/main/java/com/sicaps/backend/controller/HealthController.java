package com.sicaps.backend.controller;

import lombok.extern.slf4j.Slf4j;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

import javax.sql.DataSource;
import java.sql.Connection;
import java.sql.Statement;
import java.util.HashMap;
import java.util.Map;

@Slf4j
@RestController
public class HealthController {

    @Autowired(required = false)
    private DataSource dataSource;

    @GetMapping({"/", "/health", "/api/health"})
    public ResponseEntity<Map<String, Object>> checkHealth() {
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("message", "SICAPS Backend (Spring Boot) is running correctly");
        response.put("status", "UP");

        boolean dbOk = false;
        if (dataSource != null) {
            try (Connection conn = dataSource.getConnection();
                 Statement stmt = conn.createStatement()) {
                stmt.execute("SELECT 1");
                dbOk = true;
            } catch (Exception e) {
                log.warn("Health check DB probe: {}", e.getMessage());
            }
        }
        response.put("database", dbOk ? "UP" : "DOWN");

        return ResponseEntity.ok(response);
    }
}

