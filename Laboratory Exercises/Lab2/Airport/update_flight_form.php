<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM flights WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $flight = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid flight ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update FLight</title>
</head>
<body>
<h1>Update Flight</h1>

<?php if ($flight): ?>
    <form action="update_flight.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($flight['id']); ?>">

        <label for="code">Code:</label>
        <input type="text" name="code" id="code" value="<?php echo htmlspecialchars($flight['code']); ?>"
               required><br><br>

        <label for="destination">Destination:</label>
        <input type="text" name="destination" id="destination"
               value="<?php echo htmlspecialchars($flight['destination']); ?>" required><br><br>

        <label for="departure">Departure Date:</label>
        <input type="date" name="departure" id="departure" value="<?php echo htmlspecialchars($flight['departure']); ?>"
               required><br><br>

        <label for="seats">Seats:</label>
        <input type="number" name="seats" id="seats" value="<?php echo htmlspecialchars($flight['seats']); ?>" required><br><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Scheduled"
                    <?php echo htmlspecialchars($flight['status']) == 'Scheduled' ? 'selected' : '' ?>>Scheduled
            </option>
            <option
                    <?php echo htmlspecialchars($flight['status']) == 'Delayed' ? 'selected' : '' ?>>Delayed
            </option>
            <option
                    <?php echo htmlspecialchars($flight['status']) == 'Cancelled' ? 'selected' : '' ?>>Cancelled
            </option>
        </select><br><br>

        <button type="submit">Update Flight</button>
    </form>
<?php else: ?>
    <p>Flight not found.</p>
<?php endif; ?>
</body>
</html>