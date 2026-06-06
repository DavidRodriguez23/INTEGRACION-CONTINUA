# 🐄 Ganadería Livestock — Integración Continua
**Politécnico Grancolombiano · Integración Continua**  
Grupo 13 · Profesor: John Olarte

**Integrantes:**
-JUAN GUZMAN PARRA
- JUAN PABLO PARRA BARÓN
- DAVID PERALTA ROZO
- JUAN RAMIREZ VASQUEZ
- DAVID FRANCISCO RODRIGUEZ VILLEGAS

---

## 🏗️ Arquitectura de contenedores
┌─────────────────────────────────────────┐
│           Red: ganandez-net             │
│                                         │
│  ┌──────────────┐  ┌──────────────┐     │
│  │ ganandez_web │  │  ganandez_db │     │
│  │ PHP 8.1      │◄─┤  MySQL 8.0   │     │
│  │ Apache       │  │  Puerto 3306 │     │
│  │ Puerto 8080  │  └──────────────┘     │
│  └──────────────┘                       │
│                                         │
│  ┌──────────────────┐                   │
│  │ ganandez_jenkins │                   │
│  │ Jenkins LTS      │                   │
│  │ Puerto 8081      │                   │
│  └──────────────────┘                   │
└─────────────────────────────────────────┘

## 🚀 Instrucciones para ejecutar

### Requisitos
- Docker Desktop instalado
- Git

### Pasos

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
- `ganandez_web`: PHP 8.1 + Apache, sirve la aplicación Ganadería Livestock
- `ganandez_db`: MySQL 8.0, base de datos inicializada automáticamente con `sql/IT.sql`

## ⚙️ Entrega 2 — Jenkins (Semana 5)
Tercer contenedor `ganandez_jenkins` agregado a la misma red. Pipeline CI definido en `Jenkinsfile` con las etapas:
1. Clonar repositorio
2. Verificar archivos
3. Construir contenedor web
4. Desplegar aplicación
5. Verificar despliegue
