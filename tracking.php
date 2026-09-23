<?php include 'db.php';
$r=mysqli_query($conn,"SELECT * FROM orders ORDER BY id DESC LIMIT 1");
$d=mysqli_fetch_assoc($r);
echo "<h3>Status: ".$d['status']."</h3>";
?>