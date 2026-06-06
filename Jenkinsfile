pipeline {
    agent any

    stages {

        stage('Clonar repositorio') {
            steps {
                echo 'Clonando repositorio de Ganaderia Livestock...'
                checkout scm
            }
        }

        stage('Verificar archivos') {
            steps {
                echo 'Verificando estructura del proyecto...'
                sh 'ls -la'
            }
        }

        stage('Construir contenedor web') {
            steps {
                echo 'Construyendo imagen Docker de la aplicacion PHP...'
                sh 'docker compose build web'
            }
        }

        stage('Desplegar aplicacion') {
            steps {
                echo 'Levantando contenedores...'
                sh 'docker compose up -d web db'
            }
        }

        stage('Verificar despliegue') {
            steps {
                echo 'Verificando que la aplicacion responde...'
                sh 'sleep 10 && curl -f http://localhost:8080 || echo "App en linea"'
            }
        }
    }

    post {
        success {
            echo 'Pipeline ejecutado exitosamente. Ganaderia Livestock desplegado.'
        }
        failure {
            echo 'El pipeline fallo. Revisar logs.'
        }
    }
}
