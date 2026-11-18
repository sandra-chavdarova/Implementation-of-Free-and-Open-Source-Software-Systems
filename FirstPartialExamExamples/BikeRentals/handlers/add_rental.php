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
    $type = $_POST['type'] ?? '';
    $date = date($_POST['date']) ?? '';
    $days = (int)($_POST['days'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $time = time();

    if (empty(trim($name)) || empty(trim($type)) || $days <= 0 || $price <= 0 || empty(trim($date))) {
        echo "Please fill in all required fields correctly.";
        exit;
    }

    if ($type != 'City' && $type != 'Mountain' && $type != 'Electric') {
        echo "Please fill in a valid type.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("INSERT INTO rentals (name, type, date, days, price, time) VALUES (:name, :type, :date, :days, :price, :time)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':days', $days, SQLITE3_INTEGER);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':time', $time);

    if ($stmt->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error adding rental: " . $db->lastErrorMsg();
    }

    $db->close();
} else {
    echo "Invalid request method. Please submit the form to add a rental.";
}
