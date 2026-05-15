<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bazar_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}
session_start();
?>