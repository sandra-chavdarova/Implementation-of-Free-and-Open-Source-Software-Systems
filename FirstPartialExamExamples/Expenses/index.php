<?php
include 'helpers/db_connection.php';
session_start();
include "helpers/jwt_helper.php";

if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
    header("Location: pages/login.php");
    exit;
}

$db = connectDatabase();

$query = "SELECT * FROM expenses";
$result = $db->query($query);

if (!$result) {
    die("Error fetching expenses: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Expenses</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div style="display: flex; align-items: center; justify-content: space-between">
    <p class="text-center text-lg mb-6">Your login was successful. Welcome!</p>
    <div class="text-center">
        <a href="handlers/logout_handler.php" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-lg">
            Logout
        </a>
    </div>
    <h1>Expense List</h1>
    <a href="pages/add_expense_form.php">
        Add Expense
    </a>
</div>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Type of Payment</th>
        <th>Actions</th>
        <th>Note</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($expense = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($expense['name']); ?></td>
                <td><?php echo htmlspecialchars($expense['date']); ?></td>
                <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                <td><?php echo htmlspecialchars($expense['type']); ?></td>
                <td>
                    <form action="handlers/delete_expense.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="pages/update_expense_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td><?php echo htmlspecialchars($expense['amount']) > 100 ? 'Cannot delete this expense' : ''; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No expenses found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>