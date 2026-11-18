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

    $stmt = $db->prepare("DELETE FROM rentals WHERE id = :id
                                AND (
                                    date(date, '+'||days||' days')<:today
                                )
");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':today', date('Y-m-d'));
    $stmt->execute();

    if ($db->changes() == 0) {
        echo "An active rental cannot be deleted.";
        $db->close();
        exit();
    }

    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}
