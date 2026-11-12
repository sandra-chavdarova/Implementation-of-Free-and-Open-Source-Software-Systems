<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flight</title>
</head>
<body>
<form action="add_flight.php" method="POST">
    <label for="code">Code:</label>
    <input type="text" name="code" id="code" required>
    <br/>

    <label for="destination">Destination:</label>
    <input type="text" name="destination" id="destination" required>
    <br/>

    <label for="departure">Departure Date:</label>
    <input type="date" name="departure" id="departure" required>
    <br/>

    <label for="seats">Seats:</label>
    <input type="number" name="seats" id="seats" required>
    <br/>

    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option>Scheduled</option>
        <option>Delayed</option>
        <option>Cancelled</option>
    </select>
    <br/>

    <button type="submit">Add Flight</button>
</form>
</body>