<?php
session_start();
header("Content-Type: text/plain");

if (!isset($_SESSION['user_id'])) {
    echo "NOT_LOGGED_IN";
    exit();
}

$user_id = $_SESSION['user_id'];
$meal_name = $_POST['meal_name'] ?? '';
$price = $_POST['price'] ?? '';

if ($meal_name === '' || $price === '') {
    echo "INVALID_DATA";
    exit();
}

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
    echo "DB_ERROR";
    exit();
}

$stmt = $conn->prepare(
  "INSERT INTO orders (user_id, meal_name, price) VALUES (?, ?, ?)"
);
$stmt->bind_param("isd", $user_id, $meal_name, $price);

if ($stmt->execute()) {
    echo "ORDER_SUCCESS";
} else {
    echo "ORDER_FAILED";
}

$stmt->close();
$conn->close();
