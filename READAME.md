# News Management System

A simple web-based News Management System built with PHP and MySQL.  
The system allows authenticated users to manage news articles by creating categories, adding news with images, editing existing news, and performing soft deletes.  
The project is fully containerized using Docker to ensure a consistent environment and easy setup on any machine.

---

## Tech Stack

- **Backend:** PHP 8.2  
- **Web Server:** Apache  
- **Database:** MySQL  
- **Frontend:** HTML, CSS, Bootstrap  
- **Containerization:** Docker  
- **Version Control:** Git & GitHub  

---

## Project Structure

```text
news-system/
├── src/
│   ├── app/            # Application pages (news, categories, dashboard)
│   ├── auth/           # Authentication (login, logout)
│   ├── config/         # Database configuration
│   ├── uploads/        # Uploaded images
│   └── index.php       # Application entry point
├── database.sql        # Database schema
├── Dockerfile          # Docker build instructions
├── .dockerignore       # Docker ignore rules
├── .gitignore          # Git ignore rules
├── README.md
└── docs/
    ├── screenshots/    # Assignment screenshots
    └── notes.md        # Technical notes
How to Build and Run Using Docker
Prerequisites

Docker Desktop installed and running

MySQL running on the host machine (e.g., XAMPP)

Step 1: Build the Docker Image

From the project root directory, run:

docker build -t news-system-app .

Step 2: Run the Docker Container
docker run -d -p 8080:80 --name news-system-container news-system-app

Step 3: Access the Application

Open your browser and go to:

http://localhost:8080

How to Stop and Clean Up

To stop the container:

docker stop news-system-container


To remove the container:

docker rm news-system-container


(Optional) To remove the Docker image:

docker rmi news-system-app

Configuration Notes

Application Port:

Host: 8080

Container: 80

Database Configuration:
The application connects to a MySQL database running on the host machine.

Host: host.docker.internal
Database: news_system
Username: root
Password: (empty)


Database configuration file:

src/config/config.php

Database Setup

Ensure MySQL is running on your machine.

Create a database named:

news_system


Import the database schema:

SOURCE database.sql;

How to Test the Application

Open the application:

http://localhost:8080


Log in with a valid user account.

Test the following features:

Add a category

Add a news article (with image upload)

Edit an existing article

Delete a news article (soft delete)

View deleted news

Screenshots of the running application and Docker containers are available in:

docs/screenshots/

Attribution

This project was developed as part of an academic assignment.
No external open-source project was used as a base.

Notes

Docker is used to ensure the project runs consistently on any machine.

In a real production environment, environment variables should be used instead of hardcoded database credentials.