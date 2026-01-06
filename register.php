<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
  


    // Check if user already exists
    $check_sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        $message = "You are already registered! Please login.";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
        $insert_result = mysqli_query($conn, $insert_sql);

        if ($insert_result) {
            // Automatically log in the user
            $_SESSION['user_id'] = mysqli_insert_id($conn); // Store user ID in session
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
             $user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : ''; 

            header("Location: checkout.php"); // Redirect to checkout page
            exit();
        } else {
            $message = "Error during registration.";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Food Delivery</title>
    <link rel="stylesheet" href="logreg.css">
</head>
<body>
    <div class="container">
        <form method="POST">
            <h2>Register</h2>
            <?php if ($message != "") { echo "<p class='message'>$message</p>"; } ?>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required >
            <input type="text" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" title="Phone number must be in 10 digits" maxlength="10">
            <button type="submit" class="btn">Register</button>
            <p class="switch">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>