<?php
include 'db.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    mysqli_query($conn,"DELETE FROM feedback WHERE id='$id'");
}

header("Location: manage_feedback.php");
exit();
?>