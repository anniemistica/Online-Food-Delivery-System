<?php
include 'db.php';
session_start();

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update order status in the database
    $update_sql = "UPDATE orders SET status='$status' WHERE id='$order_id'";
    mysqli_query($conn, $update_sql);

    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Orders</title>
</head>
<body>
    <h2>Order Management</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
            <td><?php echo $row['payment_method']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <?php if ($row['status'] == 'pending') : ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="status" value="confirmed">Confirm</button>
                        <button type="submit" name="status" value="rejected">Reject</button>
                    </form>
                <?php else : ?>
                    <?php echo ucfirst($row['status']); ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>