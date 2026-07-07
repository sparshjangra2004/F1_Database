<?php

mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$username = "root";
$password = "";
$database = "F1_championship";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("DB connection failed: " . $conn->connect_error);
    die("Service unavailable. Please try again later.");
}


$conn->set_charset("utf8mb4");

