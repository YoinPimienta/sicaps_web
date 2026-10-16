package com.sicaps.backend.service;

import com.sicaps.backend.dto.PatientRequestDTO;
import com.sicaps.backend.entity.Patient;
import com.sicaps.backend.entity.Role;
import com.sicaps.backend.entity.User;
import com.sicaps.backend.enums.RoleName;
import com.sicaps.backend.enums.UserStatus;
import com.sicaps.backend.repository.PatientRepository;
import com.sicaps.backend.repository.RoleRepository;
import com.sicaps.backend.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class PatientService {

    private final PatientRepository patientRepository;
    private final UserRepository userRepository;
    private final RoleRepository roleRepository;
    private final PasswordEncoder passwordEncoder;

    @Transactional
    public Patient createPatient(PatientRequestDTO dto) {
        String email = dto.getEmail() != null ? dto.getEmail().trim() : "";
        if (userRepository.existsByEmailIgnoreCase(email)) {
            throw new IllegalArgumentException("El email ya está registrado");
        }
        if (userRepository.existsByDocumentNumber(dto.getDocumentNumber())) {
            throw new IllegalArgumentException("El documento ya está registrado");
        }

        Role patientRole = roleRepository.findByName(RoleName.ROLE_PATIENT)
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
        user.setRole(patientRole);
        user.setStatus(UserStatus.ACTIVE);
        
        String pass = dto.getPassword() != null ? dto.getPassword() : dto.getDocumentNumber(); // Default password as document
        user.setPassword(passwordEncoder.encode(pass));
        
        user = userRepository.save(user);

        Patient patient = new Patient();
        patient.setUser(user);
        patient.setEmergencyContactName(dto.getEmergencyContactName());
        patient.setEmergencyContactPhone(dto.getEmergencyContactPhone());
        patient.setMedicalBackground(dto.getMedicalBackground());
        patient.setFamilyBackground(dto.getFamilyBackground());
        patient.setPsychologicalBackground(dto.getPsychologicalBackground());

        return patientRepository.save(patient);
    }

    public List<Patient> getAllPatients() {
        return patientRepository.findAll();
    }

    public Patient getPatientById(Long id) {
        return patientRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("Paciente no encontrado"));
    }

    @Transactional
    public Patient updatePatient(Long id, PatientRequestDTO dto) {
        Patient patient = getPatientById(id);
        User user = patient.getUser();

        // Update User info
        user.setEmail(dto.getEmail());
        if (dto.getPhone() != null) user.setPhone(dto.getPhone());
        if (dto.getAddress() != null) user.setAddress(dto.getAddress());
        userRepository.save(user);

        // Update Patient info
        if (dto.getEmergencyContactName() != null) patient.setEmergencyContactName(dto.getEmergencyContactName());
        if (dto.getEmergencyContactPhone() != null) patient.setEmergencyContactPhone(dto.getEmergencyContactPhone());
        
        return patientRepository.save(patient);
    }

    @Transactional
    public void deletePatient(Long id) {
        Patient patient = getPatientById(id);
        patientRepository.delete(patient);
        userRepository.delete(patient.getUser());
    }
}
