# Manual Técnico - SICAPS Web

Este manual describe el proceso de configuración y despliegue del sistema SICAPS Web.

## 1. Arquitectura
- **Frontend:** Laravel 11 (PHP 8.2), Blade, TailwindCSS.
- **Backend:** Spring Boot 3 (Java 17), Spring Security (JWT), Spring Data JPA.
- **Base de Datos:** PostgreSQL 15.

## 2. Variables de Entorno

### Backend (Spring Boot)
Debe definir las siguientes variables en su entorno (ej. en Render):
- `DB_URL`: jdbc:postgresql://<host>:<port>/<dbname>
- `DB_USERNAME`: Usuario de la BD.
- `DB_PASSWORD`: Contraseña de la BD.
- `jwt.secret`: Clave secreta en Base64 para firmar tokens (mínimo 256 bits).
- `jwt.expiration`: Tiempo de expiración del token en milisegundos (ej. 86400000 para 24h).

### Frontend (Laravel)
Debe definir en su archivo `.env` o en el entorno de despliegue:
- `API_BASE_URL`: URL pública donde se despliegue el backend de Spring Boot (ej: `https://sicaps-backend.onrender.com/api`).
- `APP_KEY`: Clave de cifrado de Laravel (generada con `php artisan key:generate`).
- `APP_URL`: URL pública del frontend.

## 3. Despliegue en Producción (Render + Supabase)

1. **Base de Datos (Supabase):**
   - Cree un nuevo proyecto en Supabase.
   - En *Project Settings -> Database*, obtenga la cadena de conexión URI (PostgreSQL).

2. **Backend (Render):**
   - Cree un nuevo "Web Service" en Render.
   - Conecte su repositorio de GitHub seleccionando la carpeta `backend/`.
   - Entorno: **Docker**.
   - Añada las variables de entorno (`DB_URL`, `DB_USERNAME`, `DB_PASSWORD`) con los datos de Supabase.

3. **Frontend (Render):**
   - Cree otro "Web Service" en Render.
   - Conecte el repositorio seleccionando la carpeta `frontend/`.
   - Entorno: **Docker**.
   - Añada las variables `API_BASE_URL` apuntando al Web Service del Backend creado en el paso anterior.
   - Añada un `APP_KEY` válido de Laravel.

## 4. Comandos de Desarrollo Local (Docker Compose)
Si desea ejecutar todo en su máquina local:
```bash
# Iniciar servicios (PostgreSQL, Java, PHP)
docker-compose up -d --build

# Ver logs del backend
docker logs sicaps_backend -f

# Detener servicios
docker-compose down
```
