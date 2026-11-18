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
    <title>Add Rental</title>
</head>
<body>
<form action="../handlers/add_rental.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br/>

    <p>Type:</p>
    <label for="type">
        <input type="radio" name="type" value="City" required>
        City
    </label><br>
    <label for="type">
        <input type="radio" name="type" value="Mountain" required>
        Mountain
    </label><br>
    <label for="type">
        <input type="radio" name="type" value="Electric" required>
        Electric
    </label>
    <br/>

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required>
    <br/>

    <label for="days">Number of days:</label>
    <input type="number" name="days" id="days" required>
    <br/>

    <label for="price">Price per Day:</label>
    <input type="number" name="price" id="price" required>
    <br/>

    <button type="submit">Add Rental</button>
</form>
</body>