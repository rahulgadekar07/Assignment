<?php
include 'db.php';

// Reset visitor count in the database
$conn->query("UPDATE visitor_count SET count = 0");

echo "Visitor count has been reset.";
?>
