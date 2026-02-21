<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Team ID.");
}

$team_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Teams WHERE team_id = ?");
$stmt->bind_param("i", $team_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting team.";
}

$stmt->close();
?>