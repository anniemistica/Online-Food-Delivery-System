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
$user_address = $_SESSION['user_address'] ?? '';

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

// Process payment
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upi_id = mysqli_real_escape_string($conn, $_POST['upi_id']);

    if (empty($upi_id)) {
        $error_message = "UPI ID is required.";
    } 
    else if (!preg_match("/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/", $upi_id)) {
        $error_message = "Invalid UPI ID format. Please enter a valid one (e.g., user@upi).";
    } 
    else {
        // Insert order details into database
        $sql = "INSERT INTO orders (customer_name, email, phone, address, payment_method, total_amount) 
                VALUES ('$user_name', '$user_email', '$user_phone', '$user_address', 'UPI', '$total_price')";
        
        if (mysqli_query($conn, $sql)) {
            // Clear the cart after successful order
            mysqli_query($conn, "DELETE FROM cart WHERE 1");

            // Redirect to success page
            header("Location: view_orders.php");
            exit();
        } else {
            $error_message = "Error processing order: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UPI Payment</title>
    <link rel="stylesheet" href="upi.css">
</head>
<body>
    <div class="container">
        <h2>UPI Payment</h2>
        <div class="order-details">
            <p><strong>Name:</strong> <?php echo $user_name; ?></p>
            <p><strong>Email:</strong> <?php echo $user_email; ?></p>
            <p><strong>Phone:</strong> <?php echo $user_phone; ?></p>
            <p><strong>Address:</strong> <?php echo nl2br($user_address); ?></p>
        </div>

        <h3>Cart Items</h3>
        <div class="cart-section">
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

        <p class="total-amount"><strong>Total Amount:</strong> ₹<?php echo number_format($total_price, 2); ?></p>

        <form action="" method="POST">
            <label>Enter UPI ID:</label>
            <input type="text" name="upi_id" required value="<?php echo isset($_POST['upi_id']) ? htmlspecialchars($_POST['upi_id']) : ''; ?>">
            
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <button type="submit" class="confirm-btn">Confirm Order</button>
        </form>
    </div>
</body>
</html>