<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    // Fetch the current details of the student
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $product = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid student ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student</title>
</head>
<body>
<h1>Update Student</h1>

<?php if ($product): ?>
    <form action="update_product.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>
        <label for="category">Category:</label>
        <select name="category" required>
            <option value="Electronics" <?php echo $product['category'] == 'Electronics' ? 'selected':'';?>>
                Electronics
            </option>
            <option value="Food" <?php echo $product['category'] == 'Food' ? 'selected':'';?>>
                Food
            </option>
            <option value="Clothing" <?php echo $product['category'] == 'Clothing' ? 'selected':'';?>>
                Electronics
            </option>
        </select>
        <br><br>
        <label for="amount">Amount:</label>
        <input type="number" name="amount" value="<?php echo htmlspecialchars($product['amount']); ?>" required><br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>
        <label for="supplier">Supplier:</label>
        <input type="text" name="supplier" value="<?php echo htmlspecialchars($product['supplier']); ?>" required><br><br>
        <button type="submit">Update Product</button>
    </form>
<?php else: ?>
    <p>Product not found.</p>
<?php endif; ?>
</body>
</html>