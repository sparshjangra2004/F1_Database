<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Race ID.");
}

$race_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Races WHERE race_id = ?");
$stmt->bind_param("i", $race_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting race.";
}

$stmt->close();
?>