<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $category = $_POST['category'];
    $amount = intval($_POST['amount']);
    $price = floatval($_POST['price']);
    $supplier = $_POST['supplier'];

    $errors = [];

    if (trim($name) === '') {
        $errors['name'] = 'Name is required';
    }

    if (!in_array($category, ['Electronics', 'Food', 'Clothing'])) {
        $errors['category'] = 'Invalid category';
    }

    if (filter_var($_POST['amount'] ?? '', FILTER_VALIDATE_INT) === false ||
        (int)($_POST['amount'] ?? 0) < 0) {
        $errors['amount'] = 'Amount must be a non-negative integer';
    }

    if (!is_numeric($_POST['price'] ?? '') ||
        (float)($_POST['price'] ?? 0) <= 0) {
        $errors['price'] = 'Price must be greater than 0';
    }

    if (trim($supplier) === '') {
        $errors['supplier'] = 'Supplier is required';
    }

    if (!empty($errors)) {
        $old = $_POST;
        include 'update_product_form.php';
        exit;
    }

//    if (empty(trim($name)) || empty(trim($category)) || $amount < 0 || $price <= 0 || empty(trim($supplier))) {
//        echo "Please fill in all required fields correctly.";
//        exit;
//    }
//
//    if ($category != "Electronics" && $category != "Food" && $category != "Clothing") {
//        echo "Please choose an available category.";
//        exit;
//    }

    $db = connectDatabase();

    // Update student details
    $stmt = $db->prepare("UPDATE products SET name = :name, category = :category, amount = :amount, price=:price, supplier=:supplier WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_INTEGER);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':supplier', $supplier, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    // Close the database connection
    $db->close();

    // Redirect back to the view page
    header("Location: index.php");
    exit();
} else {
    echo "Invalid request.";
}