<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sponsor_name = trim($_POST['sponsor_name']);
    $industry = trim($_POST['industry']);
    $country = trim($_POST['country']);

    if ($sponsor_name && $industry && $country) {

        $stmt = $conn->prepare("
            INSERT INTO Sponsors
            (sponsor_name, industry, country)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("sss",
            $sponsor_name,
            $industry,
            $country
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating sponsor. (Name might already exist)";
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sponsor</title>
</head>
<body>

<h1>Add New Sponsor</h1>

<a href="list.php">Back to Sponsors</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Sponsor Name:</label><br>
    <input type="text" name="sponsor_name" required><br><br>

    <label>Industry:</label><br>
    <input type="text" name="industry" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country" required><br><br>

    <button type="submit">Add Sponsor</button>

</form>

</body>
</html>