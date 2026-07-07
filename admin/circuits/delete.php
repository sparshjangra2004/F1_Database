<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}


if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid Circuit ID.");
}

$circuit_id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM Circuits WHERE circuit_id = ?");
$stmt->bind_param("i", $circuit_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting circuit. It may still be referenced by a race.";
}

$stmt->close();
?>
