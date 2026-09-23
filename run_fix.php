<?php
// ONE-TIME DATABASE FIX SCRIPT
// Visit: http://localhost/railway_food_order/run_fix.php
// DELETE THIS FILE AFTER RUNNING

$conn = mysqli_connect("localhost","root","","railway_food_adv");
if(!$conn){ die("DB Connection Failed: " . mysqli_connect_error()); }

$queries = [

    // 1. Add missing columns to users table
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS phone varchar(20) DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS gender varchar(10) DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS dob date DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS address text DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_pic varchar(100) DEFAULT NULL",

    // 2. Fix feedback table - rename 'user' to 'user_email'
    "ALTER TABLE feedback CHANGE COLUMN `user` user_email varchar(100)",
    "ALTER TABLE feedback ADD COLUMN IF NOT EXISTS email varchar(100) DEFAULT NULL",
    "ALTER TABLE feedback ADD COLUMN IF NOT EXISTS created_at datetime DEFAULT NULL",

    // 3. Fix orders table - add all missing columns
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS user_email varchar(100) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS food_name varchar(100) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS price decimal(10,2) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS quantity int DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS total_price decimal(10,2) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS seat_no varchar(20) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method varchar(30) DEFAULT NULL",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS order_status varchar(30) DEFAULT 'Pending'",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS order_date datetime DEFAULT CURRENT_TIMESTAMP",

    // 4. Create complaints table (was missing entirely)
    "CREATE TABLE IF NOT EXISTS complaints (
        id int AUTO_INCREMENT PRIMARY KEY,
        name varchar(100),
        email varchar(100),
        message text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP
    )",

    // 5. Create cart table (was missing entirely)
    "CREATE TABLE IF NOT EXISTS cart (
        id int AUTO_INCREMENT PRIMARY KEY,
        user_email varchar(100),
        item_name varchar(100),
        price decimal(10,2),
        qty int DEFAULT 1
    )",
];

echo "<!DOCTYPE html><html><head><title>DB Fix</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='p-4'>";
echo "<h3>🔧 Database Fix Results</h3><table class='table table-bordered'>";
echo "<tr><th>#</th><th>Query</th><th>Result</th></tr>";

$i = 1;
foreach($queries as $sql){
    $short = substr(trim($sql), 0, 80) . (strlen($sql) > 80 ? '...' : '');
    if(mysqli_query($conn, $sql)){
        echo "<tr class='table-success'><td>$i</td><td><code>$short</code></td><td>✅ OK</td></tr>";
    } else {
        $err = mysqli_error($conn);
        // "Duplicate column" errors are fine - means column already exists
        if(strpos($err, 'Duplicate column') !== false || strpos($err, 'already exists') !== false){
            echo "<tr class='table-warning'><td>$i</td><td><code>$short</code></td><td>⚠️ Already exists (OK)</td></tr>";
        } else {
            echo "<tr class='table-danger'><td>$i</td><td><code>$short</code></td><td>❌ $err</td></tr>";
        }
    }
    $i++;
}

echo "</table>";

// Show final table list
$tables = mysqli_query($conn, "SHOW TABLES");
echo "<h5>📋 Tables in database:</h5><ul>";
while($t = mysqli_fetch_row($tables)){
    echo "<li><strong>{$t[0]}</strong></li>";
}
echo "</ul>";
echo "<div class='alert alert-success'><strong>✅ Done! All fixes applied.</strong><br>You can now <a href='index.php'>go back to the site</a>.<br><strong>⚠️ Please delete run_fix.php after this.</strong></div>";
echo "</body></html>";

mysqli_close($conn);
?>
