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
    $db = connectDatabase();

    $stmt = $db->prepare("DELETE FROM repairs WHERE id = :id AND status <> 'In Progress'");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    if ($db->changes() == 0) {
        echo "A repair that is In Progress cannot be deleted";
        $db->close();
        exit();
    }

    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}