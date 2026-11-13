<?php
session_start();

require '../helpers/db_connection.php';
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmation = $_POST['confirmation'];

    if (strlen($username) < 3 || strlen($password) < 6) {
        die('Username needs to be at least 3 characters long. The password needs to be at least 6 characters long.');
    }
    if ($password != $confirmation) {
        die("Password and confirmed password do not match. Try again");
    }

    // Check if username already exists
    $db = connectDatabase();
    $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result->fetchArray(SQLITE3_ASSOC)) {
        $db->close();
        die("Username already exists.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedConfirmation = password_hash($confirmation, PASSWORD_DEFAULT);

    $db = connectDatabase();
    $stmt = $db->prepare("INSERT INTO users (username, password, confirmation) VALUES (:username, :password, :confirmation)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(':confirmation', $hashedConfirmation, SQLITE3_TEXT);
    $result = $stmt->execute();
    try {
        echo "Registration is successful! <a href='../pages/login.php'>Login here</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Username already exist.");
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}
