<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $code = $_POST['code'];
    $destination = $_POST['destination'];
    $departure = date($_POST['departure']);
    $seats = intval($_POST['seats']);
    $status = $_POST['status'];

    if (empty(trim($code)) || empty(trim($destination)) || empty(trim($departure)) || $seats <= 0 || empty(trim($status))
        || $departure < date('Y-m-d')) {
        echo "Please fill in all required fields correctly.";
        exit;
    }
    if ($status != "Scheduled" && $status != "Delayed" && $status != "Cancelled") {
        echo "Please fill in a valid flight status.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("UPDATE flights SET code = :code, destination = :destination, departure = :departure, seats = :seats, status = :status WHERE id = :id");
    $stmt->bindValue(':code', $code, SQLITE3_TEXT);
    $stmt->bindValue(':destination', $destination, SQLITE3_TEXT);
    $stmt->bindValue(':departure', $departure);
    $stmt->bindValue(':seats', $seats, SQLITE3_INTEGER);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    $db->close();

    header("Location: index.php");
    exit();
} else {
    echo "Invalid request.";
}