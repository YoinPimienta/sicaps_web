package com.sicaps.backend.service;

import com.sicaps.backend.enums.RoleName;
import com.sicaps.backend.repository.AppointmentRepository;
import com.sicaps.backend.repository.PatientRepository;
import com.sicaps.backend.repository.ProfessionalRepository;
import com.sicaps.backend.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.util.HashMap;
import java.util.Map;

@Service
@RequiredArgsConstructor
public class ReportService {

    private final PatientRepository patientRepository;
    private final ProfessionalRepository professionalRepository;
    private final AppointmentRepository appointmentRepository;
    private final UserRepository userRepository;

    public Map<String, Object> getAdminDashboardStats() {
        Map<String, Object> stats = new HashMap<>();
        stats.put("totalPatients", patientRepository.count());
        stats.put("totalProfessionals", professionalRepository.count());
        stats.put("totalUsers", userRepository.count());
        stats.put("appointmentsToday", appointmentRepository.findByDate(LocalDate.now()).size());
        return stats;
    }
}
