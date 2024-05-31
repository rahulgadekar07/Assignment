<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "animal_db";

// Creating connection to Database:-
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection errors if any:-
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
