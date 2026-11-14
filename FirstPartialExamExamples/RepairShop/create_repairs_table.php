<?php
include 'helpers/db_connection.php';
$db = connectDatabase();

$createTableQuery = <<<SQL
CREATE TABLE IF NOT EXISTS repairs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    model TEXT NOT NULL,
    date DATE NOT NULL,
    price FLOAT NOT NULL,
    status TEXT NOT NULL
);
SQL;

if ($db->exec($createTableQuery)) {
    echo "Table created successfully.";
} else {
    echo "Error creating table: " . $db->lastErrorMsg();
}

$db->close();