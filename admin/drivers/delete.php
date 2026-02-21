<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Driver ID.");
}

$driver_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Drivers WHERE driver_id = ?");
$stmt->bind_param("i", $driver_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting driver.";
}

$stmt->close();
?>