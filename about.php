________________________________________
Complete Working Jenkins + PHP Deployment on Ubuntu 24 WSL
________________________________________
1️⃣ Update System & Install Prerequisites
sudo apt update
sudo apt install -y curl gnupg apt-transport-https git php libapache2-mod-php apache2 unzip
________________________________________
2️⃣ Install Java 21
sudo apt install -y openjdk-21-jdk
java -version
•	If multiple Java versions exist: 
sudo update-alternatives --config java
sudo update-alternatives --config javac
________________________________________
3️⃣ Install Git
sudo apt install -y git
git --version
________________________________________
4️⃣ Install Apache & PHP
sudo apt install -y apache2 php libapache2-mod-php
sudo systemctl enable apache2
sudo systemctl start apache2

# Make web root writable by WSL user
sudo chown -R $(whoami):$(whoami) /var/www/html
•	Web root: /var/www/html 
________________________________________
5️⃣ Install Jenkins (WAR Method)
mkdir -p ~/jenkins
cd ~/jenkins
curl -LO https://get.jenkins.io/war-stable/latest/jenkins.war
•	Run Jenkins: 
java -jar ~/jenkins/jenkins.war --httpPort=8080
•	Optional: run in background so it stays running: 
nohup java -jar ~/jenkins/jenkins.war --httpPort=8080 > ~/jenkins/jenkins.log 2>&1 &
•	Access Jenkins in browser: http://localhost:8080 
•	Unlock using initial admin password: 
cat ~/.jenkins/secrets/initialAdminPassword
________________________________________
6️⃣ Install Required Jenkins Plugins
1.	Git Plugin 
2.	GitHub Plugin 
3.	Credentials Plugin 
•	Go to Manage Jenkins → Manage Plugins → Available → Install Suggested Plugins 
________________________________________
7️⃣ Add GitHub Credentials
1.	Manage Jenkins → Credentials → Global → Add Credentials 
2.	Kind: Secret text 
3.	Secret: GitHub Personal Access Token (PAT) 
4.	ID/Description: github-pat 
5.	Scope: Global 
________________________________________
8️⃣ Configure Git in Jenkins
1.	Manage Jenkins → Global Tool Configuration → Git 
2.	Add Git if missing: 
o	Name: Default 
o	Path: /usr/bin/git (or leave blank if in PATH) 
________________________________________
9️⃣ Create Freestyle PHP Project
1.	New Item → Freestyle Project → Name: php_free_style_project 
2.	Source Code Management → Git: 
o	Repository URL: https://github.com/username/php-helloworld.git 
o	Credentials: github-pat 
3.	Build → Execute Shell: 
#!/bin/bash
# Remove old deployment
rm -rf /var/www/html/*

# Copy new files from Jenkins workspace
cp -r "$WORKSPACE/"* /var/www/html/

# Set permissions
chmod -R 755 /var/www/html/
$WORKSPACE handles spaces in job paths safely.

