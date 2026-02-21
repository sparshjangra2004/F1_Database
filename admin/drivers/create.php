<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$teams = $conn->query("SELECT team_id, team_name FROM Teams ORDER BY team_name ASC");

if($_SERVER['REQUEST_METHOD']== "POST"){
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $driver_number = intval($_POST['driver_number']);
    $nationality = trim($_POST['nationality']);
    $team_id = !empty($_POST['team_id']) ? intval($_POST['team_id']) : NULL;
    $status = $_POST['status'];

    if(!empty($first_name) && !empty($last_name) &&
        !empty($driver_number) && !empty($nationality) &&
        !empty($status)){
            $stmt = $conn->prepare("
            INSERT INTO Drivers
            (first_name, last_name, driver_number, nationality, team_id, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssisss",
            $first_name,
            $last_name,
            $driver_number,
            $nationality,
            $team_id,
            $status
        );
        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error creating driver.";
        }

        $stmt->close();
    } else {
        $error = "All required fields must be filled.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Driver</title>
</head>
<body>

<h1>Add New Driver</h1>

<a href="list.php">Back to Drivers</a>

<hr> 

<?php if (isset($error)) echo "<p>$error</p>"; ?>

<form method="POST">

    <label>First Name:</label><br>
    <input type="text" name="first_name" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" required><br><br>

    <label>Driver Number:</label><br>
    <input type="number" name="driver_number" required><br><br>

    <label>Nationality:</label><br>
    <input type="text" name="nationality" required><br><br>

    <label>Team:</label><br>
    <select name="team_id">
        <option value="">-- Select Team --</option>
        <?php while ($team = $teams->fetch_assoc()): ?>
            <option value="<?php echo $team['team_id']; ?>">
                <?php echo $team['team_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="Active">Active</option>
        <option value="Reserve">Reserve</option>
        <option value="Retired">Retired</option>
    </select><br><br>

    <button type="submit">Add Driver</button>

</form>

</body>
</html>