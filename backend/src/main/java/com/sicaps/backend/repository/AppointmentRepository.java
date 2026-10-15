package com.sicaps.backend.repository;

import com.sicaps.backend.entity.Appointment;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.time.LocalDate;
import java.time.LocalTime;
import java.util.List;

@Repository
public interface AppointmentRepository extends JpaRepository<Appointment, Long> {
    List<Appointment> findByPatientId(Long patientId);
    List<Appointment> findByProfessionalId(Long professionalId);
    List<Appointment> findByDate(LocalDate date);

    @Query("SELECT COUNT(a) > 0 FROM Appointment a WHERE a.professional.id = :professionalId " +
           "AND a.date = :date AND a.time = :time AND a.status != 'CANCELADA'")
    boolean existsConflictForProfessional(@Param("professionalId") Long professionalId, 
                                          @Param("date") LocalDate date, 
                                          @Param("time") LocalTime time);

    @Query("SELECT COUNT(a) > 0 FROM Appointment a WHERE a.patient.id = :patientId " +
           "AND a.date = :date AND a.time = :time AND a.status != 'CANCELADA'")
    boolean existsConflictForPatient(@Param("patientId") Long patientId, 
                                     @Param("date") LocalDate date, 
                                     @Param("time") LocalTime time);
}
