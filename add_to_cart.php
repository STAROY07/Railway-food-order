<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$item  = '';
$price = '';

if(isset($_POST['item'])){
    $item  = $_POST['item'];
    $price = $_POST['price'];
}
elseif(isset($_POST['item_name'])){
    $item  = $_POST['item_name'];
    $price = $_POST['price'];
}
else{
    die("No product data received");
}

$user = $_SESSION['user'];
$item  = mysqli_real_escape_string($conn,$item);
$price = mysqli_real_escape_string($conn,$price);
$qty   = 1;

$check = mysqli_query($conn,"SELECT * FROM cart 
    WHERE user_email='$user' AND item_name='$item'");

if(mysqli_num_rows($check) > 0){

    mysqli_query($conn,"UPDATE cart 
        SET qty = qty + 1 
        WHERE user_email='$user' AND item_name='$item'");

} else {

    $sql = "INSERT INTO cart (user_email,item_name,price,qty)
            VALUES ('$user','$item','$price','$qty')";

    if(!mysqli_query($conn,$sql)){
        echo "DB Error: ".mysqli_error($conn);
        exit();
    }
}

header("Location: cart.php");
exit();
?>
