<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - Bus Pass System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        .info-card {
            background: #f7fafc;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s;
        }
        .info-card:hover {
            transform: translateY(-5px);
        }
        .info-icon {
            font-size: 45px;
            margin-bottom: 15px;
        }
        .info-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .info-card p {
            color: #555;
            line-height: 1.6;
        }
        .map-placeholder {
            background: #e2e8f0;
            height: 250px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }
        .back-btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #5a67d8;
        }
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .social-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📞 Contact Us</h1>
        <p class="subtitle">We're here to help! Get in touch with us anytime.</p>

        <div class="contact-grid">
            <div class="info-card">
                <div class="info-icon">🏢</div>
                <h3>Office Address</h3>
                <p>Bus Pass Management Office<br>Main Road, City Center<br>New Delhi - 110001</p>
            </div>
            <div class="info-card">
                <div class="info-icon">📧</div>
                <h3>Email Us</h3>
                <p>support@buspass.com<br>admin@buspass.com<br>info@buspass.com</p>
            </div>
            <div class="info-card">
                <div class="info-icon">📱</div>
                <h3>Call Us</h3>
                <p>+91 98765 43210 (Support)<br>+91 12345 67890 (Admin)<br>+91 55555 55555 (Emergency)</p>
            </div>
            <div class="info-card">
                <div class="info-icon">🕒</div>
                <h3>Working Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 2:00 PM<br>Sunday: Closed</p>
            </div>
        </div>

        <div class="map-placeholder">
            <div>
                <div style="font-size: 48px;">📍</div>
                <p><strong>Our Location</strong><br>Main Road, City Center<br>New Delhi - 110001</p>
            </div>
        </div>

        <div class="social-links">
            <a href="#" class="social-btn">📘 Facebook</a>
            <a href="#" class="social-btn">🐦 Twitter</a>
            <a href="#" class="social-btn">📸 Instagram</a>
            <a href="#" class="social-btn">💼 LinkedIn</a>
        </div>

        <div style="text-align: center;">
            <a href="<?php echo isLoggedIn() ? (isAdmin() ? 'admin_dashboard.php' : 'user_dashboard.php') : 'index.php'; ?>" class="back-btn">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>