<?php
include 'db_connection.php';

$db = connectDatabase();

$query = "SELECT * FROM flights";
$result = $db->query($query);

if (!$result) {
    die("Error fetching flights: " . $db->lastErrorMsg());
}

$flights = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Flights</title>
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
    <h1>Flight List</h1>
    <a href="add_flight_form.php">
        Add Flight
    </a>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Destination</th>
        <th>Departure Date</th>
        <th>Number of seats</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($flight = $result->fetchArray(SQLITE3_ASSOC)):
            $date = htmlspecialchars($flight['departure']);
            $id = htmlspecialchars($flight['id']);
            $flights["$date - $id"] = $flight;
        endwhile;
        ksort($flights);
        foreach ($flights as $flight):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($flight['id']); ?></td>
                <td><?php echo htmlspecialchars($flight['code']); ?></td>
                <td><?php echo htmlspecialchars($flight['destination']); ?></td>
                <td><?php echo htmlspecialchars(date('d.m.Y', strtotime($flight['departure']))); ?></td>
                <td><?php echo htmlspecialchars($flight['seats']); ?></td>
                <td><?php echo htmlspecialchars($flight['status']); ?></td>
                <td>
                    <form action="delete_flight.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $flight['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="update_flight_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $flight['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No flights found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>