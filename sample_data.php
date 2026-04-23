<?php
require_once 'config.php';

// Add sample announcement
$stmt = $pdo->prepare("INSERT INTO announcements (title, message, posted_by) VALUES (?, ?, ?)");
$stmt->execute(["New Feature Alert", "We've added live bus tracking! Check your dashboard for updates.", "System"]);

echo "Sample announcement added!";
?>