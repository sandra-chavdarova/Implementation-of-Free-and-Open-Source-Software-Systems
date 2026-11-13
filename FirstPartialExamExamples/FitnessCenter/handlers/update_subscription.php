<?php
include '../helpers/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $price = floatval($_POST['price']);

    $db = connectDatabase();

    $stmt = $db->prepare("UPDATE subscriptions SET name = :name, date = :date, type = :type, price = :price WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}
