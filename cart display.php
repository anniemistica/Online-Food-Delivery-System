<?php
include 'db.php';
session_start();

// Handle remove item request
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $delete_sql = "DELETE FROM cart WHERE id='$remove_id'";
    mysqli_query($conn, $delete_sql);
    header("Location: cart display.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Background Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* Header Styling */
        .header {
            background: radial-gradient(circle, #ff512f, #dd2476);
            color: white;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Cart Container */
        .cart-container {
            width: 70%;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Cart Item Card */
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Product Image */
        .cart-item img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Cart Item Details */
        .cart-item div {
            flex-grow: 1;
            margin-left: 15px;
            text-align: left;
            font-size: 18px;
            font-weight: bold;
        }

        /* Remove Button */
        .remove-btn {
            padding: 8px 12px;
            background: #ff4b5c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .remove-btn:hover {
            background: #ff1e3a;
        }

        /* Total Price */
        .total-price {
            font-size: 22px;
            font-weight: bold;
            color: white;
            margin-top: 20px;
        }

        /* Back Button */
        .back-btn {
            padding: 10px 15px;
            font-size: 18px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #45a049;
        }
        .back-button {
    position: fixed; /* Ensures the button stays on top even when scrolling */
    top: 10px;
    right: 10px;
    background-color: #ff5733;
    color: white;
    padding: 8px 18px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease-in-out, transform 0.2s;
}

.back-button:hover {
    background-color: #d43f00;
    transform: scale(1.05);
}
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">Your Cart</div>

    <div class="cart-container">
        <?php
        $sql = "SELECT * FROM cart";
        $result = mysqli_query($conn, $sql);
        $total_price = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $item_total = $row['product_price'] * $row['quantity'];
            $total_price += $item_total;
            echo "<div class='cart-item'>";
            echo "<img src='" . $row['product_img'] . "'>";
            echo "<div>" . $row['product_name'] . "<br>₹" . $row['product_price'] . " x " . $row['quantity'] . "</div>";
            echo "<div>₹" . $item_total . "</div>";
            echo "<a href='cart display.php?remove=" . $row['id'] . "'><button class='remove-btn'>Remove</button></a>";
            echo "</div>";
        }

        if ($total_price == 0) {
            echo "<p class='total-price'>Your cart is empty.</p>";
        } else {
            echo "<p class='total-price'>Total Price: ₹" . $total_price . "</p>";
        }
        ?>
    </div>

    <a href="log.html"><button class="back-btn">Order Now</button></a>
    <a href="view.php"><button class="back-button">Back to Menu</button></a>


</body>
</html>