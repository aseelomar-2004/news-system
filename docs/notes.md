# Technical Notes – News Management System

## 1. Biggest Docker Problem and Solution

### Problem:
The biggest issue I faced was database connectivity when running the PHP application inside Docker.
Initially, the application was trying to connect to MySQL using `localhost`, which caused a
"Connection refused" error because `localhost` inside a Docker container refers to the container itself,
not the host machine.

### Solution:
This allows the Docker container to access the MySQL service running on the host machine (XAMPP).
After updating the database configuration and rebuilding the Docker image, the application connected
successfully to the database.

---

## 2. Most Important Git/GitHub Lesson Learned

The most important lesson I learned is the importance of meaningful and structured commit messages.
Instead of making one large commit or using vague messages, I learned to:

- Commit small, logical changes
- Use clear commit messages that describe *what* and *why* something was changed
- Separate concerns (Docker, documentation, fixes, structure)

This makes the project easier to understand, review, and maintain, especially when working in teams
or submitting professional assignments.

