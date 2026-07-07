<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}


if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid Result ID.");
}

$result_id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM Results WHERE result_id = ?");
$stmt->bind_param("i", $result_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting result.";
}

$stmt->close();
?>
