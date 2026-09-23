<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Order Food</title>

<style>
body{
  margin:0;
  font-family: Arial, sans-serif;
  background: linear-gradient(120deg,#007bff,#00c6ff);
  min-height:100vh;
}

.container{
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
}

.box{
  background:#fff;
  width:500px;
  padding:30px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.25);
}

h2{
  text-align:center;
  color:#007bff;
  margin-bottom:5px;
}

p{
  text-align:center;
  color:#555;
  margin-bottom:20px;
}

label{
  display:block;
  font-weight:bold;
  margin-top:12px;
}

input[type=text],
select{
  width:100%;
  padding:10px;
  margin-top:6px;
  border-radius:6px;
  border:1px solid #ccc;
}

.radio-group{
  margin-top:8px;
}

.radio-group input{
  margin-right:5px;
}

.radio-group label{
  display:inline-block;
  font-weight:normal;
  margin-right:20px;
}

button{
  width:100%;
  padding:12px;
  margin-top:25px;
  background:#28a745;
  color:white;
  border:none;
  font-size:16px;
  border-radius:6px;
  cursor:pointer;
}

button:hover{
  background:#218838;
}
</style>

</head>
<body>

<div class="container">
<div class="box">

<h2>🍽️ Order Food</h2>
<p>Enter journey and food details</p>

<form method="post" action="payment.php">

<!-- PNR -->
<label>PNR Number</label>
<input type="text" name="pnr" placeholder="Enter PNR" required>

<!-- Food -->
<label>Select Food Item</label>
<select name="food" required>
  <option value="">-- Select Food --</option>
  <option value="Veg Thali">Veg Thali</option>
  <option value="Paneer Butter Masala">Paneer Butter Masala</option>
  <option value="Chicken Biryani">Chicken Biryani</option>
  <option value="Egg Fried Rice">Egg Fried Rice</option>
</select>

<!-- Veg / Non-Veg -->
<label>Food Category</label>
<div class="radio-group">
  <label>
    <input type="radio" name="category" value="Veg" required>
    Veg
  </label>
  <label>
    <input type="radio" name="category" value="Non-Veg">
    Non-Veg
  </label>
</div>

<!-- Coach -->
<label>Coach</label>
<input type="text" name="coach" placeholder="Example: S2" required>

<!-- Seat -->
<label>Seat Number</label>
<input type="text" name="seat" placeholder="Example: 45" required>

<!-- Station -->
<label>Delivery Station</label>
<select name="station" required>
  <option value="">-- Select Station --</option>
  <option value="Mumbai">Mumbai</option>
  <option value="Pune">Pune</option>
  <option value="Nagpur">Nagpur</option>
  <option value="Delhi">Delhi</option>
</select>

<button type="submit">Proceed to Payment</button>

</form>

</div>
</div>

</body>
</html>
