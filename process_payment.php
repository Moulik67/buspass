<?php
require_once 'config.php';

if(!isLoggedIn() || isset($_POST['make_payment'])) {
    $application_id = $_POST['application_id'];
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];
    
    // Get application details
    $stmt = $pdo->prepare("SELECT * FROM bus_applications WHERE id = ? AND user_id = ?");
    $stmt->execute([$application_id, $user_id]);
    $app = $stmt->fetch();
    
    if($app) {
        // Generate receipt number
        $receipt_number = "RCP" . time() . rand(100, 999);
        
        // Insert payment
        $sql = "INSERT INTO payments (application_id, user_id, amount, receipt_number, payment_method) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$application_id, $user_id, $app['fee'], $receipt_number, $payment_method]);
        
        // Update application payment status
        $stmt = $pdo->prepare("UPDATE bus_applications SET payment_status = 'completed' WHERE id = ?");
        $stmt->execute([$application_id]);
        
        header("Location: user_dashboard.php?msg=Payment successful! Receipt #$receipt_number");
    } else {
        header("Location: user_dashboard.php?error=Payment failed!");
    }
}
?>