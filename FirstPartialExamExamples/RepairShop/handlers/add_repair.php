<?php
include '../helpers/db_connection.php';
session_start();
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php?error=access");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $model = $_POST['model'] ?? '';
    $date = date($_POST['date']) ?? '';
    $price = (float)($_POST['price'] ?? 0);
    $status = $_POST['status'] ?? '';

    if (empty(trim($name)) || empty(trim($model)) || empty(trim($date)) || empty(trim($status)) || $price <= 0) {
        echo "Please fill in all required fields correctly.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("INSERT INTO repairs (name, model, date, price, status) VALUES (:name, :model, :date, :price, :status)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':model', $model, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);

    if ($stmt->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error adding repair: " . $db->lastErrorMsg();
    }

    $db->close();
} else {
    echo "Invalid request method. Please submit the form to add a repair.";
}
