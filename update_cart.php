<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['action'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $user = mysqli_real_escape_string($conn, $_SESSION['user']);

    if($action == 'inc'){
        mysqli_query($conn, "UPDATE cart SET qty = qty + 1 WHERE id='$id' AND user_email='$user'");
    }

    if($action == 'dec'){
        $q = mysqli_query($conn, "SELECT qty FROM cart WHERE id='$id' AND user_email='$user'");
        if($row = mysqli_fetch_assoc($q)){
            if($row['qty'] > 1){
                mysqli_query($conn, "UPDATE cart SET qty = qty - 1 WHERE id='$id' AND user_email='$user'");
            } else {
                // If quantity reaches 0, remove item
                mysqli_query($conn, "DELETE FROM cart WHERE id='$id' AND user_email='$user'");
            }
        }
    }
}

header("Location: cart.php");
exit();
?>
