 <?php
$conn = new mysqli("localhost", "root", "", "annie");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $contact = $_POST['contact'];
    $table_number = $_POST['table_number'];

    $today = date("Y-m-d");

    // Check if the table is already booked for today
    $check_query = "SELECT * FROM reservations WHERE table_number = '$table_number' 
                    AND DATE(reservation_time) = '$today'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error_message = "This table is already booked!";
    }
    else {
        // Insert new reservation as 'pending'
        $insert_query = "INSERT INTO reservations (user_name, contact, table_number, reservation_time, status) 
                         VALUES ('$user_name', '$contact', '$table_number', NOW(), 'pending')";
        $conn->query($insert_query);
        $success_message = "To view your booking status, click the view button!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Booking - Maple Leaf Restaurant</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }/* Header Styles */
    .header {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: white;
        padding: 25px;
        text-align: center;
        position: relative;
        border-bottom: 5px solid gold;
    }

    .header h1 {
        font-size: 45px;
        margin: 0;
        text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.7);
        font-family: 'Georgia', serif;
        letter-spacing: 3px;
        font-weight: bold;
    }

    .header p {
        font-size: 20px;
        font-weight: bold;
        margin-top: 10px;
        color: #ffeb3b;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    }

    /* Special Announcement */
    .announcement {
        font-size: 22px;
        font-weight: bold;
        padding: 15px;
        background: #ff5722;
        color: white;
        border-radius: 8px;
        display: inline-block;
        margin: 20px auto;
        animation: pulse 1.5s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        100% { transform: scale(1.05); }
    }

    /* Container Styles */
    .container {
        width: 50%;
        margin: auto;
        background: white;
        padding: 30px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        margin-top: 30px;
        animation: fadeIn 1.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    h2 {
        color: #333;
        font-size: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    /* Form Styling */
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }
    input, select {
        padding: 12px;
        font-size: 18px;
        border: 2px solid #ddd;
        border-radius: 6px;
        text-align: center;
    }
    .btn {
        background: linear-gradient(to right, #007bff, #0056b3);
        color: white;
        padding: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn:hover {
        background: linear-gradient(to right, #0056b3, #003d7a);
        transform: scale(1.05);
    }
</style>
</head>
<body>
    <div class="header">
    <h1>Reservation</h1>
    <p1>Restaurant Maple Leaf welcomes you to our offline dining experience!</p1>
</div>

<div class="button-container">
    <a href="reservations_display.php" class="btn">Check Your Booked Tables</a>
    <a href="page.html" class="btn">Back to Home Page</a>
    </div>
    <div class="announcement">
    🎉 Grand Opening Special Timings: 5:00 PM - 8:00 PM | Exclusive offers inside! 🍽️
</div>
<div class="container">
    <h2>Book a Table</h2>
    <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
<?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>

<form method="post">
    <input type="text" name="user_name" placeholder="Enter your name" required>
    <input type="text" name="contact" placeholder="Enter your contact number" required>

    <select name="table_number" required>
        <option value="">Select Table</option>
        <?php
        $table_query = "SELECT table_number FROM tables WHERE status='available'";
        $table_result = $conn->query($table_query);

        while ($row = $table_result->fetch_assoc()) {
            echo "<option value='" . $row['table_number'] . "'>Table " . $row['table_number'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" value="Book Table" class="btn">
</form>
</div>
</body>
</html>