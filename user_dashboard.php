<?php
require_once 'config.php';

if(!isLoggedIn() || isAdmin()) {
    header("Location: index.php");
    exit();
}

// Handle new application
if(isset($_POST['apply_pass'])) {
    $sql = "INSERT INTO bus_applications (user_id, student_name, student_id, source_stop, destination_stop, route_number, pass_type, fee, valid_from, valid_to) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['student_name'],
        $_POST['student_id'],
        $_POST['source_stop'],
        $_POST['destination_stop'],
        $_POST['route_number'],
        $_POST['pass_type'],
        $_POST['fee'],
        $_POST['valid_from'],
        $_POST['valid_to']
    ]);
    $success = "Application submitted successfully! Wait for admin approval.";
}

// Handle edit application
if(isset($_POST['edit_application'])) {
    $sql = "UPDATE bus_applications SET student_name=?, student_id=?, source_stop=?, destination_stop=?, route_number=?, pass_type=?, valid_from=?, valid_to=? WHERE id=? AND user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['student_name'], $_POST['student_id'], $_POST['source_stop'], 
        $_POST['destination_stop'], $_POST['route_number'], $_POST['pass_type'],
        $_POST['valid_from'], $_POST['valid_to'], $_POST['app_id'], $_SESSION['user_id']
    ]);
    $success = "Application updated successfully!";
}

// Handle delete application
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM bus_applications WHERE id=? AND user_id=? AND status='pending'");
    $stmt->execute([$_GET['delete'], $_SESSION['user_id']]);
    $success = "Application deleted successfully!";
}

// Get user's applications
$stmt = $pdo->prepare("SELECT * FROM bus_applications WHERE user_id = ? ORDER BY applied_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();

// Get payment history
$stmt = $pdo->prepare("SELECT p.*, a.route_number FROM payments p JOIN bus_applications a ON p.application_id = a.id WHERE p.user_id = ? ORDER BY payment_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$payments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Bus Pass System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container { max-width: 1400px; margin: 0 auto; }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: #f56565;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card h2 {
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .form-group { margin-bottom: 15px; }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        .status-pending { background: #fed7d7; color: #c53030; padding: 5px 10px; border-radius: 5px; display: inline-block; }
        .status-approved { background: #c6f6d5; color: #22543d; padding: 5px 10px; border-radius: 5px; display: inline-block; }
        .status-rejected { background: #fed7d7; color: #742a2a; padding: 5px 10px; border-radius: 5px; display: inline-block; }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
        }
        .btn-danger { background: #f56565; }
        .btn-warning { background: #ed8936; }
        .receipt {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-family: monospace;
        }
        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>🎫 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <p>Email: <?php echo $_SESSION['user_email']; ?></p>
            </div>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>

    <!-- Quick Help Section -->
<div style="background: #e8f4f8; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; border-left: 4px solid #667eea;">
    💡 Need help? Contact support: <strong>support@buspass.com</strong> | Call: <strong>+91 98765 43210</strong>
</div>

        <?php if(isset($success)): ?>
            <div class="success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <div class="grid-2">
            <!-- Module 2: Apply for Pass -->
            <div class="card">
                <h2>📝 Module 2: Apply for Bus Pass</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="student_name" required>
                    </div>
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" name="student_id" required>
                    </div>
                    <div class="form-group">
                        <label>Route Number</label>
                        <select name="route_number" required>
                            <option value="">Select Route</option>
                            <option value="R101">R101 - North Gate to University (₹500)</option>
                            <option value="R102">R102 - South Gate to College (₹450)</option>
                            <option value="R103">R103 - East Stop to Campus (₹550)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Source Stop</label>
                        <input type="text" name="source_stop" required>
                    </div>
                    <div class="form-group">
                        <label>Destination Stop</label>
                        <input type="text" name="destination_stop" required>
                    </div>
                    <div class="form-group">
                        <label>Pass Type</label>
                        <select name="pass_type" required>
                            <option value="Monthly">Monthly (₹500)</option>
                            <option value="Quarterly">Quarterly (₹1350 - 10% off)</option>
                            <option value="Yearly">Yearly (₹5000 - 15% off)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fee Amount</label>
                        <input type="number" name="fee" value="500" readonly>
                    </div>
                    <div class="form-group">
                        <label>Valid From</label>
                        <input type="date" name="valid_from" required>
                    </div>
                    <div class="form-group">
                        <label>Valid To</label>
                        <input type="date" name="valid_to" required>
                    </div>
                    <button type="submit" name="apply_pass">Submit Application</button>
                </form>
            </div>

            <!-- Module 4: Payment Section -->
            <div class="card">
                <h2>💰 Module 4: Make Payment</h2>
                <?php
                $pending_payments = array_filter($applications, function($app) {
                    return $app['status'] == 'approved' && $app['payment_status'] == 'pending';
                });
                if(count($pending_payments) > 0): ?>
                    <form method="POST" action="process_payment.php">
                        <div class="form-group">
                            <label>Select Approved Pass</label>
                            <select name="application_id" required>
                                <?php foreach($pending_payments as $app): ?>
                                    <option value="<?php echo $app['id']; ?>">
                                        Route <?php echo $app['route_number']; ?> - ₹<?php echo $app['fee']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="UPI">UPI (Google Pay/PhonePe)</option>
                                <option value="Net Banking">Net Banking</option>
                            </select>
                        </div>
                        <button type="submit" name="make_payment">Pay Now</button>
                    </form>
                <?php else: ?>
                    <p>No pending payments. Wait for admin approval first.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Module 2: My Applications -->
        <div class="card" style="margin-bottom: 20px;">
            <h2>📋 My Bus Pass Applications</h2>
            <?php if(count($applications) > 0): ?>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Student Name</th><th>Route</th><th>Pass Type</th><th>Fee</th><th>Status</th><th>Payment</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($applications as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                            <td><?php echo $app['route_number']; ?></td>
                            <td><?php echo $app['pass_type']; ?></td>
                            <td>₹<?php echo $app['fee']; ?></td>
                            <td><span class="status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                            <td><?php echo ucfirst($app['payment_status']); ?></td>
                            <td>
                                <?php if($app['status'] == 'pending'): ?>
                                    <a href="?delete=<?php echo $app['id']; ?>" onclick="return confirm('Delete this application?')" class="btn-small btn-danger" style="background:#f56565; color:white; padding:5px 10px; text-decoration:none; border-radius:5px;">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications found. Apply for a bus pass above!</p>
            <?php endif; ?>
        </div>

        <!-- Module 4: Payment History -->
        <div class="card">
            <h2>📄 Payment History & Receipts</h2>
            <?php if(count($payments) > 0): ?>
                <table>
                    <thead>
                        <tr><th>Receipt No</th><th>Route</th><th>Amount</th><th>Payment Method</th><th>Date</th><th>Receipt</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($payments as $payment): ?>
                        <tr>
                            <td>#<?php echo $payment['receipt_number']; ?></td>
                            <td><?php echo $payment['route_number']; ?></td>
                            <td>₹<?php echo $payment['amount']; ?></td>
                            <td><?php echo $payment['payment_method']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($payment['payment_date'])); ?></td>
                            <td><a href="receipt.php?id=<?php echo $payment['id']; ?>" target="_blank" style="background:#48bb78; color:white; padding:5px 10px; text-decoration:none; border-radius:5px;">Download Receipt</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No payment history found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>