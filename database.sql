-- Create announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200),
    message TEXT,
    posted_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample announcements
INSERT INTO announcements (title, message, posted_by) VALUES
('Welcome to Bus Pass System', 'Thank you for using our bus pass management system. Please register and apply for passes.', 'Admin'),
('New Routes Added', 'We have added 2 new bus routes - R106 and R107. Check routes page for details.', 'Admin'),
('Payment Update', 'Now you can pay using UPI, Credit Card, and Net Banking.', 'Admin');