-- Create database
CREATE DATABASE IF NOT EXISTS bus_pass_system;
USE bus_pass_system;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bus applications table
CREATE TABLE IF NOT EXISTS bus_applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    student_name VARCHAR(100),
    student_id VARCHAR(50),
    source_stop VARCHAR(100),
    destination_stop VARCHAR(100),
    route_number VARCHAR(50),
    pass_type VARCHAR(50) DEFAULT 'Monthly',
    fee DECIMAL(10,2) DEFAULT 500.00,
    payment_status VARCHAR(20) DEFAULT 'pending',
    status VARCHAR(20) DEFAULT 'pending',
    admin_comment TEXT,
    valid_from DATE,
    valid_to DATE,
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT,
    user_id INT,
    amount DECIMAL(10,2),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    receipt_number VARCHAR(100),
    payment_method VARCHAR(50),
    status VARCHAR(20) DEFAULT 'completed',
    FOREIGN KEY (application_id) REFERENCES bus_applications(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert admin account (password: admin123)
INSERT IGNORE INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@buspass.com', 'admin123', 'admin');

-- Insert sample routes data
INSERT IGNORE INTO bus_applications (user_id, student_name, student_id, source_stop, destination_stop, route_number, pass_type, fee, valid_from, valid_to, status) 
SELECT 1, 'Demo Student', 'STU001', 'North Gate', 'University', 'R101', 'Monthly', 500, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 'approved' 
WHERE NOT EXISTS (SELECT 1 FROM bus_applications WHERE student_id='STU001');