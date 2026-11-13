<?php
include '../helpers/db_connection.php';
session_start();
include '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM subscriptions WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $subscription = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid subscription ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Subscription</title>
</head>
<body>
<h1>Update Student</h1>

<?php if ($subscription): ?>
    <form action="../handlers/update_subscription.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($subscription['id']); ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($subscription['name']); ?>"
               required><br><br>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($subscription['date']); ?>"
               required><br><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="Monthly" <?php echo htmlspecialchars($subscription['type']) == 'Monthly' ? 'selected' : ''; ?>>Monthly</option>
            <option value="Yearly" <?php echo htmlspecialchars($subscription['type']) == 'Yearly' ? 'selected' : ''; ?>>Yearly</option>
        </select><br><br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($subscription['price']); ?>"
               required><br><br>

        <button type="submit">Update Subscription</button>
    </form>
<?php else: ?>
    <p>Subscription not found.</p>
<?php endif; ?>
</body>
</html>