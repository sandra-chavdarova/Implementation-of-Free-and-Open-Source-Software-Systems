<?php
session_start();

require '../helpers/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmation = $_POST['confirmation'];

    if (strlen($password) < 6) {
        die('Password needs to have at least 6 characters.');
    }

    if ($password != $confirmation) {
        die('Passwords do not match.');
    }

    $db = connectDatabase();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result->fetchArray(SQLITE3_ASSOC)) {
        $db->close();
        die("Username already exists.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedConfirmation = password_hash($confirmation, PASSWORD_DEFAULT);


    $stmt = $db->prepare("INSERT INTO users (username, password, confirmation) VALUES (:username, :password, :confirmation)");
    try {
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(':confirmation', $hashedConfirmation, SQLITE3_TEXT);
        $result = $stmt->execute();

        echo "Registration is successful! <br><br><a href='../pages/login.php'>Login here</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Username already exists.");
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}