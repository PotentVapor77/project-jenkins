pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo 'Obteniendo código del repositorio'
                checkout scmGit(
                    branches: [[name: '*/main']],
                    userRemoteConfigs: [[
                        credentialsId: 'GithubToken',
                        url: 'https://github.com/PotentVapor77/project-jenkins.git'
                    ]]
                )
            }
        }

        stage('Deploy') {
            steps {
                echo 'Desplegando aplicación PHP'
                
            }
        }
    }

	post {
			success {
				emailext(
					subject: "✅ Build exitoso: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
					body: "El build ${env.JOB_NAME} #${env.BUILD_NUMBER} finalizó correctamente.\nRevisa: ${env.BUILD_URL}",
					to: 'jhonnyarias712@gmail.com'
				)
			}
			failure {
				emailext(
					subject: "❌ Build fallido: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
					body: "El build ${env.JOB_NAME} #${env.BUILD_NUMBER} falló.\nRevisa: ${env.BUILD_URL}",
					to: 'jhonnyarias712@gmail.com'
				)
			}
		}