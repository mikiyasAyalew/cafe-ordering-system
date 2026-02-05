<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="student.css" />
</head>
<body>

<header class="top-nav">
  <div class="brand">MealZone</div>
  <a href="logout.php" class="btn">Logout</a>
</header>

<main style="margin-top:100px;">

  <section class="hero">
    <h1>Daily Summary</h1>

    <div class="grid">
      <div class="order-card">
        <h3>Total Orders</h3>
        <p id="totalOrders">0</p>
      </div>

      <div class="order-card">
        <h3>Total Revenue</h3>
        <p id="totalRevenue">$0.00</p>
      </div>
    </div>
  </section>

  <section class="hero">
    <h1>Orders by Meal</h1>
    <div class="grid" id="ordersByMeal"></div>
  </section>

  <section class="hero">
    <h1>Feedback</h1>
    <div class="grid" id="feedbackList"></div>
  </section>

</main>

<footer class="footer">
  <p>&copy; 2025 MealZone</p>
</footer>

<script src="manager.js"></script>
</body>
</html>
