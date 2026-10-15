package com.sicaps.backend.service;

import com.sicaps.backend.entity.ClinicalRecord;
import com.sicaps.backend.entity.Evolution;
import com.sicaps.backend.entity.Medication;
import com.sicaps.backend.entity.Patient;
import com.sicaps.backend.entity.Professional;
import com.sicaps.backend.repository.ClinicalRecordRepository;
import com.sicaps.backend.repository.EvolutionRepository;
import com.sicaps.backend.repository.MedicationRepository;
import com.sicaps.backend.repository.PatientRepository;
import com.sicaps.backend.repository.ProfessionalRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class ClinicalService {

    private final ClinicalRecordRepository clinicalRecordRepository;
    private final EvolutionRepository evolutionRepository;
    private final MedicationRepository medicationRepository;
    private final PatientRepository patientRepository;
    private final ProfessionalRepository professionalRepository;

    @Transactional
    public ClinicalRecord createClinicalRecord(Long patientId, Long professionalId, ClinicalRecord data) {
        if (clinicalRecordRepository.existsByPatientId(patientId)) {
            throw new IllegalArgumentException("El paciente ya tiene una historia clínica abierta");
        }

        Patient patient = patientRepository.findById(patientId)
                .orElseThrow(() -> new IllegalArgumentException("Paciente no encontrado"));
                
        Professional professional = professionalRepository.findById(professionalId)
                .orElseThrow(() -> new IllegalArgumentException("Profesional no encontrado"));

        data.setPatient(patient);
        data.setResponsibleProfessional(professional);
        
        return clinicalRecordRepository.save(data);
    }
    
    public ClinicalRecord getClinicalRecordByPatient(Long patientId) {
        return clinicalRecordRepository.findByPatientId(patientId)
                .orElseThrow(() -> new IllegalArgumentException("Historia clínica no encontrada para este paciente"));
    }

    @Transactional
    public Evolution addEvolution(Long patientId, Long professionalId, Evolution data) {
        
        Patient patient = patientRepository.findById(patientId)
                .orElseThrow(() -> new IllegalArgumentException("Paciente no encontrado"));
                
        Professional professional = professionalRepository.findById(professionalId)
                .orElseThrow(() -> new IllegalArgumentException("Profesional no encontrado"));

        // Auto-create clinical record if it doesn't exist
        if (!clinicalRecordRepository.existsByPatientId(patientId)) {
             ClinicalRecord record = new ClinicalRecord();
             record.setPatient(patient);
             record.setResponsibleProfessional(professional);
             record.setConsultationReason(data.getReason() != null ? data.getReason() : "Evolución inicial");
             clinicalRecordRepository.save(record);
        }

        data.setPatient(patient);
        data.setProfessional(professional);
        
        return evolutionRepository.save(data);
    }

    public List<Evolution> getEvolutionsByPatient(Long patientId) {
        return evolutionRepository.findByPatientIdOrderByCreatedAtDesc(patientId);
    }

    @Transactional
    public Medication addMedication(Long patientId, Long professionalId, Medication data) {
        Patient patient = patientRepository.findById(patientId)
                .orElseThrow(() -> new IllegalArgumentException("Paciente no encontrado"));
                
        Professional professional = professionalRepository.findById(professionalId)
                .orElseThrow(() -> new IllegalArgumentException("Profesional no encontrado"));

        data.setPatient(patient);
        data.setProfessional(professional);
        
        return medicationRepository.save(data);
    }

    public List<Medication> getMedicationsByPatient(Long patientId) {
        return medicationRepository.findByPatientIdOrderByCreatedAtDesc(patientId);
    }
}
