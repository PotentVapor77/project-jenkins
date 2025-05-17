pipeline {
    agent any

    environment {
        NODE_ENV = 'development'
    }

    tools {
        nodejs 'NodeJS 18'  // Asegúrate de tener esta herramienta configurada en Jenkins
    }

    stages {
        stage('Build') {
            steps {
                echo 'Instalando dependencias...'
                sh 'npm install'
            }
        }

        stage('Test') {
            steps {
                echo 'Ejecutando pruebas...'
                sh 'npm test'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Desplegando en entorno de pruebas...'
                // Puedes usar Docker, subir a un servidor, etc.
                // Aquí solo mostramos un mensaje
                sh 'echo "Despliegue simulado exitoso"'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline ejecutado correctamente.'
        }
        failure {
            echo '❌ Error durante la ejecución del pipeline.'
        }
    }
}
