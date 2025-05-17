pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Construyendo proyecto...'
                // Aquí pon tus comandos de build, test, deploy, etc.
            }
        }
    }

    post {
        success {
            emailext(
                subject: "✅ Éxito en pipeline: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """Hola Milena,

Tu pipeline se ejecutó correctamente.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
URL: ${env.BUILD_URL}
""",
                to: "milena.nicole.mariscal@gmail.com"
            )
        }
        failure {
            emailext(
                subject: "❌ Fallo en pipeline: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """Hola Milena,

El pipeline ha fallado. Revisa los detalles:

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
URL: ${env.BUILD_URL}
""",
                to: "milena.nicole.mariscal@gmail.com"
            )
        }
    }
	
}
