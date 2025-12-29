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

yaml
نسخ الكود

---

## 🐳 How to Build and Run with Docker

### 1️⃣ Prerequisites
Make sure you have:
- Docker Desktop installed and running
- Git installed
- MySQL running locally (XAMPP)

---

### 2️⃣ Clone the Repository
```bash
git clone https://github.com/aseelomar-2004/news-management.git
cd news-management
3️⃣ Build the Docker Image
bash
نسخ الكود
docker build -t news-system-app .
⏳ The first build may take several minutes because the PHP base image is downloaded.

4️⃣ Run the Container
bash
نسخ الكود
docker run -d -p 8080:80 --name news-system-container news-system-app
5️⃣ Open the Application
Open your browser and go to:

arduino
نسخ الكود
http://localhost:8080
🛑 Stop and Clean Up
Stop the container
bash
نسخ الكود
docker stop news-system-container
Remove the container
bash
نسخ الكود
docker rm news-system-container
(Optional) Remove the image
bash
نسخ الكود
docker rmi news-system-app
⚙️ Configuration Notes
Apache Port: 8080 → mapped to container port 80

Database Host: host.docker.internal

Database Name: news_system

Database User: root

Database Password: empty (default XAMPP setup)

Database configuration can be found in:

arduino
نسخ الكود
src/config/config.php
🧪 How to Test the Project
Make sure MySQL is running in XAMPP

Import the database:

Open phpMyAdmin

Create a database named news_system

Import database.sql

Start the Docker container

Visit:

bash
نسخ الكود
http://localhost:8080/auth/login.php
Log in and test:

Add news

Edit/delete news

Manage categories

Author
Name: Aseel Omar
GitHub: https://github.com/aseelomar-2004