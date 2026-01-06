<?php
include 'db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch session data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
$user_address = $_SESSION['user_address'];

// Fetch payment method from session (added this line)
$payment_method = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : 'Unknown';

// Fetch cart items
$sql = "SELECT product_name, product_price, quantity FROM cart";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($item = mysqli_fetch_assoc($result)) {
        $product_name = $item['product_name'];
        $quantity = $item['quantity'];
        $product_price = $item['product_price'];
        $total_price = $product_price * $quantity;

        // Insert order details into item_fetch, now including payment_method
        $insertQuery = "INSERT INTO item_fetch (customer_name, email, phone, address, product_name, quantity, product_price, total, payment_method) 
                        VALUES ('$user_name', '$user_email', '$user_phone', '$user_address', '$product_name', '$quantity', '$product_price', '$total_price', '$payment_method')";
        mysqli_query($conn, $insertQuery);
    }

    // Clear cart after saving order
    mysqli_query($conn, "DELETE FROM cart");

    // Redirect to view orders page
    header("Location: view_orders.php");
    exit();
} else {
    echo "Error: No items in cart.";
}
?>