# Casos de Uso - SICAPS Web

## CU-01: Iniciar Sesión

- **Actor:** Usuario (Administrador, Psicólogo, Paciente).
- **Precondiciones:** El usuario debe estar registrado en el sistema y tener estado "ACTIVO".
- **Flujo Principal:**
  1. El usuario ingresa su correo electrónico y contraseña en el formulario (Laravel).
  2. Laravel envía la petición POST `/api/auth/login` al backend (Spring Boot).
  3. Spring Boot valida el hash de la contraseña contra PostgreSQL.
  4. Spring Boot retorna un token JWT válido.
  5. Laravel almacena el JWT en sesión y redirige al Dashboard.
- **Flujos Alternativos:**
  - *Credenciales inválidas:* Spring Boot responde HTTP 403. Laravel muestra "Credenciales incorrectas".
- **Postcondiciones:** El usuario tiene acceso a los módulos permitidos según su rol.

## CU-02: Agendar Cita

- **Actor:** Psicólogo / Administrador.
- **Precondiciones:** El paciente y el profesional existen en el sistema.
- **Flujo Principal:**
  1. El actor selecciona un paciente, un profesional, una fecha y una hora.
  2. Laravel envía la petición a `/api/appointments`.
  3. Spring Boot valida (vía JPQL) que el paciente y el profesional no tengan citas en ese mismo bloque horario.
  4. La cita se guarda con estado "PROGRAMADA".
- **Flujos Alternativos:**
  - *Conflicto de horario:* El backend lanza `IllegalArgumentException` detectado en BD. Se retorna un error al frontend "El profesional ya tiene cita en ese horario".
- **Postcondiciones:** La cita queda agendada y visible en el calendario del profesional y del paciente.
