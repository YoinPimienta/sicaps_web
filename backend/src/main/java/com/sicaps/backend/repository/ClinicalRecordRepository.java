package com.sicaps.backend.repository;

import com.sicaps.backend.entity.ClinicalRecord;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface ClinicalRecordRepository extends JpaRepository<ClinicalRecord, Long> {
    Optional<ClinicalRecord> findByPatientId(Long patientId);
    boolean existsByPatientId(Long patientId);
}
