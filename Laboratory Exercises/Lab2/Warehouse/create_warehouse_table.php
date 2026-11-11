<?php
include "db_connection.php";
$db = connectDatabase();

$createTableQuery = <<<SQL
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    amount INTEGER NOT NULL,
    price FLOAT NOT NULL,
    supplier TEXT NOT NULL
);
SQL;

if ($db->exec($createTableQuery)) {
    echo "Table created successfully.";
} else {
    echo "Error creating table: " . $db->lastErrorMsg();
}

$db->close();