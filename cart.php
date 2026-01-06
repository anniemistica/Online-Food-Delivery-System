<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'annie');
if (mysqli_connect_error())
 {
    echo "Connection Failed";
 }
 else
 {
    // Check if the form was submitted
    if (isset($_POST['add_to_cart']))
       {
        // Get product details from the form
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_img = $_POST['product_img'];
        
        // You can set the quantity to 1 initially
        $quantity += 1;
  
        // Insert the product into the cart table 
        $sql = "INSERT INTO cart (product_name, product_price, product_img, quantity) 
                VALUES ('$product_name', '$product_price', '$product_img', '$quantity')";

        if (mysqli_query($conn, $sql)) {
            echo "Product added to cart!";
            // Redirect to the cart page or display a success message

            header('Location: cart display.php');
            exit;
        }
        else
           {
            echo "Error..." . $sql . "<br>" . mysqli_error($conn);
           }
    }
}
?>
