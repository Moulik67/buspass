<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Pass Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #667eea;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 20px;
        }
        
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background: #5a67d8;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .link {
            text-align: center;
            margin-top: 20px;
        }
        
        .link a {
            color: #667eea;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚌 Bus Pass Management System</h1>
        
        <?php
        if(isset($_GET['msg'])) {
            echo '<div class="message success">' . htmlspecialchars($_GET['msg']) . '</div>';
        }
        if(isset($_GET['error'])) {
            echo '<div class="message error">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        
        <div id="loginForm">
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="link">
                <a onclick="showRegister()">Don't have an account? Register here</a>
            </div>
        </div>
        
        <div id="registerForm" style="display:none;">
            <h2>Register</h2>
            <form method="POST" action="register.php">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
            </form>
            <div class="link">
                <a onclick="showLogin()">Already have an account? Login here</a>
            </div>
        </div>
    </div>
    
    <script>
        function showRegister() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }
        
        function showLogin() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
        }
    </script>
</body>
</html>