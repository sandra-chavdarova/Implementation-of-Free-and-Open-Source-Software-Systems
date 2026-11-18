<?php
include '../helpers/db_connection.php';
session_start();
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php?error=access");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $type = $_POST['type'];
    $date = date($_POST['date']);
    $days = intval($_POST['days']);
    $price = floatval($_POST['price']);
    $time = time();

    $db = connectDatabase();
    $stmt = $db->prepare("SELECT * FROM rentals WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $rental = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    if (time() > $rental['time'] + 86400) {
        echo "You are not allowed to edit after 24 hours of creation.";
        $db->close();
        exit();
    }

    $stmt = $db->prepare("UPDATE rentals SET name = :name, type = :type, date = :date, days = :days, price = :price, time = :time WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':days', $days, SQLITE3_INTEGER);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':time', $time, SQLITE3_FLOAT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}
