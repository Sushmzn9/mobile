<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$servername = "35.201.29.48"; // Your Cloud SQL Public IP
$username = "root"; // Replace with your DB username
$password = "db-mobile@12"; // Replace with your DB password
$dbname = "mobile";
$port = 3306;
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
