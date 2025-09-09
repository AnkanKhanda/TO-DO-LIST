<?php
$host = "localhost";
$user = "root"; // change if different
$pass = "";     // change if you have password
$dbname = "todo";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
