# 🐄 Ganadería Livestock — Integración Continua

**Politécnico Grancolombiano · Énfasis Profesional I**
Grupo 13 · Profesor: John Olarte

**Integrantes:**
- JUAN GUZMAN PARRA
- JUAN PABLO PARRA BARÓN
- DAVID PERALTA ROZO
- JUAN RAMIREZ VASQUEZ
- DAVID FRANCISCO RODRIGUEZ VILLEGAS


---

## 🏗️ Arquitectura de contenedores

La aplicación corre en tres contenedores Docker conectados mediante la red bridge `ganandez-net`:

| Contenedor | Imagen | Puerto | Rol |
|---|---|---|---|
| `ganandez_web` | PHP 8.1 + Apache | 8080 | Aplicación web |
| `ganandez_db` | MySQL 8.0 | 3306 (interno) | Base de datos |
| `ganandez_jenkins` | Jenkins LTS | 8081 | Servidor CI |

> `ganandez_web` depende de `ganandez_db` y espera a que esté saludable antes de iniciar.
> Los tres contenedores cuentan con `healthcheck` para diagnóstico de estado.

El contenedor `ganandez_jenkins` se ejecuta como `root` y monta el socket de Docker
(`/var/run/docker.sock`) y el binario `docker` del host. Esto permite que el pipeline
construya imágenes y despliegue contenedores desde dentro de Jenkins.

---

## 🚀 Instrucciones para ejecutar

**Requisitos:** Docker Desktop y Git instalados.

```bash
# 1. Clonar el repositorio
git clone https://github.com/DavidRodriguez23/INTEGRACION-CONTINUA.git
cd INTEGRACION-CONTINUA

# 2. Levantar los tres contenedores
docker compose up -d

# 3. Verificar que están corriendo
docker ps
```

### Acceder a los servicios

| Servicio | URL |
|---|---|
| Aplicación web | http://localhost:8080 |
| Jenkins | http://localhost:8081 |

---

## 📦 Entrega 1 — Docker (Semana 3)

Dos contenedores comunicados entre sí mediante red bridge `ganandez-net`:

- **`ganandez_web`**: PHP 8.1 + Apache, sirve la aplicación Ganadería Livestock
- **`ganandez_db`**: MySQL 8.0, base de datos inicializada automáticamente con `sql/IT.sql`

## ⚙️ Entrega 2 — Jenkins (Semana 5)

Tercer contenedor `ganandez_jenkins` agregado a la misma red. Pipeline CI definido en `Jenkinsfile` con las siguientes etapas:

1. **Clonar repositorio** — `checkout scm`
2. **Pruebas (PHPUnit)** — instala dependencias con Composer y ejecuta las pruebas unitarias, generando un reporte JUnit en `build/junit.xml`
3. **Construir imagen** — `docker compose build`
4. **Desplegar** — `docker compose up -d`
5. **Smoke test** — verifica con `curl` que la aplicación responda en `http://localhost:8080`
6. **Reporte de integración** — resumen del estado del build

El bloque `post { always }` publica los resultados JUnit y archiva el reporte como artefacto del build.
El pipeline incluye `timeout(30 MINUTES)` y `disableConcurrentBuilds()` para mayor robustez.

### 🧪 Pruebas automatizadas

Las pruebas unitarias usan **PHPUnit** y cubren la lógica de validación pura
(`app/Validaciones.inc.php`). Para ejecutarlas localmente:

```bash
# Requiere PHP + Composer (o usar el contenedor composer:2)
composer install
vendor/bin/phpunit
```

### 🔔 Webhook de GitHub

El pipeline se dispara automáticamente con cada `push` gracias a `triggers { githubPush() }`.
Para activarlo, configure un webhook en GitHub:

**Settings → Webhooks → Add webhook**
- *Payload URL:* `http://<host-jenkins>:8081/github-webhook/`
- *Content type:* `application/json`
- *Event:* `Just the push event`

Requiere el plugin **GitHub** instalado en Jenkins.
