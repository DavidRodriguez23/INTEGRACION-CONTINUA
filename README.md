# 🐄 Ganadería Livestock — Integración Continua

**Politécnico Grancolombiano · Énfasis Profesional I**
Grupo 13 · Profesor: John Olarte

**Integrantes:**
- Juan Pablo Parra Barón
- David Peralta Rozo
- Juan Ramírez Vásquez
- David Francisco Rodríguez Villegas

---

## 🏗️ Arquitectura de contenedores

La aplicación corre en tres contenedores Docker conectados mediante la red bridge `ganandez-net`:

| Contenedor | Imagen | Puerto | Rol |
|---|---|---|---|
| `ganandez_web` | PHP 8.1 + Apache | 8080 | Aplicación web |
| `ganandez_db` | MySQL 8.0 | 3306 (interno) | Base de datos |
| `ganandez_jenkins` | Jenkins LTS | 8081 | Servidor CI |

> `ganandez_web` depende de `ganandez_db` y espera a que esté saludable antes de iniciar.

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

1. Clonar repositorio
2. Verificar archivos
3. Construir contenedor web
4. Desplegar aplicación
5. Verificar despliegue
