<?php
include '../helpers/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $date = date($_POST['date']) ?? '';
    $amount = (float) ($_POST['amount'] ?? 0);
    $type = $_POST['type'] ?? '';

    if (empty(trim($name)) || empty(trim($date)) || $amount <= 0 || empty(trim($type))) {
        echo "Please fill in all required fields correctly.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("INSERT INTO expenses (name, date, amount, type) VALUES (:name, :date, :amount, :type)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);

    if ($stmt->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error adding expense: " . $db->lastErrorMsg();
    }

    $db->close();
} else {
    echo "Invalid request method. Please submit the form to add an expense.";
}