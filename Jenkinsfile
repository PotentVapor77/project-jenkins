pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm  // Descarga el código del repositorio
            }
        }

        stage('Build') {
            steps {
                echo '✅ Etapa de Build (simulada)'  // Mensaje de confirmación
                bat 'echo "Build completado"'  // Comando de prueba para Windows
            }
        }

        stage('Deploy') {
            steps {
                echo '🚀 Etapa de Deploy (simulada)'
                bat 'echo "Despliegue exitoso"'  // Comando de prueba para Windows
            }
        }
    }

    post {
        success {
            echo '🎉 Pipeline ejecutado correctamente'
        }
        failure {
            echo '❌ Error en el pipeline'
        }
    }
}