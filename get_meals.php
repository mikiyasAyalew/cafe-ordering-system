<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  echo json_encode([]);
  exit();
}

$result = $conn->query("SELECT id, name, description, price, image, available FROM meals ORDER BY id ASC");
$meals = [];

while ($row = $result->fetch_assoc()) {
  $row["available"] = (int)$row["available"];
  $meals[] = $row;
}

echo json_encode($meals);
$conn->close();
