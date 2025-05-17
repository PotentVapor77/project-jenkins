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
                bat 'composer install --no-interaction --prefer-dist'  // Usa 'bat' en lugar de 'sh'
            }
        }

        stage('Test') {
            steps {
                echo 'Ejecutando pruebas unitarias con PHPUnit...'
                bat 'vendor\\bin\\phpunit tests'  // Usa barras invertidas para rutas en Windows
            }
        }

        stage('Deploy') {
            steps {
                echo 'Desplegando aplicación (simulado)...'
                bat 'echo "Despliegue completado"'
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