<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Race ID.");
}

$race_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Races WHERE race_id = ?");
$stmt->bind_param("i", $race_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Race not found.");
}

$race = $result->fetch_assoc();
$stmt->close();

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

        $update = $conn->prepare("
            UPDATE Races
            SET race_name = ?, 
                race_date = ?, 
                season_id = ?, 
                circuit_id = ?, 
                weather_condition = ?, 
                laps = ?
            WHERE race_id = ?
        ");

        $update->bind_param("ssiissi",
            $race_name,
            $race_date,
            $season_id,
            $circuit_id,
            $weather,
            $laps,
            $race_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating race.";
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
    <title>Edit Race</title>
</head>
<body>

<h1>Edit Race</h1>

<a href="list.php">Back to Races</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Race Name:</label><br>
    <input type="text" name="race_name"
           value="<?php echo $race['race_name']; ?>" required><br><br>

    <label>Race Date:</label><br>
    <input type="date" name="race_date"
           value="<?php echo $race['race_date']; ?>" required><br><br>

    <label>Season:</label><br>
    <select name="season_id" required>
        <?php while ($season = $seasons->fetch_assoc()): ?>
            <option value="<?php echo $season['season_id']; ?>"
                <?php if ($race['season_id'] == $season['season_id']) echo "selected"; ?>>
                <?php echo $season['year']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Circuit:</label><br>
    <select name="circuit_id" required>
        <?php while ($circuit = $circuits->fetch_assoc()): ?>
            <option value="<?php echo $circuit['circuit_id']; ?>"
                <?php if ($race['circuit_id'] == $circuit['circuit_id']) echo "selected"; ?>>
                <?php echo $circuit['circuit_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Weather Condition:</label><br>
    <select name="weather_condition" required>
        <option value="Dry" <?php if ($race['weather_condition'] == 'Dry') echo "selected"; ?>>Dry</option>
        <option value="Wet" <?php if ($race['weather_condition'] == 'Wet') echo "selected"; ?>>Wet</option>
        <option value="Mixed" <?php if ($race['weather_condition'] == 'Mixed') echo "selected"; ?>>Mixed</option>
    </select><br><br>

    <label>Laps:</label><br>
    <input type="number" name="laps"
           value="<?php echo $race['laps']; ?>" required><br><br>

    <button type="submit">Update Race</button>

</form>

</body>
</html>