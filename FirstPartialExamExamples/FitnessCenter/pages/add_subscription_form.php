<?php
session_start();
include '../helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: ../pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subscription</title>
</head>
<body>
<form action="../handlers/add_subscription.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br/>

    <label for="type">Type:</label>
    <select name="type" id="type" required>
        <option>Monthly</option>
        <option>Yearly</option>
    </select>
    <br/>

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required>
    <br/>

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" required>
    <br/>

    <button type="submit">Add Subscription</button>
</form>
</body>