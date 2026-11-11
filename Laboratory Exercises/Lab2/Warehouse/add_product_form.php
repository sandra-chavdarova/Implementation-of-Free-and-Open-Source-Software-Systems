<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
<form action="add_product.php" method="POST">
    <label for="name">Name:</label>
    <!--    <input type="text" name="name" id="name" required>-->
    <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
    <?php if (isset($errors['name'])): ?>
        <br>    <span style="color:red">
        <?= htmlspecialchars($errors['name']) ?>
    </span>
    <?php endif; ?>
    <br>
    <br>
    <label for="email">Category:</label>
    <select name="category" id="category" required>
        <option value="Electronics" <?= ($old['category'] ?? '') === 'Electronics' ? 'selected' : '' ?>>
            Electronics
        </option>
        <option value="Food"
                <?= ($old['category'] ?? '') === 'Food' ? 'selected' : '' ?>>
            Food
        </option>
        <option value="Clothing"
                <?= ($old['category'] ?? '') === 'Clothing' ? 'selected' : '' ?>>
            Clothing
        </option>
    </select>
    <?php if (isset($errors['category'])): ?>
        <br>
        <span style="color:red">
        <?= htmlspecialchars($errors['category']) ?>
        </span>
    <?php endif; ?>
    <br>
    <br>
    <label for="age">Amount:</label>
    <!--    <input type="number" name="amount" id="amount" required>-->
    <input type="number" name="amount" value="<?= htmlspecialchars($old['amount'] ?? '') ?>">
    <?php if (isset($errors['amount'])): ?>
        <br><span style="color:red">
        <?= htmlspecialchars($errors['amount']) ?>
    </span>
    <?php endif; ?>
    <br>
    <br>
    <label for="age">Price:</label>
    <!--    <input type="number" name="price" id="price" step="any" min="0.01" required>-->
    <input type="number" name="price" value="<?= htmlspecialchars($old['price'] ?? '') ?>">
    <?php if (isset($errors['price'])): ?>
        <br><span style="color:red">
        <?= htmlspecialchars($errors['price']) ?>
    </span>
    <?php endif; ?>
    <br>
    <br>
    <label for="age">Supplier:</label>
    <!--    <input type="text" name="supplier" id="supplier" required>-->
    <input type="text" name="supplier" value="<?= htmlspecialchars($old['supplier'] ?? '') ?>">
    <?php if (isset($errors['supplier'])): ?>
        <br><span style="color:red">
        <?= htmlspecialchars($errors['supplier']) ?>
    </span>
    <?php endif; ?>
    <br>
    <br>
    <button type="submit">Add Product</button>
</form>
</body>