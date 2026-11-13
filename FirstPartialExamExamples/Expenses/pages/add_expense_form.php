<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
</head>
<body>
<form action="../handlers/add_expense.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br/>

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required>
    <br/>

    <label for="amount">Amount:</label>
    <input type="number" name="amount" id="amount" required>
    <br/>

    <label for="type">Type:</label>
    <select name="type" id="type" required>
        <option>Cash</option>
        <option>Card</option>
    </select>
    <br/>

    <button type="submit">Add Expense</button>
</form>
</body>