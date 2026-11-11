<?php
require_once 'db_connection.php';

// Check if the request method is POST to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values from the POST request, with basic validation
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $amount = (int)($_POST['amount'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $supplier = $_POST['supplier'] ?? '';

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
        include 'add_product_form.php';
        exit;
    }

    // Validate required fields
//    if (empty(trim($name)) || empty(trim($category)) || $amount < 0 || $price <= 0 || empty(trim($supplier))) {
//        echo "Please fill in all required fields correctly.";
//        exit;
//    }
//
//    if ($category != "Electronics" && $category != "Food" && $category != "Clothing") {
//        echo "Please choose an available category.";
//        exit;
//    }

    // Connect to the SQLite database
    $db = connectDatabase();

    // Prepare and execute the insert statement
    $stmt = $db->prepare("INSERT INTO products (name, category, amount, price, supplier) VALUES (:name, :category, :amount, :price, :supplier)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_INTEGER);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':supplier', $supplier, SQLITE3_TEXT);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the view page
        header("Location: index.php");
    } else {
        echo "Error adding student: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
} else {
    // If not a POST request, display an error message
    echo "Invalid request method. Please submit the form to add a student.";
}