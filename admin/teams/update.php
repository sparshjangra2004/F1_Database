<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Team ID.");
}

$team_id = intval($_GET['id']);


$stmt = $conn->prepare("SELECT * FROM Teams WHERE team_id = ?");
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Team not found.");
}

$team = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $team_name = trim($_POST['team_name']);
    $country = trim($_POST['country']);
    $team_principal = trim($_POST['team_principal']);
    $founded_year = intval($_POST['founded_year']);
    $engine_supplier = trim($_POST['engine_supplier']);

    if (!empty($team_name) && !empty($country) && !empty($team_principal)
        && !empty($founded_year) && !empty($engine_supplier)) {

        $update = $conn->prepare("
            UPDATE Teams
            SET team_name = ?, country = ?, team_principal = ?, founded_year = ?, engine_supplier = ?
            WHERE team_id = ?
        ");

        $update->bind_param("sssisi",
            $team_name,
            $country,
            $team_principal,
            $founded_year,
            $engine_supplier,
            $team_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating team.";
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
    <title>Edit Team</title>
</head>
<body>

<h1>Update Team</h1>

<a href="list.php">Back to Teams</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Team Name:</label><br>
    <input type="text" name="team_name" value="<?php echo $team['team_name']; ?>" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country" value="<?php echo $team['country']; ?>" required><br><br>

    <label>Team Principal:</label><br>
    <input type="text" name="team_principal" value="<?php echo $team['team_principal']; ?>" required><br><br>

    <label>Founded Year:</label><br>
    <input type="number" name="founded_year" value="<?php echo $team['founded_year']; ?>" required><br><br>

    <label>Engine Supplier:</label><br>
    <input type="text" name="engine_supplier" value="<?php echo $team['engine_supplier']; ?>" required><br><br>

    <button type="submit">Update Team</button>

</form>

</body>
</html>