<?php
// htdocs/includes/db.php

$host = 'sql107.infinityfree.com';
$dbname = 'if0_41976200_database'; // ⚠️ Make sure XXX matches your exact database name suffix!
$username = 'if0_41976200';
$password = 'abbookSHOP'; 

// Create a native MySQLi connection object matching your app syntax
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection stability
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure character set supports modern formatting and symbols cleanly
$conn->set_charset("utf8mb4");