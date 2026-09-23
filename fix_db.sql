USE railway_food_adv;

-- 1. Add missing columns to users table
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS phone varchar(20) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS gender varchar(10) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS dob date DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS address text DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS profile_pic varchar(100) DEFAULT NULL;

-- 2. Fix feedback table: rename 'user' col to 'user_email', add email & created_at
ALTER TABLE feedback
  CHANGE COLUMN user user_email varchar(100),
  ADD COLUMN IF NOT EXISTS email varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS created_at datetime DEFAULT NULL;

-- 3. Fix orders table: drop old 'user' column and add all proper columns
ALTER TABLE orders
  ADD COLUMN IF NOT EXISTS user_email varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS food_name varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS price decimal(10,2) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS quantity int DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS total_price decimal(10,2) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS seat_no varchar(20) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS payment_method varchar(30) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS order_status varchar(30) DEFAULT 'Pending',
  ADD COLUMN IF NOT EXISTS order_date datetime DEFAULT CURRENT_TIMESTAMP;

-- 4. Create complaints table (missing entirely)
CREATE TABLE IF NOT EXISTS complaints (
  id int AUTO_INCREMENT PRIMARY KEY,
  name varchar(100),
  email varchar(100),
  message text,
  created_at datetime DEFAULT CURRENT_TIMESTAMP
);

-- 5. Create cart table (missing entirely)
CREATE TABLE IF NOT EXISTS cart (
  id int AUTO_INCREMENT PRIMARY KEY,
  user_email varchar(100),
  item_name varchar(100),
  price decimal(10,2),
  qty int DEFAULT 1
);

SHOW TABLES;
DESCRIBE users;
DESCRIBE orders;
DESCRIBE feedback;
