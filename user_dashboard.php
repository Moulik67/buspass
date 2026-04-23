<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['apply_pass'])) {
    $sql = "INSERT INTO bus_applications (user_id, student_name, student_id, source_stop, destination_stop, route_number, valid_from, valid_to) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['student_name'],
        $_POST['student_id'],
        $_POST['source_stop'],
        $_POST['destination_stop'],
        $_POST['route_number'],
        $_POST['valid_from'],
        $_POST['valid_to']
    ]);
    $success = "Application submitted successfully!";
}

$stmt = $pdo->prepare("SELECT * FROM bus_applications WHERE user_id = ? ORDER BY applied_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
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
            max-width: 1200px;
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
        
        .form-container, .applications-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        button {
            background: #48bb78;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php if(isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="form-container">
                <h2>Apply for Bus Pass</h2>
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
                        <label>Source Stop</label>
                        <input type="text" name="source_stop" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Destination Stop</label>
                        <input type="text" name="destination_stop" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Route Number</label>
                        <input type="text" name="route_number" required>
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
            
            <div class="applications-container">
                <h2>My Applications</h2>
                <?php if(count($applications) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($applications as $app): ?>
                            <tr>
                                <td><?php echo $app['id']; ?></td>
                                <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                                <td><?php echo $app['route_number']; ?></td>
                                <td><span class="status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                                <td><?php echo date('d-m-Y', strtotime($app['applied_date'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No applications found. Apply for a bus pass above!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>