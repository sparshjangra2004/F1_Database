<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Editor ID.");
}

$admin_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT admin_id, username FROM Admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Editor not found.");
}

$admin = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && ($password === '' || strlen($password) >= 6)) {

        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE Admins SET username = ?, password = ? WHERE admin_id = ?");
            $update->bind_param("ssi", $username, $hash, $admin_id);
        } else {
            $update = $conn->prepare("UPDATE Admins SET username = ? WHERE admin_id = ?");
            $update->bind_param("si", $username, $admin_id);
        }

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating editor. (Username might already exist)";
        }

        $update->close();
    } else {
        $error = "Username is required and password (if changed) must be at least 6 characters.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Editor</title>
</head>
<body>

<h1>Edit Editor</h1>

<a href="list.php">Back to Editors</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Username:</label><br>
    <input type="text" name="username"
           value="<?php echo htmlspecialchars($admin['username']); ?>" required><br><br>

    <label>New Password:</label><br>
    <input type="password" name="password" minlength="6" placeholder="Leave blank to keep current password"><br><br>

    <button type="submit">Update Editor</button>

</form>

</body>
</html>
