<?php
// Database connection details
$servername = "i5lphs.h.filess.io";
$username = "gkjw_fundraising_exceptcake";
$password = "e90e1ab8cd779a66c31d91a8ae6cb933197d59cb";
$dbname = "gkjw_fundraising_exceptcake";
$port = 3307;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
