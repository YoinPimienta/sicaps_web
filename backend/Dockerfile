FROM maven:3.9.6-eclipse-temurin-17-alpine AS builder

WORKDIR /app

# Copiar archivos de Maven
COPY pom.xml .

# Descargar dependencias
RUN mvn dependency:go-offline || true

# Copiar código fuente
COPY src src

# Limitar memoria de Maven para compilar sin problemas en máquinas gratuitas de Render
ENV MAVEN_OPTS="-Xmx384m -XX:+TieredCompilation -XX:TieredStopAtLevel=1"

# Compilar proyecto optimizado
RUN mvn clean package -DskipTests

# --- Segunda etapa: ejecución optimizada ---
FROM eclipse-temurin:17-jre-alpine
WORKDIR /app

# Copiar el jar generado
COPY --from=builder /app/target/*.jar app.jar

EXPOSE 8080

# Optimización de JVM para arranque ultra-rápido y bajo consumo de memoria en la nube
ENTRYPOINT ["java", "-XX:+UseContainerSupport", "-XX:MaxRAMPercentage=75.0", "-XX:+TieredCompilation", "-XX:TieredStopAtLevel=1", "-Djava.security.egd=file:/dev/./urandom", "-jar", "app.jar"]
