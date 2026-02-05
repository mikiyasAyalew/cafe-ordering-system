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

$result = $conn->query("
  SELECT order_id FROM feedback WHERE user_id = $user_id
");

$ids = [];
while ($row = $result->fetch_assoc()) {
  $ids[] = intval($row['order_id']);
}

echo json_encode($ids);
$conn->close();
