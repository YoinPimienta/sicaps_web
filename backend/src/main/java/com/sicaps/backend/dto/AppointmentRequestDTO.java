package com.sicaps.backend.dto;

import jakarta.validation.constraints.FutureOrPresent;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Data;

import java.time.LocalDate;
import java.time.LocalTime;

@Data
public class AppointmentRequestDTO {
    
    @NotNull(message = "El ID del paciente es requerido")
    private Long patientId;
    
    @NotNull(message = "El ID del profesional es requerido")
    private Long professionalId;
    
    @NotNull(message = "La fecha es requerida")
    @FutureOrPresent(message = "La fecha no puede estar en el pasado")
    private LocalDate date;
    
    @NotNull(message = "La hora es requerida")
    private LocalTime time;
    
    private Integer durationMinutes = 45;
    
    @NotBlank(message = "El motivo de la cita es requerido")
    private String reason;
    
    private String observations;
}
