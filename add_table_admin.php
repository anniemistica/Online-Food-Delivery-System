<?php
$conn = new mysqli("localhost", "root", "", "annie");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_number = $_POST['table_number'];

    // Check if the table number already exists
    $check_query = "SELECT * FROM tables WHERE table_number = '$table_number'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error_message = "Table number already exists!";
    } else {
        // Insert new table with 'available' status
        $insert_query = "INSERT INTO tables (table_number, status) VALUES ('$table_number', 'available')";
        if ($conn->query($insert_query)) {
            $success_message = "New table added successfully!";
        } else {
            $error_message = "Error adding table: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Table - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            background: white;
            padding: 30px;
            width: 50%;
            margin: 40px auto;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .message {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add a New Table</h2>

    <?php if (isset($error_message)) echo "<p class='message error'>$error_message</p>"; ?>
    <?php if (isset($success_message)) echo "<p class='message success'>$success_message</p>"; ?>

    <form method="post">
        <input type="number" name="table_number" placeholder="Enter table number" required>
        <input type="submit" value="Add Table" class="btn">
    </form>
</div>

</body>
</html>