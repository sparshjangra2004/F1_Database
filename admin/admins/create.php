<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && strlen($password) >= 6) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating editor. (Username might already exist)";
        }

        $stmt->close();
    } else {
        $error = "Username is required and password must be at least 6 characters.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Editor</title>
</head>
<body>

<h1>Add New Editor</h1>

<a href="list.php">Back to Editors</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" minlength="6" required><br><br>

    <button type="submit">Add Editor</button>

</form>

</body>
</html>
