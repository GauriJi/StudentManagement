# Student Management System

A comprehensive School Management System built with Laravel. This project is now maintained by **GauriJi**.

## Setup Instructions for Collaborators

Since sensitive files and dependencies are ignored in this repository, please follow these steps to set up the project locally:

### 1. Clone the repository
```bash
git clone https://github.com/GauriJi/StudentManagement.git
cd StudentManagement
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
*Note: Open the `.env` file and update your local database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Database Setup
Create a database in your local environment (e.g., MySQL) that matches your `.env` configuration, then run:
```bash
php artisan migrate
```

### 6. Run the Application
```bash
php artisan serve
```
The application will be accessible at `http://localhost:8000`.

---
*Created and maintained by GauriJi.*
