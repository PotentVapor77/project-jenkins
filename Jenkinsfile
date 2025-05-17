pipeline {
    agent any

    environment {
        NODE_ENV = 'development'
    }

    tools {
        nodejs 'NodeJS 18'
    }

    stages {
        stage('Checkout') {
            steps {
                // Clona todo el repositorio en el workspace
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo 'Instalando dependencias...'
                sh 'npm install'
            }
        }

        stage('Test') {
            steps {
                echo 'Ejecutando pruebas unitarias...'
                sh 'npm test'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Desplegando aplicación en entorno de prueba...'
                sh 'echo "Despliegue simulado completado."'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline ejecutado exitosamente.'
        }
        failure {
            echo '❌ Error en el pipeline.'
        }
    }
}
