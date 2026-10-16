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

        // Initialize or synchronize Admin User
        Role adminRole = roleRepository.findByName(RoleName.ROLE_ADMIN).orElse(null);
        if (adminRole == null) {
            adminRole = roleRepository.save(new Role(null, RoleName.ROLE_ADMIN, "Administrador del sistema"));
        }

        User admin = userRepository.findByEmailIgnoreCase("admin@sicaps.com").orElse(null);
        if (admin == null) {
            admin = new User();
            admin.setName("Admin");
            admin.setLastName("System");
            admin.setDocumentType(DocumentType.CC);
            admin.setDocumentNumber("1234567890");
            admin.setEmail("admin@sicaps.com");
            admin.setPassword(passwordEncoder.encode("admin123"));
            admin.setRole(adminRole);
            admin.setStatus(UserStatus.ACTIVE);
            admin.setGender(Gender.OTRO);
            admin.setBirthDate(LocalDate.of(1990, 1, 1));
            userRepository.save(admin);
        } else {
            // Asegurar que la cuenta siempre esté activa y la contraseña sea admin123
            admin.setPassword(passwordEncoder.encode("admin123"));
            admin.setStatus(UserStatus.ACTIVE);
            admin.setRole(adminRole);
            userRepository.save(admin);
        }
        
        // Initialize or synchronize Default Professional for Testing
        Role profRole = roleRepository.findByName(RoleName.ROLE_PSYCHOLOGIST).orElse(null);
        if (profRole == null) {
            profRole = roleRepository.save(new Role(null, RoleName.ROLE_PSYCHOLOGIST, "Profesional Psicólogo"));
        }

        User profUser = userRepository.findByEmailIgnoreCase("doc@sicaps.com").orElse(null);
        if (profUser == null) {
            profUser = new User();
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
            professional.setSpecialty("Psicología Clínica y de la Salud");
            professional.setMedicalRegistrationNumber("TP-098765");
            professionalRepository.save(professional);
        } else {
            profUser.setPassword(passwordEncoder.encode("doc123"));
            profUser.setStatus(UserStatus.ACTIVE);
            profUser.setRole(profRole);
            userRepository.save(profUser);
        }
    }
}
