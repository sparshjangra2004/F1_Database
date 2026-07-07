<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $year = intval($_POST['year']);
    $total_races = intval($_POST['total_races']);

    if ($year > 0 && $total_races > 0) {

        $stmt = $conn->prepare("INSERT INTO Seasons (year, total_races) VALUES (?, ?)");
        $stmt->bind_param("ii", $year, $total_races);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating season. (Year might already exist)";
        }

        $stmt->close();
    } else {
        $error = "Year and total races must be valid positive numbers.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Season</title>
</head>
<body>

<h1>Add New Season</h1>

<a href="list.php">Back to Seasons</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Year:</label><br>
    <input type="number" name="year" required><br><br>

    <label>Total Races (planned):</label><br>
    <input type="number" name="total_races" min="1" required><br><br>

    <button type="submit">Add Season</button>

</form>

</body>
</html>
