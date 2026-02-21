<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Team_Sponsors WHERE team_sponsor_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting.";
}

$stmt->close();
?>