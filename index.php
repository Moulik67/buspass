<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        .header h1 { font-size: 48px; margin-bottom: 10px; }
        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .module-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .module-card:hover { transform: translateY(-5px); }
        .module-icon { font-size: 50px; margin-bottom: 15px; }
        .module-card h3 { color: #333; margin-bottom: 10px; }
        .module-card p { color: #666; font-size: 14px; }
        .form-container {
            background: white;
            max-width: 450px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        button:hover { opacity: 0.9; }
        .link { text-align: center; margin-top: 20px; }
        .link a {
            color: #667eea;
            text-decoration: none;
            cursor: pointer;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .nav-links {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .nav-btn {
            display: inline-block;
            background: #48bb78;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
            transition: background 0.3s;
        }
        .nav-btn:hover {
            background: #38a169;
        }
        @media (max-width: 768px) {
            .header h1 { font-size: 32px; }
            .modules { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚌 Smart Bus Pass Management System</h1>
            <p>Easy, Fast & Secure Bus Pass Management</p>
        </div>

        <div class="modules">
            <div class="module-card">
                <div class="module-icon">👤</div>
                <h3>Module 1: User Management</h3>
                <p>Register, Login & Manage Profile</p>
            </div>
            <div class="module-card">
                <div class="module-icon">📝</div>
                <h3>Module 2: Pass Application</h3>
                <p>Apply, Edit & Track Applications</p>
            </div>
            <div class="module-card">
                <div class="module-icon">✅</div>
                <h3>Module 3: Admin Approval</h3>
                <p>Review, Approve & Reject Passes</p>
            </div>
            <div class="module-card">
                <div class="module-icon">💰</div>
                <h3>Module 4: Payment System</h3>
                <p>Pay Fees & Download Receipts</p>
            </div>
            <div class="module-card">
                <div class="module-icon">📊</div>
                <h3>Module 5: Reports</h3>
                <p>View Statistics & Generate Reports</p>
            </div>
            <div class="module-card">
                <div class="module-icon">📢</div>
                <h3>Module 6: Announcements</h3>
                <p>View latest updates & notifications</p>
            </div>
        </div>

        <div class="nav-links">
            <a href="routes.php" class="nav-btn">🚌 View Bus Routes</a>
            <a href="contact.php" class="nav-btn">📞 Contact Us</a>
        </div>

        <div class="form-container">
            <?php if(isset($_GET['msg'])): ?>
                <div class="message success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <div id="loginForm">
                <h2>🔐 Login to Your Account</h2>
                <form method="POST" action="login.php">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
                <div class="link">
                    <a onclick="showRegister()">📝 Don't have an account? Register</a>
                </div>
            </div>

            <div id="registerForm" style="display:none;">
                <h2>📝 Create New Account</h2>
                <form method="POST" action="register.php">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
                <div class="link">
                    <a onclick="showLogin()">🔐 Already have an account? Login</a>
                </div>
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