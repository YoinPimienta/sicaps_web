package com.sicaps.backend.service;

import com.sicaps.backend.dto.ProfessionalRequestDTO;
import com.sicaps.backend.entity.Professional;
import com.sicaps.backend.entity.Role;
import com.sicaps.backend.entity.User;
import com.sicaps.backend.enums.RoleName;
import com.sicaps.backend.enums.UserStatus;
import com.sicaps.backend.repository.ProfessionalRepository;
import com.sicaps.backend.repository.RoleRepository;
import com.sicaps.backend.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class ProfessionalService {

    private final ProfessionalRepository professionalRepository;
    private final UserRepository userRepository;
    private final RoleRepository roleRepository;
    private final PasswordEncoder passwordEncoder;

    @Transactional
    public Professional createProfessional(ProfessionalRequestDTO dto) {
        if (userRepository.existsByEmail(dto.getEmail())) {
            throw new IllegalArgumentException("El email ya está registrado");
        }
        if (userRepository.existsByDocumentNumber(dto.getDocumentNumber())) {
            throw new IllegalArgumentException("El documento ya está registrado");
        }
        if (dto.getMedicalRegistrationNumber() != null && professionalRepository.existsByMedicalRegistrationNumber(dto.getMedicalRegistrationNumber())) {
            throw new IllegalArgumentException("El registro profesional ya está asociado a otro psicólogo");
        }

        Role profRole = roleRepository.findByName(RoleName.ROLE_PSYCHOLOGIST)
                .orElseThrow(() -> new RuntimeException("Role not found"));

        User user = new User();
        user.setName(dto.getName());
        user.setLastName(dto.getLastName());
        user.setDocumentType(dto.getDocumentType());
        user.setDocumentNumber(dto.getDocumentNumber());
        user.setPhone(dto.getPhone());
        user.setEmail(dto.getEmail());
        user.setAddress(dto.getAddress());
        user.setBirthDate(dto.getBirthDate());
        user.setGender(dto.getGender());
        user.setRole(profRole);
        user.setStatus(UserStatus.ACTIVE);
        
        String pass = dto.getPassword() != null ? dto.getPassword() : dto.getDocumentNumber(); 
        user.setPassword(passwordEncoder.encode(pass));
        
        user = userRepository.save(user);

        Professional prof = new Professional();
        prof.setUser(user);
        prof.setSpecialty(dto.getSpecialty());
        prof.setMedicalRegistrationNumber(dto.getMedicalRegistrationNumber());

        return professionalRepository.save(prof);
    }

    public List<Professional> getAllProfessionals() {
        return professionalRepository.findAll();
    }

    public Professional getProfessionalById(Long id) {
        return professionalRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("Profesional no encontrado con ID: " + id));
    }
}
