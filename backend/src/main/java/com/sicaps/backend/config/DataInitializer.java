package com.sicaps.backend.config;

import com.sicaps.backend.entity.Role;
import com.sicaps.backend.entity.User;
import com.sicaps.backend.enums.DocumentType;
import com.sicaps.backend.enums.Gender;
import com.sicaps.backend.enums.RoleName;
import com.sicaps.backend.enums.UserStatus;
import com.sicaps.backend.repository.RoleRepository;
import com.sicaps.backend.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.boot.CommandLineRunner;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

import java.time.LocalDate;

@Component
@RequiredArgsConstructor
public class DataInitializer implements CommandLineRunner {

    private final RoleRepository roleRepository;
    private final UserRepository userRepository;
    private final com.sicaps.backend.repository.ProfessionalRepository professionalRepository;
    private final PasswordEncoder passwordEncoder;

    @Override
    public void run(String... args) throws Exception {
        // Initialize Roles
        if (roleRepository.count() == 0) {
            roleRepository.save(new Role(null, RoleName.ROLE_ADMIN, "Administrador del sistema"));
            roleRepository.save(new Role(null, RoleName.ROLE_PSYCHOLOGIST, "Profesional Psicólogo"));
            roleRepository.save(new Role(null, RoleName.ROLE_PATIENT, "Paciente del consultorio"));
        }

        // Initialize Admin User
        if (!userRepository.existsByEmail("admin@sicaps.com")) {
            Role adminRole = roleRepository.findByName(RoleName.ROLE_ADMIN).orElseThrow();
            
            User admin = new User();
            admin.setName("Admin");
            admin.setLastName("System");
            admin.setDocumentType(DocumentType.CC);
            admin.setDocumentNumber("1234567890");
            admin.setEmail("admin@sicaps.com");
            admin.setPassword(passwordEncoder.encode("admin123")); // Contraseña de prueba
            admin.setRole(adminRole);
            admin.setStatus(UserStatus.ACTIVE);
            admin.setGender(Gender.OTRO);
            admin.setBirthDate(LocalDate.of(1990, 1, 1));
            
            userRepository.save(admin);
        }
        
        // Initialize Default Professional for Testing
        if (!userRepository.existsByEmail("doc@sicaps.com")) {
            Role profRole = roleRepository.findByName(RoleName.ROLE_PSYCHOLOGIST).orElseThrow();
            
            User profUser = new User();
            profUser.setName("Dra. María");
            profUser.setLastName("Gómez");
            profUser.setDocumentType(DocumentType.CC);
            profUser.setDocumentNumber("0987654321");
            profUser.setEmail("doc@sicaps.com");
            profUser.setPassword(passwordEncoder.encode("doc123"));
            profUser.setRole(profRole);
            profUser.setStatus(UserStatus.ACTIVE);
            profUser.setGender(Gender.FEMENINO);
            profUser.setBirthDate(LocalDate.of(1985, 5, 10));
            
            userRepository.save(profUser);
            
            com.sicaps.backend.entity.Professional professional = new com.sicaps.backend.entity.Professional();
            professional.setUser(profUser);
            professional.setSpecialty("Psicología Clínica");
            professional.setMedicalRegistrationNumber("MP-98765");
            
            professionalRepository.save(professional);
        }
    }
}
