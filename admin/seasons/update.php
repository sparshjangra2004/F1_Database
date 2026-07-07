<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Season ID.");
}

$season_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Seasons WHERE season_id = ?");
$stmt->bind_param("i", $season_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Season not found.");
}

$season = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $year = intval($_POST['year']);
    $total_races = intval($_POST['total_races']);

    if ($year > 0 && $total_races > 0) {

        $update = $conn->prepare("UPDATE Seasons SET year = ?, total_races = ? WHERE season_id = ?");
        $update->bind_param("iii", $year, $total_races, $season_id);

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating season. (Year might already exist)";
        }

        $update->close();
    } else {
        $error = "Year and total races must be valid positive numbers.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Season</title>
</head>
<body>

<h1>Edit Season</h1>

<a href="list.php">Back to Seasons</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Year:</label><br>
    <input type="number" name="year"
           value="<?php echo htmlspecialchars($season['year']); ?>" required><br><br>

    <label>Total Races (planned):</label><br>
    <input type="number" name="total_races" min="1"
           value="<?php echo htmlspecialchars($season['total_races']); ?>" required><br><br>

    <button type="submit">Update Season</button>

</form>

</body>
</html>
