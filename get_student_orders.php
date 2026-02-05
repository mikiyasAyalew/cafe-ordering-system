<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  echo json_encode([]);
  exit();
}

$stmt = $conn->prepare("
  SELECT id, meal_name, price, status, created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$orders = [];

while ($row = $result->fetch_assoc()) {
  $orders[] = $row;
}

echo json_encode($orders);

$stmt->close();
$conn->close();
