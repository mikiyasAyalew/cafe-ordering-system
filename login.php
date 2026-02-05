<?php
session_start();

header("Content-Type: text/plain");

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
    echo "DB_ERROR";
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo "EMPTY_FIELDS";
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "USER_NOT_FOUND";
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo "WRONG_PASSWORD";
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

echo "LOGIN_SUCCESS";
