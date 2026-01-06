<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: checkout.php");
    exit();
}

// Fetch user details from session
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
$user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : 'Address not provided';

// Fetch cart items and calculate total
$total_price = 0;
$cart_items = [];
$sql = "SELECT product_name, product_price, quantity FROM cart";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total_price += $row['product_price'] * $row['quantity'];
    }
}


$grand_total = $total_price; // This is the final amount to save

// Save order details when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($cart_items as $item) {
        $product_name = $item['product_name'];
        $quantity = $item['quantity'];
        $product_price = $item['product_price'];

        // Insert into item_fetch table with grand_total
        $insertQuery = "INSERT INTO item_fetch (customer_name, email, phone, address, total, product_name, quantity, product_price) 
                        VALUES ('$user_name', '$user_email', '$user_phone', '$user_address', '$grand_total', '$product_name', '$quantity', '$product_price')";
        mysqli_query($conn, $insertQuery);
    }

    // Clear cart after order is placed
    mysqli_query($conn, "DELETE FROM cart");

    echo "<script>alert('Order placed successfully!'); window.location.href='view_orders.php';</script>";
    exit();
}
?>