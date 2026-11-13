<?php
include '../helpers/db_connection.php';

session_start();
include "../helpers/jwt_helper.php";

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM expenses WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $expense = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid expense ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Expense</title>
</head>
<body>
<h1>Update Expense</h1>

<?php if ($expense): ?>
    <form action="../handlers/update_expense.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($expense['id']); ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($expense['name']); ?>" required><br><br>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($expense['date']); ?>" required><br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($expense['amount']); ?>"
               required><br><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="Cash" <?php echo htmlspecialchars($expense['type']) == 'Cash' ? 'selected' : ''; ?>>Cash
            </option>
            <option value="Card" <?php echo htmlspecialchars($expense['type']) == 'Card' ? 'selected' : ''; ?>>Card
            </option>
        </select>
        <br><br>

        <button type="submit">Update Expense</button>
    </form>
<?php else: ?>
    <p>Expense not found.</p>
<?php endif; ?>
</body>
</html>