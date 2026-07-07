<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Result ID.");
}

$result_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Results WHERE result_id = ?");
$stmt->bind_param("i", $result_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Result not found.");
}

$record = $res->fetch_assoc();
$stmt->close();

$races = $conn->query("SELECT race_id, race_name, race_date FROM Races ORDER BY race_date DESC");
$drivers = $conn->query("SELECT driver_id, first_name, last_name FROM Drivers ORDER BY last_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $race_id = intval($_POST['race_id']);
    $driver_id = intval($_POST['driver_id']);
    $grid_position = intval($_POST['grid_position']);
    $finish_position = intval($_POST['finish_position']);
    $points = floatval($_POST['points']);
    $fastest_lap = isset($_POST['fastest_lap']) ? 1 : 0;
    $status = $_POST['status'];

    if ($race_id > 0 && $driver_id > 0 && $grid_position > 0 &&
        $finish_position > 0 && $points >= 0 && $status) {

        $update = $conn->prepare("
            UPDATE Results
            SET race_id = ?,
                driver_id = ?,
                grid_position = ?,
                finish_position = ?,
                points = ?,
                fastest_lap = ?,
                status = ?
            WHERE result_id = ?
        ");

        $update->bind_param("iiiidisi",
            $race_id,
            $driver_id,
            $grid_position,
            $finish_position,
            $points,
            $fastest_lap,
            $status,
            $result_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating result. (This driver may already have a result for this race)";
        }

        $update->close();
    } else {
        $error = "All fields must be filled correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Result</title>
</head>
<body>

<h1>Edit Result</h1>

<a href="list.php">Back to Results</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Race:</label><br>
    <select name="race_id" required>
        <?php while ($race = $races->fetch_assoc()): ?>
            <option value="<?php echo $race['race_id']; ?>"
                <?php if ($record['race_id'] == $race['race_id']) echo "selected"; ?>>
                <?php echo htmlspecialchars($race['race_name'] . " (" . $race['race_date'] . ")"); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Driver:</label><br>
    <select name="driver_id" required>
        <?php while ($driver = $drivers->fetch_assoc()): ?>
            <option value="<?php echo $driver['driver_id']; ?>"
                <?php if ($record['driver_id'] == $driver['driver_id']) echo "selected"; ?>>
                <?php echo htmlspecialchars($driver['first_name'] . " " . $driver['last_name']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Grid Position:</label><br>
    <input type="number" name="grid_position" min="1"
           value="<?php echo htmlspecialchars($record['grid_position']); ?>" required><br><br>

    <label>Finish Position:</label><br>
    <input type="number" name="finish_position" min="1"
           value="<?php echo htmlspecialchars($record['finish_position']); ?>" required><br><br>

    <label>Points:</label><br>
    <input type="number" step="0.1" name="points" min="0"
           value="<?php echo htmlspecialchars($record['points']); ?>" required><br><br>

    <label>
        <input type="checkbox" name="fastest_lap" <?php if ($record['fastest_lap']) echo "checked"; ?>> Fastest Lap
    </label><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="Finished" <?php if ($record['status'] == 'Finished') echo "selected"; ?>>Finished</option>
        <option value="DNF" <?php if ($record['status'] == 'DNF') echo "selected"; ?>>DNF</option>
        <option value="DSQ" <?php if ($record['status'] == 'DSQ') echo "selected"; ?>>DSQ</option>
    </select><br><br>

    <button type="submit">Update Result</button>

</form>

</body>
</html>
