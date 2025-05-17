pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo 'Instalando dependencias con Composer...'
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Test') {
            steps {
                echo 'Ejecutando pruebas unitarias con PHPUnit...'
                sh './vendor/bin/phpunit tests' // Ajusta la ruta y carpeta de tests si es necesario
            }
        }

        stage('Deploy') {
            steps {
                echo 'Desplegando aplicación (simulado)...'
                sh 'echo "Despliegue completado"'
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
