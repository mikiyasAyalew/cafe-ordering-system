<?php
session_start();

/*
  TEMP for now: allow access if logged in.
  Later we will restrict to staff role.
*/
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "meal_ordering");
if ($conn->connect_error) {
  die("DB connection failed");
}

// Handle update (toggle availability)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $meal_id = intval($_POST["meal_id"]);
  $available = intval($_POST["available"]); // 0 or 1

  $stmt = $conn->prepare("UPDATE meals SET available = ? WHERE id = ?");
  $stmt->bind_param("ii", $available, $meal_id);
  $stmt->execute();
  $stmt->close();
}

// Load meals
$result = $conn->query("SELECT * FROM meals ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Kitchen - Meal Availability</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    .btn { padding: 8px 12px; border: none; cursor: pointer; }
  </style>
</head>
<body>

  <h2>Kitchen Dashboard - Meal Availability</h2>

  <table>
    <tr>
      <th>Meal</th>
      <th>Price</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php while($meal = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($meal["name"]); ?></td>
        <td>$<?php echo number_format($meal["price"], 2); ?></td>
        <td>
          <?php echo $meal["available"] ? "Available" : "Unavailable"; ?>
        </td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="meal_id" value="<?php echo $meal["id"]; ?>">
            <input type="hidden" name="available" value="<?php echo $meal["available"] ? 0 : 1; ?>">
            <button class="btn">
              Set <?php echo $meal["available"] ? "Unavailable" : "Available"; ?>
            </button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>

  </table>

</body>
</html>
<?php $conn->close(); ?>
