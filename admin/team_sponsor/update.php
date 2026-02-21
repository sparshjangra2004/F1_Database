<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Sponsorship ID.");
}

$team_sponsor_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT ts.team_sponsor_id,
           ts.contract_start,
           ts.contract_end,
           t.team_name,
           s.sponsor_name
    FROM Team_Sponsors ts
    JOIN Teams t ON ts.team_id = t.team_id
    JOIN Sponsors s ON ts.sponsor_id = s.sponsor_id
    WHERE ts.team_sponsor_id = ?
");

$stmt->bind_param("i", $team_sponsor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Sponsorship not found.");
}

$record = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contract_start = $_POST['contract_start'];
    $contract_end   = $_POST['contract_end'];

    if ($contract_start && $contract_end) {

        $update = $conn->prepare("
            UPDATE Team_Sponsors
            SET contract_start = ?,
                contract_end = ?
            WHERE team_sponsor_id = ?
        ");

        $update->bind_param("ssi",
            $contract_start,
            $contract_end,
            $team_sponsor_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating contract.";
        }

        $update->close();
    } else {
        $error = "Both dates are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Sponsorship</title>
</head>
<body>

<h1>Edit Sponsorship</h1>

<a href="list.php">Back</a>

<hr>

<p>
<b>Team:</b> <?php echo $record['team_name']; ?><br>
<b>Sponsor:</b> <?php echo $record['sponsor_name']; ?>
</p>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Contract Start:</label><br>
    <input type="date" name="contract_start"
           value="<?php echo $record['contract_start']; ?>" required><br><br>

    <label>Contract End:</label><br>
    <input type="date" name="contract_end"
           value="<?php echo $record['contract_end']; ?>" required><br><br>

    <button type="submit">Update Contract</button>

</form>

</body>
</html>