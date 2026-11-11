<?php
include 'db_connection.php';

$db = connectDatabase();

$query = "SELECT * FROM products";
$result = $db->query($query);

if (!$result) {
    die("Error fetching products: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
<div style="display: flex; align-items: center; justify-content: space-between">
    <h1>Products List</h1>
    <a href="add_product_form.php">
        Add Product
    </a>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Amount</th>
        <th>Price</th>
        <th>Supplier</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($product = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['id']); ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
                <td><?php echo $product['amount'] == 0 ? 'Out of stock' : htmlspecialchars($product['amount']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['supplier']); ?></td>
                <td>
                    <form action="delete_product.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="update_product_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No products found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>