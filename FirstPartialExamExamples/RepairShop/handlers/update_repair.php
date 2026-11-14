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
    $model = $_POST['model'];
    $date = date($_POST['date']);
    $price = floatval($_POST['price']);
    $status = $_POST['status'];

    $db = connectDatabase();

    $stmt = $db->prepare("UPDATE repairs SET name = :name, model = :model, date = :date, price = :price, status = :status WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':model', $model, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}
