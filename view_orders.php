<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user details
$user_email = $_SESSION['user_email'];

// Fetch order details along with order time
$sql = "SELECT id, product_name, quantity, total, status, payment_method, order_time 
        FROM item_fetch 
        WHERE email = '$user_email'";
$result = mysqli_query($conn, $sql);

// Update order statuses dynamically after 1 minute
date_default_timezone_set("Asia/Kolkata");
$current_time = time();

while ($row = mysqli_fetch_assoc($result)) {
    $order_time = strtotime($row['order_time']);
    $time_difference = ($current_time - $order_time) / 60; // Time difference in minutes

    if ($row['status'] === "Pending" && $time_difference >= 1) {
        // Update status to Delivered after 1 minute
        $update_sql = "UPDATE item_fetch SET status = 'Delivered' WHERE id = '{$row['id']}'";
        mysqli_query($conn, $update_sql);
    }
}

// Fetch updated orders
$result = mysqli_query($conn, $sql);
?><!DOCTYPE html><html>
<head>
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            text-align: center;
            position: relative;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #d9edf7;
            transition: 0.3s;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
        .disabled-btn {
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: not-allowed;
            font-weight: bold;
        }
        .message {
            color: #dc3545;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body><a href="logout.php" class="logout-btn">Logout</a>

<h2>My Orders</h2><table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Payment Method</th>
        <th>Status</th>
        <th>Order Time</th>
        <th>Action</th>
    </tr><?php
$refund_messages = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $order_time = strtotime($row['order_time']);
        $time_difference = ($current_time - $order_time) / 60;

        echo "<tr>";
        echo "<td>{$row['product_name']}</td>";
        echo "<td>{$row['quantity']}</td>";
        echo "<td>₹{$row['total']}</td>";
        echo "<td>{$row['payment_method']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['order_time']}</td>";

        if ($row['status'] === "Pending") {
            if ($time_difference < 1) {
                echo "<td><a href='cancel_order.php?id={$row['id']}' class='cancel-btn'>Cancel</a></td>";
            } else {
                echo "<td><button class='disabled-btn' disabled>Cancel</button></td>";
            }
        } else {
            echo "<td><button class='disabled-btn' disabled>Cancel</button></td>";
        }

        echo "</tr>";

        if ($row['status'] === "Cancelled") {
            if ($row['payment_method'] === "COD") {
                $refund_messages[] = "<p class='message'>No cancellation or refund for COD orders.</p>";
            } elseif ($row['payment_method'] === "UPI" || $row['payment_method'] === "Card") {
                $refund_amount = $row['total'] * 0.8;
                $refund_messages[] = "<p class='message'>You will receive a refund of ₹" . number_format($refund_amount, 2) . " for your canceled UPI/Card order.</p>";
            }
        }
    }
    if (!empty($refund_messages)) {
        echo "<tr><td colspan='7'>" . implode("<br>", $refund_messages) . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='7'>No orders found.</td></tr>";
}
?>

</table></body>
</html>