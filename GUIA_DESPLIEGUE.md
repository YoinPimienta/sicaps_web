# 🚀 Guía de Despliegue en la Nube: SICAPS Web

Esta guía te explica paso a paso cómo desplegar **SICAPS Web** en producción de forma 100% gratuita y con una **Base de Datos PostgreSQL permanente que NO se borra a los pocos días**.

---

## 📌 ¿Por qué la base de datos de Render/Railway se borra?
* **Render:** Elimina las bases de datos gratuitas a los **30 días**.
* **Railway:** Congela o termina los servicios cuando expira el crédito de prueba (30 días).

### 🏆 La Solución: Neon.tech (PostgreSQL Gratuito Permanente)
[Neon](https://neon.tech) ofrece PostgreSQL en la nube con un **plan gratuito permanente (Free Forever)**:
* **No caduca** a los 30 días.
* No requiere tarjeta de crédito.
* 0.5 GB de almacenamiento (más que suficiente para miles de historias clínicas y citas).
* Te entrega una URL de conexión en 1 clic.

---

## 🛠️ Paso 1: Crear tu Base de Datos en Neon (2 minutos)

1. Entra a [https://neon.tech](https://neon.tech) y regístrate con tu cuenta de GitHub o Google.
2. Haz clic en **Create Project**.
3. Asígnale un nombre (ejemplo: `sicaps-db`) y dale a **Create Project**.
4. En el panel principal (**Dashboard**), verás una sección que dice **Connection String**.
5. Asegúrate de que esté seleccionada la opción **Direct connection** (o **Pooled**). Verás una URL como esta:
   ```text
   postgresql://usuario:contraseña@ep-cool-flower-123456.us-east-2.aws.neon.tech/neondb?sslmode=require
   ```
6. **¡Copia esa URL completa!** *(Esa es tu `DATABASE_URL`)*.

> 💡 **Nota:** El backend de SICAPS ya está pre-configurado para auto-detectar este enlace, parsear el usuario/contraseña automáticamente y activar SSL sin que tengas que configurar nada más.

---

## 🛠️ Paso 2: Subir tu Código a GitHub

Abre tu terminal en la carpeta del proyecto `SICAPS-WEB` y ejecuta:

```powershell
git init
git add .
git commit -m "SICAPS Web: Optimizado para producción y alta velocidad"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPOSITORIO.git
git push -u origin main
```

---

## 🛠️ Paso 3: Desplegar en Render (Recomendado)

Render es gratuito y el proyecto ya incluye el archivo `render.yaml` pre-configurado.

1. Ve a [https://render.com](https://render.com) e inicia sesión con tu GitHub.
2. En el panel superior, haz clic en **New +** y selecciona **Blueprint**.
3. Selecciona tu repositorio de SICAPS.
4. Render detectará automáticamente el archivo `render.yaml` y creará los 2 servicios:
   - `sicaps-backend` (Spring Boot en Docker)
   - `sicaps-frontend` (Laravel en Docker)
5. En la pantalla de configuración de variables:
   - En **`DATABASE_URL`** del backend: **Pega la URL de Neon que copiaste en el Paso 1**.
6. Haz clic en **Apply**. Render empezará a compilar ambos servicios.
7. Una vez que termine el despliegue del backend, Render te dará un enlace público (ejemplo: `https://sicaps-backend.onrender.com`).
8. Ve a la configuración de variables del servicio `sicaps-frontend` y añade:
   * **`API_BASE_URL`**: `https://sicaps-backend.onrender.com/api`
9. ¡Listo! Abre el link de tu `sicaps-frontend` en tu navegador. El sistema ya está en línea, rápido y conectado a tu base de datos permanente.

---

## 🛠️ Opción Alternativa: Desplegar en Railway

Si prefieres usar [Railway](https://railway.app):

1. Entra a Railway e inicia sesión con GitHub.
2. Crea un **New Project** > **Deploy from GitHub repo** > Selecciona tu repositorio.
3. En la configuración del servicio, ve a **Settings** > **Root Directory** y pon: `/backend`.
4. En **Variables**, agrega:
   * `DATABASE_URL`: *(La URL que copiaste de Neon)*
   * `PORT`: `8080`
5. Dale a **Generate Domain** para que Railway le asigne un link público al backend (ej: `https://sicaps-backend.up.railway.app`).
6. Ahora, en el mismo proyecto de Railway, dale a **+ New** > **GitHub Repo** (el mismo repo).
7. En los **Settings** de este segundo servicio, en **Root Directory** pon: `/frontend`.
8. En **Variables**, agrega:
   * `API_BASE_URL`: `https://sicaps-backend.up.railway.app/api`
   * `APP_ENV`: `production`
9. Dale a **Generate Domain** al frontend. ¡Y ya está disponible en internet!

---

## ⚡ Mejoras de Velocidad Aplicadas en este Proyecto:
1. **PHP Multi-Workers Concurrente:** Laravel ya no se traba cuando se hacen peticiones simultáneas (`PHP_CLI_SERVER_WORKERS=4`).
2. **Caché de Producción Automático:** En la nube se pre-compilan las rutas, vistas y configuraciones (`config:cache`, `route:cache`, `view:cache`).
3. **Optimización JVM Java:** Se configuraron banderas de contenedor `-XX:+TieredCompilation -XX:TieredStopAtLevel=1 -XX:MaxRAMPercentage=75.0` para que Spring Boot arranque en menos de 8 segundos y use la mitad de memoria RAM.
4. **Pool HikariCP Reciclable:** Evita conexiones colgadas a la base de datos PostgreSQL.
5. **CORS Global:** Permite comunicación transparente entre dominios del Frontend y Backend.
