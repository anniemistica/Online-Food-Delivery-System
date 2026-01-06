<?php
// Database configuration
$host = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "annie";

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $review = $_POST['review'];

    if (!empty($name) && !empty($review)) {
        $sql = "INSERT INTO reviews (name, review) VALUES ('$name', '$review')";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Review submitted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Please fill in all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Food Delivery Reviews</title>
  <style>
    /* Body Gradient Background */
   body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
  background: linear-gradient(135deg, #cba9fe, #f6a08d);
  color: black;
  text-align: center;
}

h1 {
  font-size: 2.5rem;
  margin-top: 30px;
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
  color: #ffffff;
}

form {
  max-width: 600px;
  margin: 20px auto;
  padding: 20px;
  border-radius: 10px;
  background: radial-gradient(circle at top, #ffe6fa, #d8b5ff);
  color: black;
}

label {
  display: block;
  font-weight: bold;
  margin-top: 10px;
  color: #2d2d2d;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid black;
  border-radius: 4px;
  background-color: #f8e9ff;
}

textarea {
  height: 100px;
}

input[type="submit"] {
  background-color: #7873f5;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
  font-size: 1rem;
  transition: background 0.3s;
}

input[type="submit"]:hover {
  background-color: #5a56d0;
}

table {
  width: 80%;
  margin: 30px auto;
  border-collapse: collapse;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

th, td {
  padding: 12px;
  border: 1px solid black;
  text-align: left;
  background: linear-gradient(120deg, #fce1ff 0%, #e1d8ff 100%);
}

th {
 background: linear-gradient(120deg, #c7f8ab 0%, #f68888 100%);
  font-size: 1.2rem;
  color: black;
}
  </style>
</head>
<body>

<h1>Food Delivery Reviews</h1>

<!-- Review Form -->
<form action="" method="POST">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" required>

  <label for="review">Review:</label>
  <textarea id="review" name="review" required></textarea>

  <input type="submit" value="Submit">
</form>

<!-- Display Reviews Table -->
<h2>Customer Reviews</h2>
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Review</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Fetch and display reviews from the database
    $sql = "SELECT name, review, created_at FROM reviews ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['review']) . "</td>
                    <td>" . $row['created_at'] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No reviews yet.</td></tr>";
    }

    $conn->close();
    ?>
  </tbody>
</table>

</body>
</html>