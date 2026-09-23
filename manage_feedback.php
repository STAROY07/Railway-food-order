<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM feedback ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
<div class="container-fluid">
<span class="navbar-brand">💬 Manage Feedback</span>
<a href="admin_dashboard.php" class="btn btn-light btn-sm">Back</a>
</div>
</nav>

<div class="container mt-4">

<?php if(mysqli_num_rows($result) > 0){ ?>

<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>User Email</th>
<th>Rating</th>
<th>Message</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['user_email'] ?></td>
<td>⭐ <?= $row['rating'] ?>/5</td>
<td><?= $row['message'] ?></td>
<td><?= $row['created_at'] ?></td>
<td>
<a href="delete_feedback.php?id=<?= $row['id'] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this feedback?')">
Delete
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>

<?php } else { ?>

<div class="alert alert-warning text-center">
No Feedback Available
</div>

<?php } ?>

</div>

</body>
</html>