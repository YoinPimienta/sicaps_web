# Pruebas (Testing) - SICAPS Web

## Estrategia de Pruebas

El sistema emplea pruebas unitarias en la capa de servicios del Backend (Java) para validar las reglas de negocio más críticas del sistema (Test-Driven Validation).

## Casos de Prueba Ejecutados

### Caso de Prueba: TP-01 (Unitario - Backend)
- **Funcionalidad:** Prevención de Overbooking (Agendamiento simultáneo para Profesional).
- **Entrada:** `AppointmentRequestDTO` solicitando crear cita para el Profesional A en la Fecha X, Hora Y. El repositorio simula que el Profesional A ya tiene una cita en ese instante.
- **Resultado Esperado:** Lanzar `IllegalArgumentException` con mensaje "El profesional ya tiene una cita asignada en ese horario".
- **Resultado Obtenido:** Excepción interceptada correctamente en `AppointmentServiceTest.java`.
- **Estado:** ✅ APROBADO

### Caso de Prueba: TP-02 (Unitario - Backend)
- **Funcionalidad:** Prevención de Overbooking (Agendamiento simultáneo para Paciente).
- **Entrada:** `AppointmentRequestDTO` donde el Paciente A intenta agendar con otro profesional a la misma hora exacta que ya tenía reservada.
- **Resultado Esperado:** Lanzar `IllegalArgumentException` indicando conflicto de paciente.
- **Resultado Obtenido:** Excepción interceptada correctamente en `AppointmentServiceTest.java`.
- **Estado:** ✅ APROBADO

*(Las pruebas se pueden ejecutar compilando el backend con `mvn test`)*
