<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $team_name = trim($_POST['team_name']);
    $country = trim($_POST['country']);
    $team_principal = trim($_POST['team_principal']);
    $founded_year = intval($_POST['founded_year']);
    $engine_supplier = trim($_POST['engine_supplier']);

    if (!empty($team_name) && !empty($country) && !empty($team_principal)
        && !empty($founded_year) && !empty($engine_supplier)) {

        $stmt = $conn->prepare("
            INSERT INTO Teams 
            (team_name, country, team_principal, founded_year, engine_supplier)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("sssis",
            $team_name,
            $country,
            $team_principal,
            $founded_year,
            $engine_supplier
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error inserting team.";
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
    <title>Add New Team</title>
</head>
<body>

<h1>Add New Team</h1>

<a href="list.php">Back to Teams</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Team Name:</label><br>
    <input type="text" name="team_name" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country" required><br><br>

    <label>Team Principal:</label><br>
    <input type="text" name="team_principal" required><br><br>

    <label>Founded Year:</label><br>
    <input type="number" name="founded_year" required><br><br>

    <label>Engine Supplier:</label><br>
    <input type="text" name="engine_supplier" required><br><br>

    <button type="submit">Add Team</button>

</form>

</body>
</html>