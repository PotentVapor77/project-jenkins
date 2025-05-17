pipeline {
    agent any  // Ejecuta en cualquier agente disponible

    stages {
        stage('Build') {
            steps {
                echo 'Ejecutando etapa de Build...'
                // Ejemplo: sh 'mvn clean package'
            }
        }
        stage('Test') {
            steps {
                echo 'Ejecutando pruebas...'
                // Ejemplo: sh 'mvn test'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Desplegando aplicación...'
                // Ejemplo: sh 'scp target/*.jar user@server:/deploy'
            }
        }
    }

    post {
        always {
            echo 'Pipeline completado.'
        }
        success {
            echo '¡Pipeline exitoso!'
            emailext (
                subject: "✅ Pipeline ${JOB_NAME} #${BUILD_NUMBER} - ÉXITO",
                body: "La ejecución del pipeline fue exitosa.\nVer detalles: ${BUILD_URL}",
                to: 'jhonnyarias712@gmail.com'  // Correo actualizado
            )
        }
        failure {
            echo 'Pipeline fallido. Revisar logs.'
            emailext (
                subject: "❌ Pipeline ${JOB_NAME} #${BUILD_NUMBER} - FALLO",
                body: "Hubo un error en el pipeline.\nVer detalles: ${BUILD_URL}",
                to: 'jhonnyarias712@gmail.com'  // Correo actualizado
            )
        }
    }
}