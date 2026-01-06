<?php
$conn = new mysqli("localhost", "root", "", "annie"); // Database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch reservations
$query = "SELECT * FROM reservations ORDER BY reservation_time DESC";
$result = $conn->query($query);

// Process status updates
while ($row = $result->fetch_assoc()) {
    $table_number = $row['table_number'];
    $status = $row['status'];

if ($status == 'approved') {
  // Update table status to 'booked'
  $updateQuery = "UPDATE tables SET status ='booked' WHERE table_number ='$table_number'";
  $conn->query($updateQuery);
} elseif ($status == 'pending' || $status == 'rejected') {
  // Keep table status as 'available'
  $updateQuery = "UPDATE tables SET status ='available' WHERE table_number ='$table_number'";
        $conn->query($updateQuery);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Status - Maple Leaf</title>
    <style>
        /* Page Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ff6ec4, #7873f5);
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        /* Header Styling */
        .header {
            background: linear-gradient(to right, #ff3e6b, #a00068);
            padding: 20px;
            font-size: 40px;
            font-weight: bold;
            text-shadow: 4px 4px 10px rgba(255, 255, 255, 0.6);
            letter-spacing: 3px;
            animation: glow 1.5s infinite alternate;
            position: relative;
        }
        @keyframes glow {
            0% { text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.4); }
            100% { text-shadow: 4px 4px 20px rgba(255, 255, 255, 1); }
        }
        /* Go Back Button */
        .home-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background: #ffcc00;
            color: black;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 2px 2px 10px rgba(255, 255, 255, 0.3);
        }
        .home-btn:hover {
            background: #ffdb4d;
            transform: scale(1.1);
        }
        /* Marquee Announcement */
        .marquee {
            font-size: 18px;
            font-weight: bold;
            color: white;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
        }
        .marquee span {
            display: inline-block;
            animation: marquee 10s linear infinite;
        }
        @keyframes marquee {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }
        /* Table Styling */
        .container {
            width: 80%;
            margin: auto;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border: 2px solid white;
            text-align: center;
            font-weight: bold;
        }
        th {
            background: rgba(255, 255, 255, 0.2);
            font-size: 18px;
            text-transform: uppercase;
        }
        td {
            font-size: 17px;
        }
        /* Row Hover Effect */
        tr:hover {
            background: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }
        /* Status Styling */
        .approved {
            background: #00ff99;
            color: black;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .rejected {
            background: #ff3333;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .pending {
            background: #ffcc00;
            color: black;
            border-radius: 5px;
            padding: 5px 10px;
        }
        /* Fade-in Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .container {
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>
<body>
<div class="header">
    Reservation Status
    <a href="page.html" class="home-btn">Go Back to Home Page</a> 
</div>
<div class="container">
    <table>
        <tr>
            <th>User Name</th>
            <th>Contact</th>
            <th>Table Number</th>
            <th>Reservation Time</th>
            <th>Status</th>
        </tr>
        <?php 
        $result = $conn->query($query); // Fetch updated data
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                <td><?php echo htmlspecialchars($row['table_number']); ?></td>
                <td><?php echo htmlspecialchars($row['reservation_time']); ?></td>
                <td class="<?php echo strtolower($row['status']); ?>">
                    <?php echo ucfirst($row['status']); ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>