package com.sicaps.backend.config;

import com.zaxxer.hikari.HikariConfig;
import com.zaxxer.hikari.HikariDataSource;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Primary;

import javax.sql.DataSource;

@Configuration
public class DataSourceConfig {

    private static final Logger log = LoggerFactory.getLogger(DataSourceConfig.class);

    @Value("${spring.datasource.url:jdbc:postgresql://localhost:5432/sicaps_db}")
    private String defaultUrl;

    @Value("${spring.datasource.username:postgres}")
    private String defaultUsername;

    @Value("${spring.datasource.password:postgres}")
    private String defaultPassword;

    @Bean
    @Primary
    public DataSource dataSource() {
        String dbUrl = System.getenv("DATABASE_URL");
        if (dbUrl == null || dbUrl.isBlank()) {
            dbUrl = System.getenv("DB_URL");
        }
        if (dbUrl == null || dbUrl.isBlank()) {
            dbUrl = defaultUrl;
        }

        // Si estamos en la nube (Render o Railway) y la URL apunta a localhost, avisar con un error claro
        boolean isCloud = System.getenv("RENDER") != null || System.getenv("RAILWAY_ENVIRONMENT") != null;
        if (isCloud && dbUrl.contains("localhost:5432")) {
            log.error("=================================================================================");
            log.error("❌ ERROR CRÍTICO: No se ha configurado la variable DATABASE_URL en Render.");
            log.error("👉 Ve a tu servicio en Render -> pestaña 'Environment' -> añade la variable:");
            log.error("   DATABASE_URL = (tu enlace de conexión de Neon PostgreSQL)");
            log.error("=================================================================================");
            throw new IllegalStateException("DATABASE_URL no configurada en Render. Debes agregar la variable DATABASE_URL en la pestaña Environment de Render con tu URL de Neon.");
        }

        HikariConfig config = new HikariConfig();
        config.setDriverClassName("org.postgresql.Driver");
        config.setMaximumPoolSize(10);
        config.setMinimumIdle(2);
        config.setIdleTimeout(300000);
        config.setConnectionTimeout(20000);
        config.setMaxLifetime(1200000);

        // Convertir URL estándar de PostgreSQL (postgres:// o postgresql:// de Neon/Render) al formato JDBC
        String jdbcUrl;
        if (dbUrl.startsWith("postgres://")) {
            jdbcUrl = "jdbc:postgresql://" + dbUrl.substring(11);
        } else if (dbUrl.startsWith("postgresql://")) {
            jdbcUrl = "jdbc:postgresql://" + dbUrl.substring(13);
        } else {
            jdbcUrl = dbUrl;
        }

        log.info("Iniciando conexión a base de datos PostgreSQL...");
        config.setJdbcUrl(jdbcUrl);

        // Si la URL no contiene credenciales embebidas (user:pass@), aplicar las variables de entorno
        String envUser = System.getenv("DB_USERNAME");
        String envPass = System.getenv("DB_PASSWORD");
        if (!jdbcUrl.contains("@")) {
            config.setUsername(envUser != null && !envUser.isBlank() ? envUser : defaultUsername);
            config.setPassword(envPass != null && !envPass.isBlank() ? envPass : defaultPassword);
        }

        return new HikariDataSource(config);
    }
}
