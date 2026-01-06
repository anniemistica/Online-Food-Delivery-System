<?php
include 'db.php';
session_start();

// Redirect if not logged in or order not placed
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    header("Location: checkout.php");
    exit();
}

// Fetch user details from session
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
$user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : 'Address not provided';

// Fetch cart items
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

// Extra Charges
$cod_charge = 50;  // COD charge
$grand_total = $total_price + $cod_charge ;

// Confirm order and clear cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    header("Location: view_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - COD</title>
     <link rel="stylesheet" href="cod2.css"> 
    </head>
<body>
    <div class="container">
        <h2>Order Confirmation - Cash on Delivery</h2>
        <div class="container">
    <h1 style="color: #4A90E2; font-size: 26px; margin-bottom: 5px;">Cash on Delivery</h1>
    <h2>Order Confirmation</h2>
        
        <div class="section">
            <h3>Your Items</h3>
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="item">
                        <p><?php echo $item['product_name']; ?> (x<?php echo $item['quantity']; ?>)</p>
                        <p>₹<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
            <p class="total">Subtotal: ₹<?php echo number_format($total_price, 2); ?></p>
            <p class="cod-charge">COD Charge: ₹<?php echo number_format($cod_charge, 2); ?></p>
            <p class="grand-total"><strong>Total: ₹<?php echo number_format($grand_total, 2); ?></strong></p>
        </div>

        <div class="section">
            <h3>Delivery Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_phone); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user_address); ?></p>
        </div>

        <form action="save_order.php" method="POST">
            <button type="submit" class="confirm-btn">Confirm Order</button>
        </form>
    </div>
</body>
</html>