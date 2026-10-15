package com.sicaps.backend.service;

import com.sicaps.backend.dto.AppointmentRequestDTO;
import com.sicaps.backend.entity.Patient;
import com.sicaps.backend.entity.Professional;
import com.sicaps.backend.repository.AppointmentRepository;
import com.sicaps.backend.repository.PatientRepository;
import com.sicaps.backend.repository.ProfessionalRepository;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;

import java.time.LocalDate;
import java.time.LocalTime;
import java.util.Optional;

import static org.junit.jupiter.api.Assertions.assertThrows;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.when;

@ExtendWith(MockitoExtension.class)
class AppointmentServiceTest {

    @Mock
    private AppointmentRepository appointmentRepository;

    @Mock
    private PatientRepository patientRepository;

    @Mock
    private ProfessionalRepository professionalRepository;

    @InjectMocks
    private AppointmentService appointmentService;

    private AppointmentRequestDTO requestDTO;

    @BeforeEach
    void setUp() {
        requestDTO = new AppointmentRequestDTO();
        requestDTO.setPatientId(1L);
        requestDTO.setProfessionalId(2L);
        requestDTO.setDate(LocalDate.now().plusDays(1));
        requestDTO.setTime(LocalTime.of(10, 0));
    }

    @Test
    void createAppointment_ConflictProfessional_ThrowsException() {
        // Arrange
        when(patientRepository.findById(1L)).thenReturn(Optional.of(new Patient()));
        when(professionalRepository.findById(2L)).thenReturn(Optional.of(new Professional()));
        when(appointmentRepository.existsConflictForProfessional(any(), any(), any())).thenReturn(true);

        // Act & Assert
        assertThrows(IllegalArgumentException.class, () -> {
            appointmentService.createAppointment(requestDTO);
        }, "El profesional ya tiene una cita asignada en ese horario");
    }

    @Test
    void createAppointment_ConflictPatient_ThrowsException() {
        // Arrange
        when(patientRepository.findById(1L)).thenReturn(Optional.of(new Patient()));
        when(professionalRepository.findById(2L)).thenReturn(Optional.of(new Professional()));
        when(appointmentRepository.existsConflictForProfessional(any(), any(), any())).thenReturn(false);
        when(appointmentRepository.existsConflictForPatient(any(), any(), any())).thenReturn(true);

        // Act & Assert
        assertThrows(IllegalArgumentException.class, () -> {
            appointmentService.createAppointment(requestDTO);
        }, "El paciente ya tiene una cita asignada en ese horario");
    }
}
