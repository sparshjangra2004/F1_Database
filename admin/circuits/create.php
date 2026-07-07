<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $circuit_name = trim($_POST['circuit_name']);
    $country = trim($_POST['country']);
    $length_km = $_POST['length_km'] !== '' ? floatval($_POST['length_km']) : NULL;
    $turns = $_POST['turns'] !== '' ? intval($_POST['turns']) : NULL;
    $lap_record = $_POST['lap_record'] !== '' ? $_POST['lap_record'] : NULL;

    if ($circuit_name) {

        $stmt = $conn->prepare("
            INSERT INTO Circuits (circuit_name, country, length_km, turns, lap_record)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssdis",
            $circuit_name,
            $country,
            $length_km,
            $turns,
            $lap_record
        );

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating circuit.";
        }

        $stmt->close();
    } else {
        $error = "Circuit name is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Circuit</title>
</head>
<body>

<h1>Add New Circuit</h1>

<a href="list.php">Back to Circuits</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Circuit Name:</label><br>
    <input type="text" name="circuit_name" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country"><br><br>

    <label>Length (km):</label><br>
    <input type="number" step="0.01" name="length_km"><br><br>

    <label>Turns:</label><br>
    <input type="number" name="turns"><br><br>

    <label>Lap Record (hh:mm:ss):</label><br>
    <input type="text" name="lap_record" placeholder="01:30:00"><br><br>

    <button type="submit">Add Circuit</button>

</form>

</body>
</html>
