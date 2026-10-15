package com.sicaps.backend.dto;

import com.sicaps.backend.enums.DocumentType;
import com.sicaps.backend.enums.Gender;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Data;

import java.time.LocalDate;

@Data
public class PatientRequestDTO {
    
    // User fields
    @NotBlank
    private String name;
    
    @NotBlank
    private String lastName;
    
    @NotNull
    private DocumentType documentType;
    
    @NotBlank
    private String documentNumber;
    
    private String phone;
    
    @NotBlank
    @Email
    private String email;
    
    private String address;
    
    private LocalDate birthDate;
    
    private Gender gender;
    
    private String password; // optional for creation
    
    // Patient specific fields
    private String emergencyContactName;
    private String emergencyContactPhone;
    private String medicalBackground;
    private String familyBackground;
    private String psychologicalBackground;
}
