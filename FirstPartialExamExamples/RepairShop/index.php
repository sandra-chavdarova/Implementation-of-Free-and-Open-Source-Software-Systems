<?php
include 'helpers/db_connection.php';
session_start();
require 'helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !($user = decodeJWT($_SESSION['jwt']))) {
    header("Location: pages/login.php?error=access");
    exit;
}
$db = connectDatabase();

$query = "SELECT * FROM repairs";
$result = $db->query($query);

if (!$result) {
    die("Error fetching repairs: " . $db->lastErrorMsg());
}
$ordered = [];
$total = 0.0;
$username = $user->username;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Repairs</title>
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
</head>
<body>
<div style="display: flex; align-items: center; justify-content: space-between">
    <h1>Repairs List</h1>
    <a href="pages/add_repair_form.php">
        Add Repair
    </a>
</div>
<div class="text-center">
    <a href="handlers/logout_handler.php" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-lg">
        Logout
    </a>
</div>
<div>
    <p>Welcome, <?php echo $username ?></p>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Model</th>
        <th>Date</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($repair = $result->fetchArray(SQLITE3_ASSOC)):
            $date = htmlspecialchars($repair['date']);
            $id = htmlspecialchars($repair['id']);
            $ordered["$date - $id"] = $repair;
            if (htmlspecialchars($repair['status']) == 'Done'):
                $total += (float)htmlspecialchars($repair['price']);
            endif;
        endwhile;
        krsort($ordered);
        foreach ($ordered as $repair):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($repair['id']); ?></td>
                <td><?php echo htmlspecialchars($repair['name']); ?></td>
                <td><?php echo htmlspecialchars($repair['model']); ?></td>
                <td><?php echo htmlspecialchars($repair['date']); ?></td>
                <td><?php echo htmlspecialchars($repair['price']); ?></td>
                <td><?php echo htmlspecialchars($repair['status']); ?></td>
                <td>
                    <form action="handlers/delete_repair.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $repair['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="pages/update_repair_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $repair['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <p>Total earnings from completed repairs: <?php echo $total ?> MKD</p>
    <?php else: ?>
        <tr>
            <td colspan="5">No repairs found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>