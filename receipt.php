<?php
require_once 'config.php';

if(!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$payment_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, a.student_name, a.route_number, a.source_stop, a.destination_stop, a.valid_from, a.valid_to 
                       FROM payments p 
                       JOIN bus_applications a ON p.application_id = a.id 
                       WHERE p.id = ? AND p.user_id = ?");
$stmt->execute([$payment_id, $_SESSION['user_id']]);
$payment = $stmt->fetch();

if(!$payment) {
    die("Receipt not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 50px;
            background: #f0f2f5;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 2px solid #333;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .details {
            margin: 20px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
        }
        .total {
            border-top: 2px solid #333;
            margin-top: 20px;
            padding-top: 20px;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #333;
            font-size: 12px;
        }
        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>BUS PASS SYSTEM</h1>
            <p>Official Payment Receipt</p>
            <p><strong>Receipt No:</strong> <?php echo $payment['receipt_number']; ?></p>
            <p><strong>Date:</strong> <?php echo date('d-m-Y H:i:s', strtotime($payment['payment_date'])); ?></p>
        </div>
        
        <div class="details">
            <div class="row"><strong>Student Name:</strong> <span><?php echo $payment['student_name']; ?></span></div>
            <div class="row"><strong>Route Number:</strong> <span><?php echo $payment['route_number']; ?></span></div>
            <div class="row"><strong>Source Stop:</strong> <span><?php echo $payment['source_stop']; ?></span></div>
            <div class="row"><strong>Destination Stop:</strong> <span><?php echo $payment['destination_stop']; ?></span></div>
            <div class="row"><strong>Valid From:</strong> <span><?php echo $payment['valid_from']; ?></span></div>
            <div class="row"><strong>Valid To:</strong> <span><?php echo $payment['valid_to']; ?></span></div>
            <div class="row"><strong>Payment Method:</strong> <span><?php echo $payment['payment_method']; ?></span></div>
            <div class="row total"><strong>Amount Paid:</strong> <span>₹<?php echo $payment['amount']; ?></span></div>
        </div>
        
        <div class="footer">
            <p>Thank you for using Bus Pass System!</p>
            <p>This is a computer generated receipt - No signature required</p>
        </div>
    </div>
    <button onclick="window.print()">🖨️ Print Receipt</button>
    <button onclick="window.close()">Close</button>
</body>
</html>