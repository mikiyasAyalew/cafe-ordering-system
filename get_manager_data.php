<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  echo json_encode([]);
  exit();
}

/* Total orders today */
$totalOrders = $conn->query("
  SELECT COUNT(*) AS total
  FROM orders
  WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['total'];

/* Total revenue today */
$totalRevenue = $conn->query("
  SELECT IFNULL(SUM(price),0) AS revenue
  FROM orders
  WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['revenue'];

/* Orders grouped by meal */
$mealResult = $conn->query("
  SELECT meal_name, COUNT(*) AS count
  FROM orders
  GROUP BY meal_name
");

$ordersByMeal = [];
while ($row = $mealResult->fetch_assoc()) {
  $ordersByMeal[] = $row;
}

/* Feedback list with ratings */
$feedbackResult = $conn->query("
  SELECT
    users.username,
    orders.meal_name,
    feedback.rating,
    feedback.comment,
    feedback.created_at
  FROM feedback
  JOIN users ON feedback.user_id = users.id
  JOIN orders ON feedback.order_id = orders.id
  ORDER BY feedback.created_at DESC
");

$feedback = [];
while ($row = $feedbackResult->fetch_assoc()) {
  $feedback[] = $row;
}

echo json_encode([
  "totalOrders" => $totalOrders,
  "totalRevenue" => $totalRevenue,
  "ordersByMeal" => $ordersByMeal,
  "feedback" => $feedback
]);

$conn->close();
