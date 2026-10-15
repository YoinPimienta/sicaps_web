# Base de Datos - SICAPS Web

## Modelo Relacional

La base de datos relacional utiliza **PostgreSQL** y se estructura de la siguiente manera, garantizando la integridad referencial y las reglas del negocio:

### Entidades Principales

1. **`roles`**
   - `id` (PK)
   - `name` (UNIQUE)
   - `description`

2. **`users`**
   - `id` (PK)
   - `name`, `last_name`, `document_type`
   - `document_number` (UNIQUE)
   - `email` (UNIQUE)
   - `role_id` (FK -> `roles.id`)
   - `password`, `status`

3. **`patients`**
   - `id` (PK)
   - `user_id` (FK -> `users.id`, UNIQUE)
   - `emergency_contact_name`, `emergency_contact_phone`
   - `medical_background`, `psychological_background`

4. **`professionals`**
   - `id` (PK)
   - `user_id` (FK -> `users.id`, UNIQUE)
   - `specialty`
   - `medical_registration_number` (UNIQUE)

5. **`appointments`** (Citas)
   - `id` (PK)
   - `patient_id` (FK -> `patients.id`)
   - `professional_id` (FK -> `professionals.id`)
   - `date`, `time`, `duration_minutes`
   - `reason`, `status`, `observations`

### Diagrama Entidad-Relación (ER) Conceptual

```mermaid
erDiagram
    ROLES ||--o{ USERS : "tiene"
    USERS ||--o| PATIENTS : "es un"
    USERS ||--o| PROFESSIONALS : "es un"
    
    PATIENTS ||--o{ APPOINTMENTS : "solicita"
    PROFESSIONALS ||--o{ APPOINTMENTS : "atiende"
    
    USERS {
        Long id PK
        String email UK
        String document_number UK
        String password
        String status
    }
    PATIENTS {
        Long id PK
        Long user_id FK
        String medical_background
    }
    PROFESSIONALS {
        Long id PK
        Long user_id FK
        String specialty
    }
    APPOINTMENTS {
        Long id PK
        Long patient_id FK
        Long professional_id FK
        Date date
        Time time
        String status
    }
```
