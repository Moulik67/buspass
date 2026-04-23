<?php
require_once 'config.php';

if(isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if(empty($name) || empty($email) || empty($password)) {
        header("Location: index.php?error=All fields are required!");
        exit();
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=Invalid email format!");
        exit();
    }
    
    if(strlen($password) < 4) {
        header("Location: index.php?error=Password must be at least 4 characters!");
        exit();
    }
    
    // Check if email already exists
    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if($check->rowCount() > 0) {
        header("Location: index.php?error=Email already registered! Please login.");
        exit();
    }
    
    // Insert user
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$name, $email, $password])) {
        header("Location: index.php?msg=Registration successful! Please login.");
    } else {
        header("Location: index.php?error=Registration failed! Please try again.");
    }
}
?>