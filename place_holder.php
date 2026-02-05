<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$meal_name = $_POST['meal_name'];
$quantity = $_POST['quantity'];
$total_price = $_POST['total_price'];  // You can calculate this on the frontend

// Insert the order into the orders table
include 'db.php';
$sql = "INSERT INTO orders (user_id, meal_name, quantity, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isid", $user_id, $meal_name, $quantity, $total_price);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Order placed successfully!";
} else {
    echo "Error placing order.";
}

$stmt->close();
$conn->close();
?>
