<?php
include 'db.php';
session_start();

// Check if order ID is provided
if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$order_id = $_GET['id'];
$user_email = $_SESSION['user_email'];

// Fetch order details
$sql = "SELECT total, payment_method, status FROM item_fetch WHERE id='$order_id' AND email='$user_email'";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows( $result) == 0) {
    die("Order not found.");
}

$row = mysqli_fetch_assoc($result);
$total_price = $row['total'];
$payment_method = $row['payment_method'];
$status = $row['status'];

// If already cancelled or delivered, prevent further action
if ($status === "Cancelled" || $status === "Delivered") {
    die("This order cannot be canceled.");
}

// Prevent cancellation for COD orders
if ($payment_method === "COD") {
    echo "<script>alert('No cancellation or refund for COD orders.'); window.location.href = 'view_orders.php';</script>";
    exit(); // Stop further execution
}

// Update status to "Cancelled" for other payment methods
$update_sql = "UPDATE item_fetch SET status = 'Cancelled' WHERE id='$order_id'";
if (!mysqli_query($conn, $update_sql)) {
    die("Error updating order status.");
}

// Refund handling for UPI and Card payments
if ($payment_method === "UPI" || $payment_method === "Card") {
    $refund_amount = $total_price * 0.8;
    echo "<script>alert('Your order has been cancelled. ₹" . number_format($refund_amount, 2) . " will be refunded.'); window.location.href = 'view_orders.php';</script>";
} else {
    echo "<script>alert('Order cancelled successfully.'); window.location.href = 'view_orders.php';</script>";
}

?>