<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}


if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid Season ID.");
}

$season_id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM Seasons WHERE season_id = ?");
$stmt->bind_param("i", $season_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting season.";
}

$stmt->close();
?>
