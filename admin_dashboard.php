<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if(isset($_POST['update_status'])) {
    $sql = "UPDATE bus_applications SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['status'], $_POST['application_id']]);
    $success = "Application status updated!";
}

$applications = $pdo->query("SELECT a.*, u.name, u.email FROM bus_applications a 
                             JOIN users u ON a.user_id = u.id 
                             ORDER BY a.applied_date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
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
            border-radius: 5px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        
        .applications-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
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
        
        select, button {
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        button {
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .status-pending {
            background: #fed7d7;
            color: #c53030;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        
        .status-approved {
            background: #c6f6d5;
            color: #22543d;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        
        .status-rejected {
            background: #fed7d7;
            color: #742a2a;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php
        $total = count($applications);
        $pending = count(array_filter($applications, function($a) { return $a['status'] == 'pending'; }));
        $approved = count(array_filter($applications, function($a) { return $a['status'] == 'approved'; }));
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total; ?></div>
                <div>Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending; ?></div>
                <div>Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $approved; ?></div>
                <div>Approved</div>
            </div>
        </div>
        
        <?php if(isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="applications-container">
            <h2>All Applications</h2>
            <?php if(count($applications) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Student Name</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($applications as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo htmlspecialchars($app['name']); ?></td>
                            <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                            <td><?php echo $app['route_number']; ?></td>
                            <td><span class="status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                    <select name="status">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>