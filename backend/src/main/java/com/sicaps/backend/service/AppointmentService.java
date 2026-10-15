package com.sicaps.backend.service;

import com.sicaps.backend.dto.AppointmentRequestDTO;
import com.sicaps.backend.entity.Appointment;
import com.sicaps.backend.entity.Patient;
import com.sicaps.backend.entity.Professional;
import com.sicaps.backend.enums.AppointmentStatus;
import com.sicaps.backend.repository.AppointmentRepository;
import com.sicaps.backend.repository.PatientRepository;
import com.sicaps.backend.repository.ProfessionalRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class AppointmentService {

    private final AppointmentRepository appointmentRepository;
    private final PatientRepository patientRepository;
    private final ProfessionalRepository professionalRepository;

    @Transactional
    public Appointment createAppointment(AppointmentRequestDTO dto) {
        
        Patient patient = patientRepository.findById(dto.getPatientId())
                .orElseThrow(() -> new IllegalArgumentException("Paciente no encontrado"));
                
        Professional professional = professionalRepository.findById(dto.getProfessionalId())
                .orElseThrow(() -> new IllegalArgumentException("Profesional no encontrado"));
                
        // Validar conflictos de horario para el profesional
        if (appointmentRepository.existsConflictForProfessional(dto.getProfessionalId(), dto.getDate(), dto.getTime())) {
            throw new IllegalArgumentException("El profesional ya tiene una cita asignada en ese horario");
        }
        
        // Validar conflictos de horario para el paciente
        if (appointmentRepository.existsConflictForPatient(dto.getPatientId(), dto.getDate(), dto.getTime())) {
            throw new IllegalArgumentException("El paciente ya tiene una cita asignada en ese horario");
        }

        Appointment appointment = new Appointment();
        appointment.setPatient(patient);
        appointment.setProfessional(professional);
        appointment.setDate(dto.getDate());
        appointment.setTime(dto.getTime());
        appointment.setDurationMinutes(dto.getDurationMinutes());
        appointment.setReason(dto.getReason());
        appointment.setObservations(dto.getObservations());
        appointment.setStatus(AppointmentStatus.PROGRAMADA);

        return appointmentRepository.save(appointment);
    }
    
    @Transactional
    public Appointment changeStatus(Long id, AppointmentStatus newStatus) {
        Appointment appointment = appointmentRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("Cita no encontrada"));
        appointment.setStatus(newStatus);
        return appointmentRepository.save(appointment);
    }

    public List<Appointment> getAllAppointments() {
        return appointmentRepository.findAll();
    }
    
    public List<Appointment> getAppointmentsByPatient(Long patientId) {
        return appointmentRepository.findByPatientId(patientId);
    }
    
    public List<Appointment> getAppointmentsByProfessional(Long professionalId) {
        return appointmentRepository.findByProfessionalId(professionalId);
    }
}
