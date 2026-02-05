<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  echo "NOT_LOGGED_IN";
  exit();
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_POST['order_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($order_id <= 0 || $rating < 1 || $rating > 5) {
  echo "INVALID_DATA";
  exit();
}

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  echo "DB_ERROR";
  exit();
}

/* Ensure order belongs to user and is completed */
$stmt = $conn->prepare("
  SELECT id FROM orders
  WHERE id = ? AND user_id = ? AND status = 'completed'
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "NOT_ALLOWED";
  exit();
}

/* Prevent duplicate feedback */
$check = $conn->prepare("SELECT id FROM feedback WHERE order_id = ?");
$check->bind_param("i", $order_id);
$check->execute();

if ($check->get_result()->num_rows > 0) {
  echo "ALREADY_SUBMITTED";
  exit();
}

/* Insert feedback */
$insert = $conn->prepare("
  INSERT INTO feedback (order_id, user_id, rating, comment)
  VALUES (?, ?, ?, ?)
");
$insert->bind_param("iiis", $order_id, $user_id, $rating, $comment);
$insert->execute();

echo "FEEDBACK_SUCCESS";

$conn->close();
