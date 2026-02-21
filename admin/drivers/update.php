<?php

require_once '../auth.php';
require_once '../../config/dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Driver ID.");}

$driver_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Drivers WHERE driver_id = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();
$stmt->close();

$teams = $conn->query("SELECT team_id, team_name FROM Teams ORDER BY team_name ASC");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $driver_number = intval($_POST['driver_number']);
    $nationality = trim($_POST['nationality']);
    $team_id = !empty($_POST['team_id']) ? intval($_POST['team_id']) : NULL;
    $status = $_POST['status'];

    if ($first_name && $last_name && $driver_number > 0 && $nationality && $status) {

        $update = $conn->prepare("
            UPDATE Drivers
            SET first_name = ?, 
                last_name = ?, 
                driver_number = ?, 
                nationality = ?, 
                team_id = ?, 
                status = ?
            WHERE driver_id = ?
        ");

        $update->bind_param("ssisssi",
            $first_name,
            $last_name,
            $driver_number,
            $nationality,
            $team_id,
            $status,
            $driver_id
        );

        if ($update->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating driver.";
        }

        $update->close();
    } else {
        $error = "All required fields must be filled correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Driver</title>
</head>
<body>

<h1>Edit Driver</h1>

<a href="list.php">Back to Drivers</a>

<hr>

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>First Name:</label><br>
    <input type="text" name="first_name" 
           value="<?php echo $driver['first_name']; ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" 
           value="<?php echo $driver['last_name']; ?>" required><br><br>

    <label>Driver Number:</label><br>
    <input type="number" name="driver_number" 
           value="<?php echo $driver['driver_number']; ?>" required><br><br>

    <label>Nationality:</label><br>
    <input type="text" name="nationality" 
           value="<?php echo $driver['nationality']; ?>" required><br><br>

    <label>Team:</label><br>
    <select name="team_id">
        <option value="">-- No Team --</option>
        <?php while ($team = $teams->fetch_assoc()): ?>
            <option value="<?php echo $team['team_id']; ?>"
                <?php if ($driver['team_id'] == $team['team_id']) echo "selected"; ?>>
                <?php echo $team['team_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="Active" 
            <?php if ($driver['status'] == 'Active') echo "selected"; ?>>
            Active
        </option>
        <option value="Reserve"
            <?php if ($driver['status'] == 'Reserve') echo "selected"; ?>>
            Reserve
        </option>
        <option value="Retired"
            <?php if ($driver['status'] == 'Retired') echo "selected"; ?>>
            Retired
        </option>
    </select><br><br>

    <button type="submit">Update Driver</button>

</form>

</body>
</html>