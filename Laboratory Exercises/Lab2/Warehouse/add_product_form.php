<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
</head>
<body>
<form action="add_product.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br>
    <br>
    <label for="email">Category:</label>
    <select name="category" id="category" required>
        <option>Electronics</option>
        <option>Food</option>
        <option>Clothing</option>
    </select>
    <br>
    <br>
    <label for="age">Amount:</label>
    <input type="number" name="amount" id="amount" required>
    <br>
    <br>
    <label for="age">Price:</label>
    <input type="number" name="price" id="price" required>
    <br>
    <br>
    <label for="age">Supplier:</label>
    <input type="text" name="supplier" id="supplier" required>
    <br>
    <br>
    <button type="submit">Add Product</button>
</form>
</body>