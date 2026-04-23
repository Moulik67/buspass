<?php
require_once 'config.php';

if(!isAdmin()) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['update_status'])) {
    $sql = "UPDATE bus_applications SET status = ?, admin_comment = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['status'], $_POST['admin_comment'], $_POST['application_id']]);
    $success = "Application status updated!";
}

$applications = $pdo->query("SELECT a.*, u.name, u.email FROM bus_applications a 
                             JOIN users u ON a.user_id = u.id 
                             ORDER BY a.applied_date DESC")->fetchAll();

$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn();
$total_applications = count($applications);
$pending = count(array_filter($applications, function($a) { return $a['status'] == 'pending'; }));
$approved = count(array_filter($applications, function($a) { return $a['status'] == 'approved'; }));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Bus Pass System</title>
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
            flex-wrap: wrap;
        }
        .nav-btn {
            background: #48bb78;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            margin-right: 10px;
            display: inline-block;
        }
        .logout-btn {
            background: #f56565;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
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
        select, textarea, button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>👑 Admin Dashboard</h1>
                <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>
            </div>
            <div>
                <a href="announcements.php" class="nav-btn">📢 Announcements</a>
                <a href="routes.php" class="nav-btn">🚌 Routes</a>
                <a href="contact.php" class="nav-btn">📞 Contact</a>
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_applications; ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending; ?></div>
                <div class="stat-label">Pending Approvals</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $approved; ?></div>
                <div class="stat-label">Approved Passes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">₹<?php echo number_format($total_revenue, 2); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>

        <?php if(isset($success)): ?>
            <div class="success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>✅ Approve/Reject Bus Pass Applications</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Student Name</th>
                            <th>Route</th>
                            <th>Pass Type</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($applications as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo htmlspecialchars($app['name']); ?></td>
                            <td><?php echo $app['email']; ?></td>
                            <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                            <td><?php echo $app['route_number']; ?></td>
                            <td><?php echo $app['pass_type']; ?></td>
                            <td>₹<?php echo $app['fee']; ?></td>
                            <td><span class="status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                            <td><?php echo ucfirst($app['payment_status']); ?></td>
                            <td>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                    <select name="status">
                                        <option value="pending" <?php echo $app['status']=='pending'?'selected':''; ?>>Pending</option>
                                        <option value="approved" <?php echo $app['status']=='approved'?'selected':''; ?>>Approve</option>
                                        <option value="rejected" <?php echo $app['status']=='rejected'?'selected':''; ?>>Reject</option>
                                    </select>
                                    <textarea name="admin_comment" placeholder="Add comment..." style="width:150px; margin-top:5px;"><?php echo $app['admin_comment']; ?></textarea>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>