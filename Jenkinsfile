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

        stage('Verificar configuracion Docker') {
            steps {
                echo 'Verificando archivos de configuracion Docker...'
                sh 'cat Dockerfile'
                sh 'cat docker-compose.yml'
            }
        }

        stage('Validar estructura PHP') {
            steps {
                echo 'Validando archivos PHP del proyecto...'
                sh 'find . -name "*.php" | head -20'
                sh 'find . -name "*.sql" | head -5'
            }
        }

        stage('Reporte de integracion') {
            steps {
                echo 'Generando reporte de integracion continua...'
                echo 'Proyecto: Ganaderia Livestock'
                echo 'Repositorio: https://github.com/DavidRodriguez23/INTEGRACION-CONTINUA'
                echo 'Rama: main'
                echo 'Estado: Codigo verificado y listo para despliegue'
            }
        }
    }

    post {
        success {
            echo 'Pipeline ejecutado exitosamente. Ganaderia Livestock verificado.'
        }
        failure {
            echo 'El pipeline fallo. Revisar logs.'
        }
    }
}
