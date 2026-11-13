<?php
include '../helpers/db_connection.php';
session_start();
include '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $type = $_POST['type'] ?? '';
    $price = (float)($_POST['price'] ?? 0);

    if (empty($name) || empty($date) || empty($type) || $price <= 0) {
        echo "Please fill in all required fields correctly.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("INSERT INTO subscriptions (name, date, type, price) VALUES (:name, :date, :type, :price)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);

    if ($stmt->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error adding subscription: " . $db->lastErrorMsg();
    }

    $db->close();
} else {
    echo "Invalid request method. Please submit the form to add a subscription.";
}