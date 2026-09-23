<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $user = mysqli_real_escape_string($conn, $_SESSION['user']);
    
    // Delete item from cart for the logged in user
    $query = "DELETE FROM cart WHERE id='$id' AND user_email='$user'";
    mysqli_query($conn, $query);
}

header("Location: cart.php");
exit();
?>
