<?php

$servername = "localhost";
$username = "root";
$password = "root";
$db = "legekontor";


// Connect to DB
$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error)
{
    die("Connection Failed: " . $con->connect_error);
}

?>