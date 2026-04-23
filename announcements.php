<?php
require_once 'config.php';

// Only admin can post announcements
if(!isAdmin()) {
    header("Location: index.php");
    exit();
}

// Handle new announcement
if(isset($_POST['post_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $posted_by = $_SESSION['user_name'];
    
    $sql = "INSERT INTO announcements (title, message, posted_by) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $message, $posted_by]);
    $success = "Announcement posted successfully!";
}

// Handle delete announcement
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = "Announcement deleted!";
}

// Get all announcements
$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Announcements - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
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
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        button {
            background: #48bb78;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .announcement-item {
            background: #f7fafc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .announcement-meta {
            font-size: 12px;
            color: #888;
            margin: 5px 0;
        }
        .delete-btn {
            background: #f56565;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }
        .back-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Announcements Manager</h1>
            <a href="admin_dashboard.php" class="back-btn" style="background:white; color:#667eea;">← Back to Dashboard</a>
        </div>

        <?php if(isset($success)): ?>
            <div class="success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>📝 Post New Announcement</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Announcement Title</label>
                    <input type="text" name="title" placeholder="Enter title..." required>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="4" placeholder="Enter announcement details..." required></textarea>
                </div>
                <button type="submit" name="post_announcement">📢 Post Announcement</button>
            </form>
        </div>

        <div class="card">
            <h2>📋 All Announcements</h2>
            <?php if(count($announcements) > 0): ?>
                <?php foreach($announcements as $ann): ?>
                    <div class="announcement-item">
                        <div class="announcement-title"><?php echo htmlspecialchars($ann['title']); ?></div>
                        <div class="announcement-meta">
                            Posted by: <?php echo $ann['posted_by']; ?> | 
                            Date: <?php echo date('d-m-Y H:i', strtotime($ann['created_at'])); ?>
                        </div>
                        <div style="margin: 10px 0;"><?php echo nl2br(htmlspecialchars($ann['message'])); ?></div>
                        <a href="?delete=<?php echo $ann['id']; ?>" class="delete-btn" onclick="return confirm('Delete this announcement?')">Delete</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No announcements yet. Post your first announcement!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>