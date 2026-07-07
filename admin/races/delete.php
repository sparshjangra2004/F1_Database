<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}


if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid Race ID.");
}

$race_id = intval($_POST['id']);

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