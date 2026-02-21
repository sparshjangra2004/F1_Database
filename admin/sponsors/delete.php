<?php
require_once '../auth.php';
require_once '../../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Sponsor ID.");
}

$sponsor_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Sponsors WHERE sponsor_id = ?");
$stmt->bind_param("i", $sponsor_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting sponsor.";
}

$stmt->close();
?>