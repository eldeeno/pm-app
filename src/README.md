<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Management App

This is a simple project management application designed to streamline task assignments, track progress, and enhance team collaboration. Built with Laravel, it includes essential features such as project and task management, reporting, notifications, and role-based access control.

## Key Features

### ✅ Project and Task Management
- Create and manage projects.
- Perform full CRUD operations on tasks within a project.
- Assign tasks to users (only Project Managers can assign tasks).

### ✅ Dashboard and Reporting
- View an overview of all projects, including task completion rates.
- Highlight overdue tasks and filter tasks by status.
- Generate PDF reports summarizing project progress.

### ✅ Notifications and Task Dependencies
- Send email notifications for task assignments, upcoming deadlines, and overdue tasks.
- Manage task dependencies to ensure proper workflow execution.

### ✅ Access Control
- Implement Role-Based Access Control (RBAC) with roles such as Admin, Project Manager, and Team Member.
- Restrict project and task actions based on user roles.
- Use middleware to enforce authorization checks.

# Setup Guide

This project is built using **Laravel 11** (backend) and **Vue 3 with Inertia.js** (frontend). It leverages **SQLite** as the database and includes several external packages to enhance functionality.

---

## 🛠️ Tech Stack
- **Backend:** Laravel 11
- **Frontend:** Vue 3 with Inertia.js
- **Frontend:** Inertia.js 2.0.0
- **Database:** SQLite
- **PDF Generation:** `barryvdh/laravel-dompdf`
- **Access Control:** `spatie/laravel-permission`
- **UI Components:** `daisyui`
- **Toasts/Notifications:** `vue-toastification`

---

## 🚀 Project Setup

### 📌 Prerequisites
Ensure you have the following installed:
- PHP 8.2 or later
- Composer
- Node.js & npm

---

1. **Clone the repository**
   ```sh
   git clone <repository_url>
   cd <project_folder>

2. **Install dependencies**
   ```sh
   composer install
   npm install
   
3. **Publish package**
   ```sh
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

4. **Set up environment variables**
   ```sh
   cp .env.example .env
   
5. **Generate application key**
   ```sh
   php artisan key:generate
   
6. **Configure database (SQLite)**
- Create the SQLite database file:
   ```sh
   touch database/database.sqlite
   
7. **Run migrations & seeders**
   ```sh
   php artisan migrate --seed
   
8. **Start the Laravel development server**
   ```sh
   npm run dev
