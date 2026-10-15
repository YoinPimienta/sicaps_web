package com.sicaps.backend.repository;

import com.sicaps.backend.entity.Evolution;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface EvolutionRepository extends JpaRepository<Evolution, Long> {
    List<Evolution> findByPatientIdOrderByCreatedAtDesc(Long patientId);
    List<Evolution> findByProfessionalId(Long professionalId);
}
