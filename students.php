<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");  // Redirect to login page if not logged in
    exit();
}

// Fetch user orders from the database
include 'db.php';

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display user orders
while ($order = $result->fetch_assoc()) {
    echo "Meal: " . $order['meal_name'] . "<br>";
    echo "Quantity: " . $order['quantity'] . "<br>";
    echo "Total Price: $" . $order['total_price'] . "<br>";
    echo "Status: " . $order['order_status'] . "<br><br>";
}

$conn->close();
?>
