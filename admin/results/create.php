<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

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

        $stmt = $conn->prepare("
            INSERT INTO Results
            (race_id, driver_id, grid_position, finish_position, points, fastest_lap, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("iiiidis",
            $race_id,
            $driver_id,
            $grid_position,
            $finish_position,
            $points,
            $fastest_lap,
            $status
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating result. (This driver may already have a result for this race)";
        }

        $stmt->close();
    } else {
        $error = "All fields must be filled correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Result</title>
</head>
<body>

<h1>Add New Result</h1>

<a href="list.php">Back to Results</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Race:</label><br>
    <select name="race_id" required>
        <option value="">-- Select Race --</option>
        <?php while ($race = $races->fetch_assoc()): ?>
            <option value="<?php echo $race['race_id']; ?>">
                <?php echo htmlspecialchars($race['race_name'] . " (" . $race['race_date'] . ")"); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Driver:</label><br>
    <select name="driver_id" required>
        <option value="">-- Select Driver --</option>
        <?php while ($driver = $drivers->fetch_assoc()): ?>
            <option value="<?php echo $driver['driver_id']; ?>">
                <?php echo htmlspecialchars($driver['first_name'] . " " . $driver['last_name']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Grid Position:</label><br>
    <input type="number" name="grid_position" min="1" required><br><br>

    <label>Finish Position:</label><br>
    <input type="number" name="finish_position" min="1" required><br><br>

    <label>Points:</label><br>
    <input type="number" step="0.1" name="points" min="0" required><br><br>

    <label>
        <input type="checkbox" name="fastest_lap"> Fastest Lap
    </label><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="Finished">Finished</option>
        <option value="DNF">DNF</option>
        <option value="DSQ">DSQ</option>
    </select><br><br>

    <button type="submit">Add Result</button>

</form>

</body>
</html>
