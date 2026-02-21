<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Sponsor ID.");
}

$sponsor_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Sponsors WHERE sponsor_id = ?");
$stmt->bind_param("i", $sponsor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Sponsor not found.");
}

$sponsor = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sponsor_name = trim($_POST['sponsor_name']);
    $industry = trim($_POST['industry']);
    $country = trim($_POST['country']);

    if ($sponsor_name && $industry && $country) {

        $update = $conn->prepare("
            UPDATE Sponsors
            SET sponsor_name = ?, industry = ?, country = ?
            WHERE sponsor_id = ?
        ");

        $update->bind_param("sssi",
            $sponsor_name,
            $industry,
            $country,
            $sponsor_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating sponsor.";
        }

        $update->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Sponsor</title>
</head>
<body>

<h1>Edit Sponsor</h1>

<a href="list.php">Back to Sponsors</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Sponsor Name:</label><br>
    <input type="text" name="sponsor_name"
           value="<?php echo $sponsor['sponsor_name']; ?>" required><br><br>

    <label>Industry:</label><br>
    <input type="text" name="industry"
           value="<?php echo $sponsor['industry']; ?>" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country"
           value="<?php echo $sponsor['country']; ?>" required><br><br>

    <button type="submit">Update Sponsor</button>

</form>

</body>
</html>