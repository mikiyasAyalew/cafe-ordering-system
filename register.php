<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($username === '' || $password === '' || $confirmPassword === '') {
        echo "All fields are required";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "Passwords do not match";
        exit;
    }

    $conn = new mysqli('localhost', 'root', '', 'meal_ordering');

    if ($conn->connect_error) {
        echo "Database connection failed";
        exit;
    }

    // Check if username already exists
    $check = $conn->prepare(query: "SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Username already exists";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Registration failed";
    }

    $stmt->close();
    $conn->close();
}
?>
