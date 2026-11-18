<?php
function connectDatabase(): SQLite3 {
    return new SQLite3(__DIR__ . '/../database/rentals_db.sqlite');
}