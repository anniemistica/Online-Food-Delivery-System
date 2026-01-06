<?php
$conn = new mysqli("localhost", "root", "", "annie"); // Database connection

// Get user details (Modify if using sessions or a login system)
$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : '';
$contact = isset($_GET['contact']) ? $_GET['contact'] : '';

if ($user_name && $contact) {
    $query = "SELECT table_number, status FROM reservations 
              WHERE user_name = '$user_name' 
              AND contact = '$contact' 
              ORDER BY reservation_time DESC LIMIT 1";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $table_number = $row['table_number'];
        $status = $row['status'];

        if ($status == 'approved') {
            echo "<p style='color: green; font-size: 18px; font-weight: bold;'>Your table " . $table_number . " is booked successfully.</p>";

            // Update table status in tables database
            $update_query = "UPDATE tables SET status = 'booked' WHERE table_number = '$table_number'";
            $conn->query($update_query);
        } elseif ($status == 'rejected') {
            echo "<p style='color: red; font-size: 18px; font-weight: bold;'>There is some fault, so you didn't book your table. Can you try another table?</p>";

            // Ensure table remains available
            $update_query = "UPDATE tables SET status = 'available' WHERE table_number = '$table_number'";
            $conn->query($update_query);
        } else {
            // Ensure table remains available if status is pending
            $update_query = "UPDATE tables SET status = 'available' WHERE table_number = '$table_number'";
            $conn->query($update_query);
        }
    } else {
        echo "<p style='color: blue; font-size: 18px;'>No booking found for your name and contact.</p>";
    }
} else {
    echo "<p style='color: red; font-size: 18px;'>Please provide your name and contact to check the status.</p>";
}
?>