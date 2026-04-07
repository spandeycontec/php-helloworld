pipeline {
    agent any

    stages {

        stage('Clone Code') {
            steps {
                git 'https://github.com/spandeycontec/php-helloworld.git'
            }
        }

        stage('Build') {
            steps {
                sh '''
                echo "No build step required for plain PHP"
                '''
            }
        }

        stage('Test') {
            steps {
                sh '''
                echo "Running basic checks..."
                php -v
                git --version

                # Check PHP syntax (very useful)
                find . -name "*.php" -exec php -l {} \\;

                echo "Syntax check completed!"
                '''
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                echo "Deploying to /var/www/html..."

                # Remove old files
                rm -rf /var/www/html/*

                # Copy new files
                cp -r * /var/www/html/

                echo "Deployment completed!"
                '''
            }
        }
    }
}