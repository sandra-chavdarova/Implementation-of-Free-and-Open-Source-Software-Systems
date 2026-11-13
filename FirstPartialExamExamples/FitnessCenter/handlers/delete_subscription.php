<?php
include '../helpers/db_connection.php';

session_start();
include '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("DELETE FROM subscriptions WHERE id = :id
                                AND (
                                    (type = 'Monthly' AND date(date, '+30 days') < :today)
                                    OR
                                    (type = 'Yearly' AND date(date, '+1 year') < :today)
    )");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':today', date('Y-m-d'));
    $result = $stmt->execute();

    if (!$result) {
        echo "Error deleting subscription.";
        $db->close();
        exit();
    }

    if ($db->changes() == 0) {
        echo "An active subscription cannot be deleted.";
        $db->close();
        exit();
    }
    $db->close();

    header("Location: ../index.php");
    exit();
} else {
    echo "Invalid request.";
}
