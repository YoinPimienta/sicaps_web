# SICAPS Web - Sistema de Atención Psicológica

## 1. ¿Qué es SICAPS Web?
SICAPS Web es un sistema integral de información diseñado para la gestión digital de consultorios y servicios de atención psicológica. Permite administrar de forma centralizada y segura usuarios, profesionales (psicólogos), pacientes, citas, historias clínicas, evoluciones y medicamentos.

## 2. Objetivo
El objetivo principal es proveer una plataforma tecnológica robusta, estructurada bajo estándares profesionales de desarrollo de software, que facilite la administración clínica y administrativa de un centro psicológico. Este proyecto está diseñado para ser presentado como proyecto académico ante el SENA, evidenciando la aplicación de una arquitectura multicapa, desarrollo backend y frontend, bases de datos relacionales y buenas prácticas de seguridad.

## 3. Tecnologías
- **Frontend (Interfaz de Usuario & Gestión de Sesiones):** PHP 8.2+, Laravel, Blade, Tailwind CSS.
- **Backend (API REST & Lógica de Negocio):** Java 17+, Spring Boot, Spring Security, Hibernate/JPA, Maven.
- **Base de Datos:** PostgreSQL (Proveedor oficial: Supabase).
- **Despliegue (Preparado):** Render (Backend y Frontend en servicios separados), Supabase (PostgreSQL).

## 4. Arquitectura
El sistema implementa una arquitectura separada orientada a servicios:
**Usuario → Interfaz Web (PHP/Laravel) → API REST (Java/Spring Boot) → Base de Datos (PostgreSQL/Supabase)**

- **Laravel** maneja la presentación, el enrutamiento visual, el consumo de la API, y el manejo de estado/sesión. No se conecta directamente a la BD principal.
- **Spring Boot** se encarga de la lógica de negocio, validaciones complejas, persistencia de datos y autorización a nivel de servicios.

## 5. Requisitos
- Docker y Docker Compose (Recomendado para desarrollo unificado)
- PHP 8.2+ y Composer (Si se ejecuta localmente)
- Java 17+ (JDK) y Apache Maven (Si se ejecuta localmente)
- PostgreSQL (Local o cuenta en Supabase)
- Node.js y NPM (para compilar assets de Laravel)

## 6. Estructura del Proyecto
```
SICAPS-WEB/
├── frontend/        # Aplicación Laravel (PHP)
├── backend/         # API REST Spring Boot (Java)
├── database/        # Scripts SQL de base de datos extra (opcional)
├── docs/            # Documentación académica (SENA) y diagramas
├── tests/           # Pruebas de integración
└── README.md
```

## 7. Instrucciones de Despliegue (Render & Supabase)

El proyecto está diseñado de forma contenerizada (Docker) para un despliegue nativo y rápido en la nube.

**Paso 1: Base de Datos en Supabase**
1. Crea un proyecto en [Supabase](https://supabase.com).
2. Ve a la configuración de la Base de Datos (Database Settings) y copia los parámetros de conexión (Host, Port, User, Password).

**Paso 2: Backend Java en Render**
1. Crea un **Web Service** en [Render](https://render.com).
2. Conecta este repositorio de GitHub.
3. Especifica el "Root Directory" como `backend`.
4. Elige como "Environment" la opción **Docker**.
5. Añade las Variables de Entorno:
   - `DB_URL`: `jdbc:postgresql://<TU_HOST_SUPABASE>:5432/postgres`
   - `DB_USERNAME`: `<TU_USUARIO_SUPABASE>`
   - `DB_PASSWORD`: `<TU_CONTRASEÑA_SUPABASE>`
6. Despliega y copia la URL generada (ej: `https://sicaps-backend.onrender.com`).

**Paso 3: Frontend Laravel en Render**
1. Crea otro **Web Service** en Render.
2. Especifica el "Root Directory" como `frontend`.
3. Elige como "Environment" la opción **Docker**.
4. Añade las Variables de Entorno:
   - `APP_KEY`: *(Genera uno ejecutando `php -r "echo 'base64:'.base64_encode(random_bytes(32));"`)*
   - `API_BASE_URL`: `https://sicaps-backend.onrender.com/api` (La URL que copiaste en el Paso 2).
5. Despliega la aplicación.

## 8. Usuarios de Prueba
Al ejecutar el proyecto por primera vez, el backend (Java) creará automáticamente los roles e insertará este usuario administrador:
- **Correo:** `admin@sicaps.com`
- **Contraseña:** `admin123`

