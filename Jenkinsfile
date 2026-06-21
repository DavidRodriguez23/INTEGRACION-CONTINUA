pipeline {
    agent any

    environment {
        APP_NAME = 'Ganaderia-Livestock'
        PHP_VERSION = '8.1'
    }

    stages {

        stage('Checkout') {
            steps {
                echo '=== Clonando repositorio desde GitHub ==='
                checkout scm
                echo "Rama: ${env.GIT_BRANCH}"
                echo "Commit: ${env.GIT_COMMIT}"
            }
        }

        stage('Verificar entorno') {
            steps {
                echo '=== Verificando herramientas disponibles ==='
                sh 'php --version'
                sh 'docker --version || echo "Docker no disponible en este agente"'
                sh 'echo "Directorio de trabajo: $(pwd)"'
                sh 'ls -la'
            }
        }

        stage('Validar sintaxis PHP') {
            steps {
                echo '=== Validando sintaxis de archivos PHP ==='
                sh '''
                    echo "Archivos PHP encontrados:"
                    find . -name "*.php" | grep -v vendor | head -30

                    echo ""
                    echo "Ejecutando validacion de sintaxis..."
                    ERROR=0
                    for file in $(find . -name "*.php" | grep -v vendor); do
                        php -l "$file" > /dev/null 2>&1 || { echo "ERROR de sintaxis en: $file"; ERROR=1; }
                    done

                    if [ $ERROR -eq 0 ]; then
                        echo "✔ Todos los archivos PHP son validos"
                    else
                        echo "✘ Se encontraron errores de sintaxis"
                        exit 1
                    fi
                '''
            }
        }

        stage('Verificar estructura del proyecto') {
            steps {
                echo '=== Verificando estructura de directorios y archivos clave ==='
                sh '''
                    ERRORES=0

                    check_file() {
                        if [ -f "$1" ]; then
                            echo "  ✔ $1"
                        else
                            echo "  ✘ FALTA: $1"
                            ERRORES=1
                        fi
                    }

                    check_dir() {
                        if [ -d "$1" ]; then
                            echo "  ✔ $1/"
                        else
                            echo "  ✘ FALTA directorio: $1/"
                            ERRORES=1
                        fi
                    }

                    echo "Archivos raiz:"
                    check_file "Dockerfile"
                    check_file "docker-compose.yml"
                    check_file "index.php"
                    check_file ".htaccess"

                    echo "Directorios:"
                    check_dir "app"
                    check_dir "vistas"
                    check_dir "sql"
                    check_dir "css"

                    if [ $ERRORES -eq 0 ]; then
                        echo ""
                        echo "✔ Estructura del proyecto correcta"
                    else
                        echo ""
                        echo "✘ Faltan archivos o directorios requeridos"
                        exit 1
                    fi
                '''
            }
        }

        stage('Verificar Dockerfile') {
            steps {
                echo '=== Verificando configuracion de Docker ==='
                sh '''
                    echo "Contenido del Dockerfile:"
                    cat Dockerfile

                    echo ""
                    echo "Verificando instrucciones minimas..."
                    grep -q "FROM" Dockerfile && echo "  ✔ FROM definido" || { echo "  ✘ FROM no encontrado"; exit 1; }
                    grep -q "php" Dockerfile && echo "  ✔ Imagen PHP detectada" || echo "  ⚠ No se detectó imagen PHP explícita"
                    grep -q "pdo_mysql" Dockerfile && echo "  ✔ Extension PDO MySQL incluida" || echo "  ⚠ Extension PDO MySQL no encontrada"
                    echo ""
                    echo "✔ Dockerfile valido"
                '''
            }
        }

    }

    post {
        success {
            echo """
            ==========================================
            ✔ PIPELINE EXITOSO — ${APP_NAME}
            Rama    : ${env.GIT_BRANCH ?: 'main'}
            Commit  : ${env.GIT_COMMIT ?: 'N/A'}
            ==========================================
            """
        }
        failure {
            echo """
            ==========================================
            ✘ PIPELINE FALLIDO — ${APP_NAME}
            Revisa los logs para identificar el error.
            ==========================================
            """
        }
        always {
            echo '=== Pipeline finalizado ==='
        }
    }
}
