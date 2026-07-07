<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}


if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("Invalid Editor ID.");
}

$admin_id = intval($_POST['id']);

if ($admin_id === intval($_SESSION['admin_id'])) {
    die("You cannot delete your own account while logged in.");
}

$count = $conn->query("SELECT COUNT(*) AS total FROM Admins")->fetch_assoc()['total'];

if ($count <= 1) {
    die("Cannot delete the last remaining editor account.");
}

$stmt = $conn->prepare("DELETE FROM Admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit();
} else {
    echo "Error deleting editor.";
}

$stmt->close();
?>
