<?php
session_start();
require '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: login.php?error=access");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Repair</title>
</head>
<body>
<form action="../handlers/add_repair.php" method="POST">
    <label for="name">Client Name:</label>
    <input type="text" name="name" id="name" required>
    <br />

    <label for="model">Model:</label>
    <input type="text" name="model" id="model" required>
    <br />

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required>
    <br />

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" required>
    <br />

    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option>Received</option>
        <option>In Progress</option>
        <option>Done</option>
    </select>
    <br />

    <button type="submit">Add Repair</button>
</form>
</body>