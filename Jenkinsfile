pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Construyendo proyecto PHP...'
                // Aquí puedes poner comandos batch para instalar dependencias, correr pruebas, etc.
                // Por ejemplo, si usas Composer:
                bat 'composer install --no-interaction --prefer-dist'
            }
        }
        stage('Test') {
            steps {
                echo 'Ejecutando tests PHP...'
                // Por ejemplo, si usas PHPUnit
                bat '.\\vendor\\bin\\phpunit --configuration phpunit.xml'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Desplegando proyecto PHP...'
                // Aquí comandos batch para desplegar tu proyecto, como copiar archivos al servidor, etc.
            }
        }
    }

    post {
        success {
            emailext(
                subject: "✅ Éxito en pipeline: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """Hola Milena,

Tu pipeline de PHP se ejecutó correctamente.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
URL: ${env.BUILD_URL}
""",
                to: "jhonnyarias712@gmail.com"
            )
        }
        failure {
            emailext(
                subject: "❌ Fallo en pipeline: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """Hola Milena,

El pipeline de PHP ha fallado. Revisa los detalles:

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
URL: ${env.BUILD_URL}
""",
                to: "jhonnyarias712@gmail.com"
            )
        }
    }
}
