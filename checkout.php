<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];

// Fetch latest user address
$address_query = "SELECT address FROM users WHERE id='$user_id'";
$address_result = mysqli_query($conn, $address_query);
if ($address_result && mysqli_num_rows($address_result) > 0) {
    $row = mysqli_fetch_assoc($address_result);
    $_SESSION['user_address'] = $row['address'];
}
$user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';

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

// Order Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $user_address = mysqli_real_escape_string($conn, $_POST['user_address']);

    // Store values in session
    $_SESSION['user_address'] = $user_address;
    $_SESSION['payment_method'] = $payment_method;

    // Update user address
    $update_address_sql = "UPDATE users SET address='$user_address' WHERE id='$user_id'";
    mysqli_query($conn, $update_address_sql);

    // Insert order with status 'pending'
    $sql = "INSERT INTO orders (customer_name, email, phone, address, payment_method, total_amount, status) 
            VALUES ('$user_name', '$user_email', '$user_phone', '$user_address', '$payment_method', '$total_price', 'pending')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['order_id'] = mysqli_insert_id($conn);

        // Redirect to waiting page until admin confirms
        header("Location: waiting_for_admin.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="codstyle.css">
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        
        <div class="cart-section">
            <h3>Your Cart</h3>
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <p><strong><?php echo $item['product_name']; ?></strong></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p class="price">₹<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-cart">Your cart is empty.</p>
            <?php endif; ?>
        </div>

        <p class="total-price"><strong>Total:</strong> ₹<?php echo number_format($total_price, 2); ?></p>

        <div class="billing-section">
            <h3>Billing Details</h3>
            <p><strong>Name:</strong> <?php echo $user_name; ?></p>
            <p><strong>Email:</strong> <?php echo $user_email; ?></p>
            <p><strong>Phone:</strong> <?php echo $user_phone; ?></p>

            <form method="POST">
                <label for="address"><strong>Address:</strong></label>
                <textarea id="address" name="user_address" rows="3" required><?php echo htmlspecialchars($user_address); ?></textarea>

                <label for="payment"><strong>Payment Method</strong></label>
                <select id="payment" name="payment_method">
                    <option value="COD">Cash on Delivery</option>
                    <option value="Card">Credit/Debit Card</option>
                    <option value="UPI">UPI Payment</option>
                </select>

                <button type="submit" class="place-order-btn">Confirm Payment</button>
            </form>
        </div>
    </div>
</body>
</html>