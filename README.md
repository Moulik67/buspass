# Bus Pass Management System

A complete web-based Bus Pass Management System with 5 working modules.

## Features

### Module 1: User Management
- User registration and login
- Session management
- Role-based access (User/Admin)

### Module 2: Bus Pass Application
- Apply for new bus pass
- View application status
- Track application history

### Module 3: Admin Approval System
- View all applications
- Approve or reject passes
- Admin dashboard with statistics

### Module 4: Payment System
- Pay for approved passes
- Generate payment receipts
- Payment history tracking

### Module 5: Reports Module
- View system statistics
- Export reports to CSV
- Revenue tracking

## Technologies Used

- PHP (Backend)
- MySQL (Database)
- HTML5 & CSS3 (Frontend)
- JavaScript (Interactivity)

## Installation Guide

### Prerequisites
- XAMPP (or any PHP/MySQL server)

### Steps to Install

1. **Start XAMPP**
   - Start Apache and MySQL services

2. **Copy Files**
   - Copy this entire folder to: `C:\xampp\htdocs\bus-pass-system`

3. **Database Setup**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `bus_pass_system`
   - Import the database from `database.sql` file

4. **Configure Database**
   - Open `config.php`
   - Update database credentials if needed
   - Default: username `root`, password `""` (empty)

5. **Run the Application**
   - Open browser
   - Go to: `http://localhost/bus-pass-system`

## Default Admin Login
