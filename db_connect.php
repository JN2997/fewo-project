<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbafewo";

// Verbindung erstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

?>
