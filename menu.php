<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "annie";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories separately
$categories = [
    "Mid-day Meal" => "SELECT product_name, product_price, description FROM samp WHERE id BETWEEN 1 AND 10",
    "Fusion Delights" => "SELECT product_name, product_price, description FROM samp WHERE id BETWEEN 11 AND 20",
    "Beverages" => "SELECT product_name, product_price, description FROM samp WHERE id BETWEEN 21 AND 30"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Menu</title>
<style>
/* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8;
    color: #333;
}
h1, h2 {
    margin: 0;
}
a {
    text-decoration: none;
    color: inherit;
}
.navbar {
    background-color: #ec3211;
    padding: 10px 0;
}
.navbar ul {
    display: flex;
    justify-content: center;
    list-style: none;
    margin: 0;
    padding: 0;
}
.navbar li {
    margin: 0 15px;
    transition: transform 0.3s;
}
.navbar a {
    text-decoration: none;
    color: black;
    font-size: 1.1em;
    font-weight: bold;
}
.navbar li:hover {
    transform: scale(1.1);
    color: yellowgreen;
}
.header {
    text-align: center;
    background-color: #ff4500;
    color: white;
    padding: 20px 10px;
}
.header h1 {
    font-size: 3rem;
}
.menu-container {
    max-width: 1200px;
    margin: 20px auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.category {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.50);
    padding: 20px;
    text-align: center;
}
.category h2 {
    background-color: #ff4500;
    color: white;
    padding: 15px;
    border-radius: 5px;
}
.category ul {
    list-style-type: none;
    padding: 0;
}
.category ul li {
    margin: 10px 0;
    font-size: 1.1rem;
}
.view-all {
    display: block;
    margin: 10px auto;
    padding: 10px;
    background-color: red;
    color: white;
    border-radius: 5px;
    font-size: 1rem;
    text-align: center;
    width: 120px;
}
.view-all:hover {
    background-color: yellowgreen;
}
/* Marquee */
.marquee-container {
    width: 100%;
    background-color: #ff4500;
    padding: 10px 0;
    overflow: hidden;
    text-align: center;
}
.marquee-text {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    animation: marquee 10s linear infinite;
}
@keyframes marquee {
    from { transform: translateX(100%); }
    to { transform: translateX(-100%); }
}
/* Order Now Button */
.order-container {
    text-align: center;
    margin-top: 20px;
}
.order-now {
    display: inline-block;
    padding: 8px 15px;
    background-color: #ec3211;
    color: white;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}
.order-now:hover {
    background-color: yellowgreen;
}
/* Maple Leaf Info */
.info-container {
    max-width: 1200px;
    margin: 40px auto;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
}
.info-text {
    flex: 1;
}
.info-text h2 {
    color: #ff4500;
}
.info-text p {
    font-size: 1.2rem;
}
.info-image img {
    max-width: 100%;
    border-radius: 10px;
}
.category ul li {
    background: #fff5e1;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    transition: 0.3s;
}

/* Hover effect for menu items */
.category ul li:hover {
    background: #ffcc80;
    transform: scale(1.05);
}
</style>
</head>
<body>

<header class="header">
    <h1>Maple Leaf</h1>
    <p>Food Delivery</p>
    <nav class="navbar">
        <ul>
            <li><a href="page.html" target="_blank">Home</a></li>
            <li><a href="about.html" target="_blank">About</a></li>
            <li><a href="contact.html" target="_blank">Contact</a></li>
            <li><a href="quality.html" target="_blank">Quality & Services</a></li>
            <li><a href="cart_display.php" target="_blank">Your Cart</a></li>
        </ul>
    </nav>
</header>

<!-- Marquee -->
<div class="marquee-container">
    <div class="marquee-text">Cheers to beginnings, bites, and bonding for our new arrivals!!</div>
</div>

<!-- Food Menu -->
<div class="menu-container">
    <?php foreach ($categories as $category_name => $query): ?>
        <div class="category">
            <h2><?php echo $category_name; ?></h2>
            <ul>
                <?php
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<li><b>' . $row["product_name"] . '</b> - ₹' . $row["product_price"] . '</li>';
                    }
                } else {
                    echo "<li>No items available.</li>";
                }
                ?>
            </ul>
            <a href="view.php" target="_blank" class="view-all">View All</a>
        </div>
    <?php endforeach; ?>
</div>

<!-- Maple Leaf Info -->
<div class="info-container">
    <div class="info-text">
        <h2>Welcome to Maple Leaf</h2>
        <p>Experience the finest cuisines made by expert chefs. Come and explore the taste of happiness with us!</p>
    </div>
    <div class="info-image">
        <img src="food.jpeg" alt="Delicious Food">
    </div>
</div>

<!-- Order Now Button -->
<div class="order-container">
    <a href="cart display.php" class="order-now">Place Order Now</a>
</div>

<footer class="footer">
    <p>&copy; 2024 Maple Leaf. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>