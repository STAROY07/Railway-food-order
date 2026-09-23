<?php
// ONE-TIME FIX: Creates admins, restaurants, payments tables + uploads folder
$conn = mysqli_connect("localhost","root","","railway_food_adv");
if(!$conn){ die("DB Connection Failed"); }

$queries = [
    // admins table with default admin account
    "CREATE TABLE IF NOT EXISTS admins (
        id int AUTO_INCREMENT PRIMARY KEY,
        name varchar(100) DEFAULT 'Admin',
        email varchar(100) UNIQUE,
        password varchar(100),
        created_at datetime DEFAULT CURRENT_TIMESTAMP
    )",
    "INSERT IGNORE INTO admins (name, email, password) VALUES ('Super Admin', 'admin@railway.com', 'admin123')",

    // restaurants table
    "CREATE TABLE IF NOT EXISTS restaurants (
        id int AUTO_INCREMENT PRIMARY KEY,
        name varchar(100),
        owner_name varchar(100),
        email varchar(100),
        phone varchar(20),
        location varchar(200),
        description text,
        image varchar(200),
        created_at datetime DEFAULT CURRENT_TIMESTAMP
    )",

    // payments table
    "CREATE TABLE IF NOT EXISTS payments (
        id int AUTO_INCREMENT PRIMARY KEY,
        user_email varchar(100),
        payment_id varchar(100),
        amount decimal(10,2),
        method varchar(50) DEFAULT 'Online',
        status varchar(30) DEFAULT 'Paid',
        created_at datetime DEFAULT CURRENT_TIMESTAMP
    )",
];

echo "<!DOCTYPE html><html><head><title>Admin Fix</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='p-4'><h3>🔧 Admin Panel Fix</h3><ul class='list-group mb-4'>";

foreach($queries as $sql){
    if(mysqli_query($conn, $sql)){
        echo "<li class='list-group-item list-group-item-success'>✅ ".substr(trim($sql),0,70)."...</li>";
    } else {
        $e = mysqli_error($conn);
        if(strpos($e,'Duplicate') !== false){
            echo "<li class='list-group-item list-group-item-warning'>⚠️ Already exists: ".substr(trim($sql),0,50)."</li>";
        } else {
            echo "<li class='list-group-item list-group-item-danger'>❌ $e</li>";
        }
    }
}

// Create uploads directory
$uploadDir = __DIR__ . '/uploads/';
if(!is_dir($uploadDir)){
    if(mkdir($uploadDir, 0755, true)){
        echo "<li class='list-group-item list-group-item-success'>✅ Created uploads/ directory</li>";
    } else {
        echo "<li class='list-group-item list-group-item-danger'>❌ Could not create uploads/ directory</li>";
    }
} else {
    echo "<li class='list-group-item list-group-item-warning'>⚠️ uploads/ directory already exists</li>";
}

echo "</ul>";
echo "<div class='alert alert-success'>";
echo "<strong>✅ Done!</strong><br>";
echo "Admin credentials: <strong>admin@railway.com</strong> / <strong>admin123</strong><br>";
echo "<a href='admin_login.php' class='btn btn-dark mt-2'>Go to Admin Login</a>";
echo "</div></body></html>";
?>
