package com.sicaps.backend.repository;

import com.sicaps.backend.entity.Professional;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface ProfessionalRepository extends JpaRepository<Professional, Long> {
    Optional<Professional> findByUserId(Long userId);
    boolean existsByUserId(Long userId);
    boolean existsByMedicalRegistrationNumber(String medicalRegistrationNumber);
}
