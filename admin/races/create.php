<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';


$seasons = $conn->query("SELECT season_id, year FROM Seasons ORDER BY year DESC");
$circuits = $conn->query("SELECT circuit_id, circuit_name FROM Circuits ORDER BY circuit_name ASC");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $race_name = trim($_POST['race_name']);
    $race_date = $_POST['race_date'];
    $season_id = intval($_POST['season_id']);
    $circuit_id = intval($_POST['circuit_id']);
    $weather = $_POST['weather_condition'];
    $laps = intval($_POST['laps']);

    if ($race_name && $race_date && $season_id > 0 &&
        $circuit_id > 0 && $weather && $laps > 0) {

        $stmt = $conn->prepare("
            INSERT INTO Races
            (race_name, race_date, season_id, circuit_id, weather_condition, laps)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssiisi",
            $race_name,
            $race_date,
            $season_id,
            $circuit_id,
            $weather,
            $laps
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating race.";
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
    <title>Add Race</title>
</head>
<body>

<h1>Add New Race</h1>

<a href="list.php">Back to Races</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Race Name:</label><br>
    <input type="text" name="race_name" required><br><br>

    <label>Race Date:</label><br>
    <input type="date" name="race_date" required><br><br>

    <label>Season:</label><br>
    <select name="season_id" required>
        <option value="">-- Select Season --</option>
        <?php while ($season = $seasons->fetch_assoc()): ?>
            <option value="<?php echo $season['season_id']; ?>">
                <?php echo $season['year']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Circuit:</label><br>
    <select name="circuit_id" required>
        <option value="">-- Select Circuit --</option>
        <?php while ($circuit = $circuits->fetch_assoc()): ?>
            <option value="<?php echo $circuit['circuit_id']; ?>">
                <?php echo $circuit['circuit_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Weather Condition:</label><br>
    <select name="weather_condition" required>
        <option value="Dry">Dry</option>
        <option value="Wet">Wet</option>
        <option value="Mixed">Mixed</option>
    </select><br><br>

    <label>Laps:</label><br>
    <input type="number" name="laps" required><br><br>

    <button type="submit">Add Race</button>

</form>

</body>
</html>