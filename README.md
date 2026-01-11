# News Management System (PHP + Docker)

A simple News Management System built with PHP and MySQL, containerized using Docker.
This project allows admins to manage news articles and categories through a web interface.

The main goal of this project is to demonstrate GitHub best practices and Dockerization for a PHP application.

---

## 🚀 Tech Stack

- **Backend:** PHP 8.2
- **Web Server:** Apache
- **Database:** MySQL (via XAMPP or external MySQL)
- **Containerization:** Docker
- **Version Control:** Git & GitHub

---

## 📁 Project Structure

news-system/
├─ src/ # PHP source code
├─ README.md # Project documentation
├─ Dockerfile # Docker build instructions
├─ .dockerignore # Files ignored by Docker
├─ .gitignore # Files ignored by Git
├─ database.sql # Database schema
├─ docs/
│ ├─ screenshots/ # Assignment screenshots
│ └─ notes.md # Technical notes


---

## 🐳 How to Build and Run with Docker

### 1️⃣ Prerequisites
Make sure you have:
- Docker Desktop installed and running
- Git installed
- MySQL running locally (XAMPP)

---

### 2️⃣ Clone the Repository
git clone https://github.com/aseelomar-2004/news-management.git
cd news-management

3️⃣ Build the Docker Image
docker build -t news-system-app .
⏳ The first build may take several minutes because the PHP base image is downloaded.

4️⃣ Run the Container
docker run -d -p 8080:80 --name news-system-container news-system-app


5️⃣ Open the Application
Open your browser and go to:

http://localhost:8080

🛑 Stop and Clean Up
Stop the container
docker stop news-system-container
Remove the container
docker rm news-system-container
(Optional) Remove the image
docker rmi news-system-app

⚙️ Configuration Notes
Apache Port: 8080 → mapped to container port 80

Database Host: host.docker.internal

Database Name: news_system

Database User: root

Database Password: empty (default XAMPP setup)

Database configuration can be found in:


src/config/config.php


🧪 How to Test the Project
Make sure MySQL is running in XAMPP

Import the database:

Open phpMyAdmin

Create a database named news_system

Import database.sql

Start the Docker container

Visit:


http://localhost:8080/auth/login.php
Log in and test:

Add news

Edit/delete news

Manage categories

Author

Name: Aseel Omar

GitHub: https://github.com/aseelomar-2004
## Production URL

The project is running in GitHub Codespaces and can be accessed at the following URL:

[Open Project in Browser](https://shiny-system-6974v9w9qxgxf4q7x-8081.app.github.dev/auth/login.php)

> Replace `YOUR_CODESPACE_NAME` with your actual Codespace name or preview link.

---

## Deployment Instructions Using Docker

To deploy this project on any VPS or local machine:

1. **Install Git and Docker** (if not installed):
   ```bash
  apt update
  apt install git docker.io docker-compose -y

2.Clone the repository:

git clone https://github.com/aseelomar-2004/news-system
cd news-system

3.Build and run the Docker containers:

docker compose up -d