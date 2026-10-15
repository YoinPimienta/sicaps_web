# Requerimientos - SICAPS Web

## Requisitos Funcionales (RF)

| ID | Nombre | Descripción | Prioridad | Actor |
|---|---|---|---|---|
| RF01 | Autenticación Segura | El sistema debe permitir el inicio de sesión validando credenciales cifradas y retornando un token JWT. | Alta | Todos |
| RF02 | Gestión de Usuarios | El sistema debe permitir crear, editar y consultar usuarios con validación de correo y documento único. | Alta | Administrador |
| RF03 | Registro de Pacientes | Se debe registrar la información personal, antecedentes médicos y psicológicos de los pacientes. | Alta | Psicólogo, Admin |
| RF04 | Agendamiento de Citas | El sistema debe asignar citas evitando cruces de horario para el mismo paciente o profesional. | Alta | Psicólogo, Admin |
| RF05 | Historia Clínica | El profesional podrá abrir una historia clínica única por paciente y registrar el diagnóstico y plan de intervención. | Alta | Psicólogo |
| RF06 | Evoluciones | El profesional registrará las evoluciones cronológicas por cada atención realizada al paciente. | Alta | Psicólogo |
| RF07 | Registro de Medicamentos | El sistema permitirá añadir un registro de medicamentos recetados al paciente. | Media | Psicólogo |
| RF08 | Dashboard y Reportes | Visualización de estadísticas generales (citas de hoy, pacientes totales). | Media | Todos |

## Requisitos No Funcionales (RNF)

| ID | Nombre | Descripción | Prioridad |
|---|---|---|---|
| RNF01 | Arquitectura REST | El backend debe implementarse como API REST independiente en Spring Boot (Java). | Alta |
| RNF02 | Frontend MVC | El cliente será desarrollado en PHP 8 con Laravel y TailwindCSS. | Alta |
| RNF03 | Persistencia | Uso de PostgreSQL garantizando integridad referencial (Claves Foráneas). | Alta |
| RNF04 | Despliegue | Configuración Dockerizada lista para despliegue en contenedores (Render/Supabase). | Alta |
| RNF05 | Seguridad JWT | Protección de endpoints mediante Spring Security y stateless JSON Web Tokens. | Alta |

## Reglas de Negocio (RN)

1. Un usuario no puede tener dos cuentas activas con el mismo correo electrónico.
2. Un paciente no puede tener dos registros con el mismo número de documento.
3. Un profesional y un paciente no pueden tener dos citas agendadas simultáneamente en la misma fecha y hora (Evita Overbooking).
4. Solamente el rol de "Psicólogo" está autorizado para crear o modificar registros clínicos (Historias Clínicas y Evoluciones).
