<?php
include 'db.php';
session_start();

$order_id = $_SESSION['order_id'];
$start_time = time();
$max_wait_time = 60; // Maximum wait time in seconds

while (true) {
    // Stop checking after max wait time
    if ((time() - $start_time) > $max_wait_time) {
        echo "<h2>Order status update taking too long. Please reload again.</h2>";
        exit();
    }

    // Fetch order status
    $result = mysqli_query($conn, "SELECT status FROM orders WHERE id='$order_id'");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if ($row['status'] == 'confirmed') {
            if ($_SESSION['payment_method'] == "COD") {
                header("Location: cod.php");
            } elseif ($_SESSION['payment_method'] == "Card") {
                header("Location: card.php");
            } elseif ($_SESSION['payment_method'] == "UPI") {
                header("Location: upi.php");
            }
            exit();
        } elseif ($row['status'] == 'rejected') {
            echo "<h2>Sorry, your order was rejected by the admin.</h2>
            <a href='cart display.php'>Go to cart Page</a>";
            exit();
        }
    }

    sleep(2); // Check every 2 seconds
}
?>