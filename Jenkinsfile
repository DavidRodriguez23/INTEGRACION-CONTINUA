pipeline {
    agent any

    options {
        // 3.2 - Un build colgado libera el agente tras 30 minutos.
        timeout(time: 30, unit: 'MINUTES')
        // 3.3 - Evita que dos builds simultaneos se pisen Docker Compose.
        disableConcurrentBuilds()
    }

    triggers {
        // 1.4 - Dispara el pipeline automaticamente con el webhook de GitHub.
        // Requiere el plugin "GitHub" y el webhook configurado en
        // GitHub -> Settings -> Webhooks -> http://<jenkins>:8081/github-webhook/
        githubPush()
    }

    stages {

        stage('Clonar repositorio') {
            steps {
                echo 'Clonando repositorio de Ganaderia Livestock...'
                checkout scm
            }
        }

        stage('Pruebas (PHPUnit)') {
            steps {
                echo 'Ejecutando pruebas unitarias con PHPUnit...'
                // Se ejecutan dentro de un contenedor efimero con PHP + Composer.
                sh '''
                    docker run --rm \
                        -v "$WORKSPACE":/app -w /app \
                        composer:2 sh -c "composer install --no-interaction && vendor/bin/phpunit --log-junit build/junit.xml"
                '''
            }
        }

        stage('Construir imagen') {
            steps {
                echo 'Construyendo la imagen de la aplicacion...'
                sh 'docker compose build'
            }
        }

        stage('Desplegar') {
            steps {
                echo 'Levantando los contenedores...'
                sh 'docker compose up -d'
            }
        }

        stage('Smoke test') {
            steps {
                echo 'Verificando que la aplicacion responde...'
                // Espera a que el contenedor web este arriba y responde 200.
                sh '''
                    for i in $(seq 1 10); do
                        if curl -fsS http://localhost:8080/ > /dev/null; then
                            echo "Aplicacion respondiendo correctamente."
                            exit 0
                        fi
                        echo "Esperando a la aplicacion... intento $i"
                        sleep 5
                    done
                    echo "La aplicacion no respondio a tiempo."
                    exit 1
                '''
            }
        }

        stage('Reporte de integracion') {
            steps {
                echo 'Generando reporte de integracion continua...'
                echo 'Proyecto: Ganaderia Livestock'
                echo 'Repositorio: https://github.com/DavidRodriguez23/INTEGRACION-CONTINUA'
                echo 'Estado: Codigo probado, construido y desplegado.'
            }
        }
    }

    post {
        // 3.6 - Conserva el reporte de pruebas y publica los resultados JUnit en cada build.
        always {
            junit allowEmptyResults: true, testResults: 'build/junit.xml'
            archiveArtifacts artifacts: 'build/junit.xml', allowEmptyArchive: true, fingerprint: true
        }
        success {
            echo 'Pipeline ejecutado exitosamente. Ganaderia Livestock desplegado.'
        }
        failure {
            echo 'El pipeline fallo. Revisar logs.'
        }
    }
}
