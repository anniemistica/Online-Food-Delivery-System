<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Food Delivery</title>
    <link rel="stylesheet" href="logreg.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['user']; ?>!</h2>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>