<?php
include 'db.php';
session_start();

// Handling Add to Cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['product_id'];
    $item_name = $_POST['product_name'];
    $item_price = $_POST['product_price'];
    $item_img = $_POST['product_img'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Get quantity input

    // Check if item is already in the cart
    $check_sql = "SELECT * FROM cart WHERE product_name='$item_name'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Update quantity if item exists
        $update_sql = "UPDATE cart SET quantity = quantity + $quantity WHERE product_name='$item_name'";
        mysqli_query($conn, $update_sql);
    } else {
        // Insert new item with chosen quantity
        $insert_sql = "INSERT INTO cart (product_name, product_price, product_img, quantity) 
                       VALUES ('$item_name', '$item_price', '$item_img', '$quantity')";
        mysqli_query($conn, $insert_sql);
    }
}

// Handle search input
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
}

// Fetch menu items from the database based on search
$sql = "SELECT * FROM samp";
if (!empty($search_query)) {
    $sql .= " WHERE product_name LIKE '%$search_query%'";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content = "width = device-width, initial-scale = 1.0">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #ff9966, #ff5e62);
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(to right, #333399, #ff00cc);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .view-cart {
            background: #ffcc00;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .view-cart:hover {
            background: #ffaa00;
        }
        .search-bar {
            margin: 20px auto;
            text-align: center;
        }
        .search-bar input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px;
            background: #333399;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background: #ff00cc;
        }
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .menu-item {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            text-align: center;
        }
        .menu-item img {
            width: 100%;
            border-radius: 10px;
            height: 150px;
            object-fit: cover;
        }
        .menu-item p {
            font-size: 18px;
            margin: 10px 0;
            font-weight: bold;
        }
        .add-to-cart {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .add-to-cart:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Our Menu</h1>
        <a href="cart display.php" class="view-cart">View Cart</a>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for food..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Menu Items -->
    <div class="menu-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='menu-item'>";
                echo "<img src='" . $row['product_img'] . "' alt='" . $row['product_name'] . "'>";
                echo "<p>" . $row['product_name'] . " - ₹" . $row['product_price'] . "</p>";
                
                // Add quantity input field
                echo "<form method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<input type='hidden' name='product_name' value='" . $row['product_name'] . "'>";
                echo "<input type='hidden' name='product_price' value='" . $row['product_price'] . "'>";
                echo "<input type='hidden' name='product_img' value='" . $row['product_img'] . "'>";
                
                // Quantity Input Field
                echo "<input type='number' name='quantity' value='1' min='1' required style='width: 60px; text-align: center; margin-bottom: 10px;'>";

                echo "<button type='submit' name='add_to_cart' class='add-to-cart'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; font-size:20px; color:white;'>No items found.</p>";
        }
        ?>
    </div>
</body>
</html>