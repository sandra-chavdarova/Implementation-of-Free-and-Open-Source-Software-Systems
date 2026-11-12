<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']) ?? '';
    $destination = trim($_POST['destination']) ?? '';
    $departure = trim(date($_POST['departure'])) ?? '';
    $seats = (int)($_POST['seats'] ?? 0);
    $status = trim($_POST['status']) ?? '';

    if (empty($code) || empty($destination) || empty($departure) || $seats <= 0 || empty($status)
        || $departure < date('Y-m-d')) {
        echo "Please fill in all required fields correctly.";
        exit;
    }
    if ($status != "Scheduled" && $status != "Delayed" && $status != "Cancelled") {
        echo "Please fill in a valid flight status.";
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("INSERT INTO flights (code, destination, departure, seats, status) VALUES (:code, :destination, :departure, :seats, :status)");
    $stmt->bindValue(':code', $code, SQLITE3_TEXT);
    $stmt->bindValue(':destination', $destination, SQLITE3_TEXT);
    $stmt->bindValue(':departure', $departure);
    $stmt->bindValue(':seats', $seats, SQLITE3_INTEGER);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error adding flight: " . $db->lastErrorMsg();
    }

    $db->close();
} else {
    echo "Invalid request method. Please submit the form to add a flight.";
}