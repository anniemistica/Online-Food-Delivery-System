<?php
$conn = new mysqli("localhost", "root", "", "annie"); // Update credentials if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action']; // 'approve' or 'reject'

    if ($action == "approve") {
        $update_query = "UPDATE reservations SET status = 'approved' WHERE id = '$reservation_id'";
        $conn->query($update_query);
    } elseif ($action == "reject") {
        $update_query = "UPDATE reservations SET status = 'rejected' WHERE id = '$reservation_id'";
        $conn->query($update_query);
    }
}

// Fetch all reservations (approved, rejected, and pending)
$query = "SELECT * FROM reservations ORDER BY status ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Reservations</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Container */
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
        }

        /* Headings */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Buttons */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: white;
        }

        .approve {
            background-color: #28a745;
        }

        .reject {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reservation Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Table</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['table_number']; ?></td>
                <td><?php echo $row['reservation_time']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] == 'pending') { ?>
                        <form method="post">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="action" value="approve" class="btn approve">
                            <input type="submit" name="action" value="reject" class="btn reject">
                        </form>
                    <?php } else { echo "No action"; } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>