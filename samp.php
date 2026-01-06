<?php
$conn=mysqli_connect('localhost','root','','annie');
if(mysqli_connect_error()){
echo "Connection Failed";
}
 else {
    $name = $_POST['proname'];
    $img = $_POST['proimg'];
    $price = $_POST['price'];

    // Prepare the SQL query
    $sql = "INSERT INTO samp (name, img, price)VALUES('$name','$img','$price');";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        header('Location: view.php');
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}
?>
