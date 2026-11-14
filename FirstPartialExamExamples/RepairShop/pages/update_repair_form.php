<?php
include '../helpers/db_connection.php';
session_start();
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: login.php?error=access");
    exit;
}
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM repairs WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $repair = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid repair ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Repair</title>
</head>
<body>
<h1>Update Repair</h1>

<?php if ($repair): ?>
    <form action="../handlers/update_repair.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($repair['id']); ?>">

        <label for="name">Client Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($repair['name']); ?>" required><br><br>

        <label for="model">Model:</label>
        <input type="text" name="model" value="<?php echo htmlspecialchars($repair['model']); ?>" required><br><br>

        <label for="date">Date:</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($repair['date']); ?>" required><br><br>

        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($repair['price']); ?>" required><br><br>

        <label for="status">Status:</label>
        <select name="status" required>
            <option value="Received" <?php echo htmlspecialchars($repair['status']) == 'Received' ? 'selected' : ''; ?>>
                Received
            </option>
            <option value="In Progress" <?php echo htmlspecialchars($repair['status']) == 'In Progress' ? 'selected' : ''; ?>>
                In Progress
            </option>
            <option value="Done" <?php echo htmlspecialchars($repair['status']) == 'Done' ? 'selected' : ''; ?>>Done
            </option>
        </select><br><br>

        <button type="submit">Update Repair</button>
    </form>
<?php else: ?>
    <p>Repair not found.</p>
<?php endif; ?>
</body>
</html>