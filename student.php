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
  <title>Student Meal Ordering</title>
  <link rel="stylesheet" href="student.css" />
</head>
<body>

<!-- TOP NAV -->
<header class="top-nav">
  <div class="brand">MealZone</div>
  <div>
    <span style="margin-right:15px;">
      Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
    </span>
    <a href="logout.php" class="btn">Logout</a>
  </div>
</header>

<main style="margin-top:100px;">

  <!-- ORDER MESSAGE -->
  <div id="orderMessage" class="order-message">
    Your order is on the way. Thank you for using our service!
  </div>

  <!-- MEALS -->
  <section class="hero">
    <h1>Available Meals Today</h1>
    <div class="grid" id="mealList"></div>
  </section>

  <!-- STUDENT ORDERS -->
  <section class="hero">
    <h1>Your Orders</h1>
    <div class="grid" id="orderList"></div>
  </section>

</main>

<!-- ORDER MODAL -->
<div id="orderModal" class="order-modal">
  <div class="modal-content">
    <h2>Confirm Your Order</h2>
    <p id="meal-name"></p>
    <p id="meal-price"></p>
    <button id="confirmOrder" class="btn">Confirm</button>
    <button id="cancelOrder" class="btn">Cancel</button>
  </div>
</div>

<footer class="footer">
  <p>&copy; 2025 MealZone</p>
</footer>

<script src="main.js"></script>
<!-- FEEDBACK MODAL -->
<div id="feedbackModal" class="order-modal">
  <div class="modal-content">
    <h2>Leave Feedback</h2>

    <label>Rating (1–5)</label>
    <input type="number" id="feedbackRating" min="1" max="5" />

    <label>Comment</label>
    <textarea id="feedbackComment"></textarea>

    <button id="submitFeedback" class="btn">Submit</button>
    <button onclick="closeFeedbackModal()" class="btn">Cancel</button>
  </div>
</div>

</body>
</html>
