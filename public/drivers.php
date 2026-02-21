<?php
require_once '../config/dbconn.php';

$query = "
    SELECT 
        d.driver_id,
        d.first_name,
        d.last_name,
        d.driver_number,
        d.nationality,
        d.status,
        t.team_name
    FROM Drivers d
    LEFT JOIN Teams t ON d.team_id = t.team_id
    ORDER BY d.last_name ASC
";

$result = $conn->query($query);
$total_drivers = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Drivers - F1 Championship</title>
</head>
<body>

<center>
    <h1><a href="../mainmenu.php">F1</a></h1>
</center>

<hr>

<h3>Navigation</h3>
<a href="teams.php">Teams</a> |
<a href="drivers.php">Drivers</a> |
<a href="races.php">Races</a> |
<a href="circuits.php">Circuits</a> |
<a href="sponsors.php">Sponsors</a>

<hr>

<center>
    <h2>Drivers Overview</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Drivers:</b> <?php echo $total_drivers; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Driver</th>
        <th>Number</th>
        <th>Nationality</th>
        <th>Team</th>
        <th>Status</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $row['first_name'] . " " . $row['last_name']; ?></b></td>
                <td><?php echo $row['driver_number']; ?></td>
                <td><?php echo $row['nationality']; ?></td>
                <td><?php echo $row['team_name'] ?? 'No Team'; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No drivers found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>