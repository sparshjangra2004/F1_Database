<?php 
require_once '../config/dbconn.php';

$query = "
    SELECT
        c.circuit_id,
        c.circuit_name,
        c.country,
        c.length_km,
        c.turns,
        c.lap_record,
        COUNT(ra.race_id) AS total_races,
        MAX(s.year) AS last_race_year
    FROM Circuits c
    LEFT JOIN Races ra ON c.circuit_id = ra.circuit_id
    LEFT JOIN Seasons s ON ra.season_id = s.season_id
    GROUP BY
        c.circuit_id,
        c.circuit_name,
        c.country,
        c.length_km,
        c.turns,
        c.lap_record
    ORDER BY c.circuit_name ASC";

$result = $conn->query($query);

$total_circuits = $result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Circuits - F1 Championship</title>
</head>
<body>

<!-- System Header -->
<center>
    <h1><a href="../mainmenu.php">F1</a></h1>
</center>

<hr>

<!-- Navigation -->
<h3>Navigation</h3>
<a href="teams.php">Teams</a> |
<a href="drivers.php">Drivers</a> |
<a href="races.php">Races</a> |
<a href="circuits.php">Circuits</a> |
<a href="sponsors.php">Sponsors</a>

<hr>

<center>
    <h2>Circuits Overview</h2>
</center>

<fieldset>
    <legend><b>Summary Statistics</b></legend>
    <p><b>Total Circuits:</b> <?php echo $total_circuits; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Circuit</th>
        <th>Country</th>
        <th>Length (km)</th>
        <th>Turns</th>
        <th>Lap Record</th>
        <th>Total Races Hosted</th>
        <th>Last Race Year</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $row['circuit_name']; ?></b></td>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['length_km']; ?></td>
                <td><?php echo $row['turns']; ?></td>
                <td><?php echo $row['lap_record'] ?? 'N/A'; ?></td>
                <td><?php echo $row['total_races']; ?></td>
                <td><?php echo $row['last_race_year'] ?? 'N/A'; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No circuits found.</td>
        </tr>
    <?php endif; ?>
</table>

<hr>


</body>
</html>