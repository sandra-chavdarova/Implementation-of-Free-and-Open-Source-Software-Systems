<?php
require_once 'db_connection.php';
if (isset($_GET['id']) || isset($_POST['id'])) {
    $id = intval($_POST['id'] ?? $_GET['id']);
    $db = connectDatabase();

    // Fetch the current details of the products
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $product = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid product ID.");
}
$errors = $errors ?? [];
$old = $old ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
</head>
<body>
<h1>Update Product</h1>

<?php if ($product): ?>
    <form action="update_product.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">

        <label for="name">Name:</label>
        <!--        <input type="text" name="name" value="-->
        <?php //echo htmlspecialchars($product['name']); ?><!--" required><br><br>-->
        <!-- Use the value on the left if it exists and is not null; otherwise, use the value on the right -->
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $product['name']) ?>">
        <?php if (isset($errors['name'])): ?>
            <br><span style="color:red">
        <?= htmlspecialchars($errors['name']) ?>
                </span>
        <?php endif; ?>
        <br><br>

        <label for="category">Category:</label>
        <select name="category" required>
            <!--            <option value="Electronics" -->
            <?php //echo $product['category'] == 'Electronics' ? 'selected' : ''; ?><!-->-->
            <!--                Electronics-->
            <!--            </option>-->
            <option value="Electronics"
                    <?= ($old['category'] ?? $product['category']) === 'Electronics' ? 'selected' : '' ?>>
                Electronics
            </option>
            <option value="Food"
                    <?= ($old['category'] ?? $product['category']) === 'Food' ? 'selected' : '' ?>>
                Food
            </option>
            <option value="Clothing"
                    <?= ($old['category'] ?? $product['category']) === 'Clothing' ? 'selected' : '' ?>>
                Clothing
            </option>
        </select>
        <?php if (isset($errors['category'])): ?>
            <br>
            <span style="color:red">
        <?= htmlspecialchars($errors['category']) ?>
            </span>
        <?php endif; ?>
        <br><br>

        <label for="amount">Amount:</label>
        <!--        <input type="number" name="amount" value="-->
        <?php //echo htmlspecialchars($product['amount']); ?><!--" required><br><br>-->
        <input type="number" name="amount" value="<?= htmlspecialchars($old['amount'] ?? $product['amount']) ?>">
        <?php if (isset($errors['amount'])): ?>
            <br><span style="color:red">
        <?= htmlspecialchars($errors['amount']) ?>
                </span>
        <?php endif; ?>
        <br><br>

        <label for="price">Price:</label>
        <!--        <input type="number" name="price" step="any" min="0.01"-->
        <!--               value="--><?php //echo htmlspecialchars($product['price']); ?><!--" required><br><br>-->
        <input type="number" name="price" value="<?= htmlspecialchars($old['price'] ?? $product['price']) ?>">
        <?php if (isset($errors['price'])): ?>
            <br><span style="color:red">
        <?= htmlspecialchars($errors['price']) ?>
                </span>
        <?php endif; ?>
        <br><br>

        <label for="supplier">Supplier:</label>
        <!--        <input type="text" name="supplier" value="-->
        <?php //echo htmlspecialchars($product['supplier']); ?><!--"-->
        <!--               required>-->
        <input type="text" name="supplier" value="<?= htmlspecialchars($old['supplier'] ?? $product['supplier']) ?>">
        <?php if (isset($errors['supplier'])): ?>
            <br><span style="color:red">
        <?= htmlspecialchars($errors['supplier']) ?>
                </span>
        <?php endif; ?>
        <br><br>
        <button type="submit">Update Product</button>
    </form>
<?php else: ?>
    <p>Product not found.</p>
<?php endif; ?>
</body>
</html>