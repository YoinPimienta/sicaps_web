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

        dbUrl = dbUrl.trim();

        // Si estamos en la nube (Render o Railway) y la URL apunta a localhost, avisar con un error claro
        boolean isCloud = System.getenv("RENDER") != null || System.getenv("RAILWAY_ENVIRONMENT") != null;
        if (isCloud && dbUrl.contains("localhost:5432")) {
            log.error("=================================================================================");
            log.error("❌ ERROR: No se ha configurado la variable DATABASE_URL en Render.");
            log.error("👉 Ve a tu servicio en Render -> pestaña 'Environment' -> añade DATABASE_URL.");
            log.error("=================================================================================");
            throw new IllegalStateException("DATABASE_URL no configurada en Render.");
        }

        HikariConfig config = new HikariConfig();
        config.setDriverClassName("org.postgresql.Driver");
        config.setMaximumPoolSize(6);
        config.setMinimumIdle(1);
        config.setIdleTimeout(60000); // 1 minuto
        config.setConnectionTimeout(30000); // 30 segundos
        config.setMaxLifetime(300000); // 5 minutos (renueva antes de que Neon cierre el socket)
        config.setKeepaliveTime(30000); // Pings periódicos cada 30 segundos
        config.setValidationTimeout(5000);
        config.setConnectionTestQuery("SELECT 1");

        // Procesar URL estándar de PostgreSQL (ej. postgresql://user:pass@host/db) para cumplir con el formato JDBC
        String raw = dbUrl;
        if (raw.startsWith("jdbc:")) {
            raw = raw.substring(5);
        }
        if (raw.startsWith("postgres://")) {
            raw = raw.substring(11);
        } else if (raw.startsWith("postgresql://")) {
            raw = raw.substring(13);
        }

        String jdbcUrl;
        String user = null;
        String pass = null;

        if (raw.contains("@")) {
            int atPos = raw.indexOf("@");
            String credentials = raw.substring(0, atPos);
            String hostAndRest = raw.substring(atPos + 1);

            if (credentials.contains(":")) {
                int colonPos = credentials.indexOf(":");
                user = credentials.substring(0, colonPos);
                pass = credentials.substring(colonPos + 1);
            } else {
                user = credentials;
            }

            jdbcUrl = "jdbc:postgresql://" + hostAndRest;
        } else {
            jdbcUrl = "jdbc:postgresql://" + raw;
        }

        // Si es una conexión remota a Neon o Render, asegurar parámetros de estabilidad de conexión
        if (!jdbcUrl.contains("localhost:5432")) {
            char separator = jdbcUrl.contains("?") ? '&' : '?';
            if (!jdbcUrl.contains("sslmode=")) {
                jdbcUrl += separator + "sslmode=require";
                separator = '&';
            }
            if (!jdbcUrl.contains("tcpKeepAlive=")) {
                jdbcUrl += separator + "tcpKeepAlive=true";
                separator = '&';
            }
            if (!jdbcUrl.contains("connectTimeout=")) {
                jdbcUrl += separator + "connectTimeout=20";
                separator = '&';
            }
            if (!jdbcUrl.contains("socketTimeout=")) {
                jdbcUrl += separator + "socketTimeout=45";
            }
        }

        log.info("Conectando a base de datos PostgreSQL: {}", jdbcUrl.replaceAll("\\?.*", ""));
        config.setJdbcUrl(jdbcUrl);

        if (user != null && !user.isBlank()) {
            config.setUsername(user);
        } else {
            String envUser = System.getenv("DB_USERNAME");
            config.setUsername(envUser != null && !envUser.isBlank() ? envUser : defaultUsername);
        }

        if (pass != null && !pass.isBlank()) {
            config.setPassword(pass);
        } else {
            String envPass = System.getenv("DB_PASSWORD");
            config.setPassword(envPass != null && !envPass.isBlank() ? envPass : defaultPassword);
        }

        return new HikariDataSource(config);
    }
}
