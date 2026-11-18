<?php
include '../helpers/db_connection.php';
session_start();
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: login.php?error=access");
    exit;
}

$id = intval($_GET['id']);

$db = connectDatabase();
$stmt = $db->prepare("SELECT * FROM rentals WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$rental = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
if (time() > (int)$rental['time'] + 86400) {
    echo "You are not allowed to edit after 24 hours of creation.";
    $db->close();
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM rentals WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $rental = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid rental ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Rental</title>
</head>
<body>
<h1>Update Rental</h1>

<?php if ($rental): ?>
    <form action="../handlers/update_rental.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($rental['id']); ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($rental['name']); ?>" required><br><br>

        <p>Type:</p>
        <label for="type">
            <input type="radio" name="type"
                   value="City" <?php echo htmlspecialchars($rental['type']) == 'City' ? 'checked' : ''; ?>>
            City
        </label><br>
        <label for="type">
            <input type="radio" name="type"
                   value="Mountain" <?php echo htmlspecialchars($rental['type']) == 'Mountain' ? 'checked' : ''; ?>>
            Mountain
        </label><br>
        <label for="type">
            <input type="radio" name="type"
                   value="Electric" <?php echo htmlspecialchars($rental['type']) == 'Electric' ? 'checked' : ''; ?>>
            Electric
        </label><br>

        <label for="date">Date:</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($rental['date']); ?>" required><br><br>

        <label for="days">Days:</label>
        <input type="number" name="days" value="<?php echo htmlspecialchars($rental['days']); ?>" required><br><br>

        <label for="price">Price per Day:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($rental['price']); ?>" required><br><br>

        <button type="submit">Update Rental</button>
    </form>
<?php else: ?>
    <p>Rental not found.</p>
<?php endif; ?>
</body>
</html>