<?php
require_once 'config.php';

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if($check->rowCount() > 0) {
        header("Location: index.php?error=Email already exists!");
        exit();
    }
    
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$name, $email, $password])) {
        header("Location: index.php?msg=Registration successful! Please login.");
    } else {
        header("Location: index.php?error=Registration failed!");
    }
}
?>