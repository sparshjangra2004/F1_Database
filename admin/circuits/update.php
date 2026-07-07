<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Circuit ID.");
}

$circuit_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Circuits WHERE circuit_id = ?");
$stmt->bind_param("i", $circuit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Circuit not found.");
}

$circuit = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $circuit_name = trim($_POST['circuit_name']);
    $country = trim($_POST['country']);
    $length_km = $_POST['length_km'] !== '' ? floatval($_POST['length_km']) : NULL;
    $turns = $_POST['turns'] !== '' ? intval($_POST['turns']) : NULL;
    $lap_record = $_POST['lap_record'] !== '' ? $_POST['lap_record'] : NULL;

    if ($circuit_name) {

        $update = $conn->prepare("
            UPDATE Circuits
            SET circuit_name = ?, country = ?, length_km = ?, turns = ?, lap_record = ?
            WHERE circuit_id = ?
        ");

        $update->bind_param("ssdisi",
            $circuit_name,
            $country,
            $length_km,
            $turns,
            $lap_record,
            $circuit_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating circuit.";
        }

        $update->close();
    } else {
        $error = "Circuit name is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Circuit</title>
</head>
<body>

<h1>Edit Circuit</h1>

<a href="list.php">Back to Circuits</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>Circuit Name:</label><br>
    <input type="text" name="circuit_name"
           value="<?php echo htmlspecialchars($circuit['circuit_name']); ?>" required><br><br>

    <label>Country:</label><br>
    <input type="text" name="country"
           value="<?php echo htmlspecialchars($circuit['country'] ?? ''); ?>"><br><br>

    <label>Length (km):</label><br>
    <input type="number" step="0.01" name="length_km"
           value="<?php echo htmlspecialchars($circuit['length_km'] ?? ''); ?>"><br><br>

    <label>Turns:</label><br>
    <input type="number" name="turns"
           value="<?php echo htmlspecialchars($circuit['turns'] ?? ''); ?>"><br><br>

    <label>Lap Record (hh:mm:ss):</label><br>
    <input type="text" name="lap_record"
           value="<?php echo htmlspecialchars($circuit['lap_record'] ?? ''); ?>" placeholder="01:30:00"><br><br>

    <button type="submit">Update Circuit</button>

</form>

</body>
</html>
