<?php
require_once 'config.php';

if(!isAdmin()) {
    header("Location: index.php");
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bus_pass_report_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Report Generated: ' . date('Y-m-d H:i:s')]);
fputcsv($output, []);
fputcsv($output, ['BUS PASS SYSTEM - COMPLETE REPORT']);
fputcsv($output, []);

fputcsv($output, ['BUS PASS APPLICATIONS']);
fputcsv($output, ['ID', 'Student Name', 'Email', 'Route', 'Pass Type', 'Fee', 'Status', 'Payment Status', 'Applied Date']);

$stmt = $pdo->query("SELECT a.*, u.email FROM bus_applications a JOIN users u ON a.user_id = u.id ORDER BY a.applied_date DESC");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['id'], $row['student_name'], $row['email'], $row['route_number'], $row['pass_type'], $row['fee'], $row['status'], $row['payment_status'], $row['applied_date']]);
}

fputcsv($output, []);
fputcsv($output, ['FINANCIAL REPORT']);
fputcsv($output, ['Total Revenue', 'Total Payments']);

$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn();
$total_payments = $pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();
fputcsv($output, [$total_revenue, $total_payments]);

fclose($output);
exit();
?>