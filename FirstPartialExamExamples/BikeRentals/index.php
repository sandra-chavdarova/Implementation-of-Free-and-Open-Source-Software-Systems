<?php
include 'helpers/db_connection.php';
session_start();
require 'helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !($user = decodeJWT($_SESSION['jwt']))) {
    header("Location: pages/login.php?error=access");
    exit;
}

$db = connectDatabase();

$query = "SELECT * FROM rentals ORDER BY date DESC, time DESC";
$result = $db->query($query);

if (!$result) {
    die("Error fetching rentals: " . $db->lastErrorMsg());
}

$total = 0;
$username = $user->username;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rentals</title>
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
    <h1>Rental List</h1>
    <a href="pages/add_rental_form.php">
        Add Rental
    </a>
</div>
<div class="text-center">
    <a href="handlers/logout_handler.php" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-lg">
        Logout
    </a>
</div>
<p>Welcome, <?php echo $username ?></p>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Days</th>
        <th>Total price</th>
        <th>Status</th>
        <th>Time created</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($rental = $result->fetchArray(SQLITE3_ASSOC)):
            $endDate = htmlspecialchars($rental['date']);
            $days = htmlspecialchars($rental['days']);
            $price = htmlspecialchars($rental['price']);
            $endDate = date('Y-m-d', strtotime($endDate . "+ $days days"));
            $status = 'Returned';
            if ($endDate >= date('Y-m-d')):
                $status = 'Active';
            endif;

            if ($status == 'Returned'):
                $total += $days * $price;
            endif;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($rental['id']); ?></td>
                <td><?php echo htmlspecialchars($rental['name']); ?></td>
                <td><?php echo htmlspecialchars($rental['type']); ?></td>
                <td><?php echo date('d.m.Y', strtotime(htmlspecialchars($rental['date']))); ?></td>
                <td><?php echo date('d.m.Y', strtotime($endDate)); ?></td>
                <td><?php echo $days; ?></td>
                <td><?php echo $days * $price; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo date("Y-m-d H:i:s", (int)htmlspecialchars($rental['time'])); ?></td>
                <td>
                    <form action="handlers/delete_rental.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $rental['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="pages/update_rental_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $rental['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No rentals found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<p>Total earnings from returned rentals: <?php echo $total ?> MKD</p>
</body>
</html>