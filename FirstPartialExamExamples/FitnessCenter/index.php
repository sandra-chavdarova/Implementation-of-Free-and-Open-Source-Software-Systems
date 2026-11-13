<?php
include 'helpers/db_connection.php';

session_start();
include 'helpers/jwt_helper.php';

if (!isset($_SESSION['jwt']) || !($user = decodeJWT($_SESSION['jwt']))) {
    header("Location: pages/login.php");
    exit;
}

$username = $user->username;
$userId = $user->userId;

$db = connectDatabase();

$query = "SELECT * FROM subscriptions";
$result = $db->query($query);

if (!$result) {
    die("Error fetching subscriptions: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Subscriptions</title>
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
    <h1>Subscriptions List</h1>
    <a href="pages/add_subscription_form.php">
        Add Subscription
    </a>
    <div class="text-center">
        <a href="handlers/logout_handler.php" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-lg">
            Logout
        </a>
    </div>
</div>
<div>
    <p>Welcome, <?php echo htmlspecialchars($username)?></p>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($subscription = $result->fetchArray(SQLITE3_ASSOC)):
            $endDate = htmlspecialchars($subscription['date']);
            if (htmlspecialchars($subscription['type']) == 'Monthly'):
                $endDate = date('Y-m-d', strtotime($endDate . ' + 30 days'));
            else:
                $endDate = date('Y-m-d', strtotime($endDate . ' + 1 year'));
            endif;

            $status = 'Expired';
            if ($endDate >= date('Y-m-d')):
                $status = 'Active';
            endif;

            ?>
            <tr>
                <td><?php echo htmlspecialchars($subscription['id']); ?></td>
                <td><?php echo htmlspecialchars($subscription['name']); ?></td>
                <td><?php echo htmlspecialchars($subscription['type']); ?></td>
                <td><?php echo htmlspecialchars($subscription['date']); ?></td>
                <td><?php echo $endDate; ?></td>
                <td><?php echo htmlspecialchars($subscription['price']); ?></td>
                <td><?php echo $status; ?></td>
                <td>
                    <form action="handlers/delete_subscription.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $subscription['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="pages/update_subscription_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $subscription['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No subscriptions found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>