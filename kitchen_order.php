<?php
session_start();

/*
  TEMP ACCESS RULE:
  Anyone logged in can access.
  Later you can restrict to staff role.
*/
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  die("Database connection failed");
}

/* Handle order completion */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"])) {
  $order_id = intval($_POST["order_id"]);

  $stmt = $conn->prepare(
    "UPDATE orders SET status = 'completed' WHERE id = ?"
  );
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $stmt->close();
}

/* Fetch orders */
$result = $conn->query("
  SELECT
    orders.id,
    users.username,
    orders.meal_name,
    orders.price,
    orders.status,
    orders.created_at
  FROM orders
  JOIN users ON orders.user_id = users.id
  ORDER BY orders.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Kitchen Order Queue</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }
    h2 {
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background: #333;
      color: #fff;
    }
    .pending {
      color: orange;
      font-weight: bold;
    }
    .completed {
      color: green;
      font-weight: bold;
    }
    button {
      padding: 6px 12px;
      border: none;
      cursor: pointer;
      background: #28a745;
      color: #fff;
      border-radius: 4px;
    }
    button:disabled {
      background: #aaa;
      cursor: not-allowed;
    }
  </style>
</head>
<body>

<h2>Kitchen Order Queue</h2>

<table>
  <tr>
    <th>Order ID</th>
    <th>Student</th>
    <th>Meal</th>
    <th>Price</th>
    <th>Order Time</th>
    <th>Status</th>
    <th>Action</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row["id"]; ?></td>
      <td><?php echo htmlspecialchars($row["username"]); ?></td>
      <td><?php echo htmlspecialchars($row["meal_name"]); ?></td>
      <td>$<?php echo number_format($row["price"], 2); ?></td>
      <td><?php echo $row["created_at"]; ?></td>
      <td class="<?php echo $row["status"]; ?>">
        <?php echo ucfirst($row["status"]); ?>
      </td>
      <td>
        <?php if ($row["status"] === "pending"): ?>
          <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $row["id"]; ?>">
            <button type="submit">Mark Completed</button>
          </form>
        <?php else: ?>
          <button disabled>Completed</button>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>

</table>

</body>
</html>
<?php $conn->close(); ?>
