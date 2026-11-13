<?php
require 'helpers/db_connection.php';

$db = connectDatabase();

if ($db->exec("DELETE FROM users")) {
    echo "All users have been deleted successfully.";
} else {
    echo "Error: " . $db->lastErrorMsg();
}

$db->close();