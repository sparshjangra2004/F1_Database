<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$teams = $conn->query("SELECT team_id, team_name FROM Teams ORDER BY team_name");
$sponsors = $conn->query("SELECT sponsor_id, sponsor_name FROM Sponsors ORDER BY sponsor_name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $team_id = intval($_POST['team_id']);
    $sponsor_id = intval($_POST['sponsor_id']);
    $contract_start = $_POST['contract_start'];
    $contract_end = $_POST['contract_end'];

    if ($team_id && $sponsor_id && $contract_start && $contract_end) {

        $stmt = $conn->prepare("
            INSERT INTO Team_Sponsors
            (team_id, sponsor_id, contract_start, contract_end)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("iiss",
            $team_id,
            $sponsor_id,
            $contract_start,
            $contract_end
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating sponsorship (maybe duplicate).";
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
    <title>Add Sponsorship</title>
</head>
<body>

<h1>Add Sponsorship</h1>

<a href="list.php">Back</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Team:</label><br>
    <select name="team_id" required>
        <option value="">Select Team</option>
        <?php while ($team = $teams->fetch_assoc()): ?>
            <option value="<?php echo $team['team_id']; ?>">
                <?php echo $team['team_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Sponsor:</label><br>
    <select name="sponsor_id" required>
        <option value="">Select Sponsor</option>
        <?php while ($s = $sponsors->fetch_assoc()): ?>
            <option value="<?php echo $s['sponsor_id']; ?>">
                <?php echo $s['sponsor_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Contract Start:</label><br>
    <input type="date" name="contract_start" required><br><br>

    <label>Contract End:</label><br>
    <input type="date" name="contract_end" required><br><br>

    <button type="submit">Create Sponsorship</button>

</form>

</body>
</html>