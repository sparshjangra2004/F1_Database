<?php
require_once '../config/dbconn.php';

$query = "
    SELECT 
        ra.race_id,
        ra.race_name,
        ra.race_date,
        s.year AS season_year,
        c.circuit_name,
        ra.weather_condition,
        ra.laps,
        CONCAT(d.first_name, ' ', d.last_name) AS winner_name
    FROM Races ra
    JOIN Seasons s ON ra.season_id = s.season_id
    JOIN Circuits c ON ra.circuit_id = c.circuit_id
    LEFT JOIN Results r ON ra.race_id = r.race_id AND r.finish_position = 1
    LEFT JOIN Drivers d ON r.driver_id = d.driver_id
    ORDER BY ra.race_date DESC
";

$result = $conn->query($query);
$total_races = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Races - F1 Championship</title>
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
    <h2>Races Overview</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Races:</b> <?php echo $total_races; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Race</th>
        <th>Date</th>
        <th>Season</th>
        <th>Circuit</th>
        <th>Weather</th>
        <th>Laps</th>
        <th>Winner</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $row['race_name']; ?></b></td>
                <td><?php echo $row['race_date']; ?></td>
                <td><?php echo $row['season_year']; ?></td>
                <td><?php echo $row['circuit_name']; ?></td>
                <td><?php echo $row['weather_condition']; ?></td>
                <td><?php echo $row['laps']; ?></td>
                <td><?php echo $row['winner_name'] ?? 'N/A'; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No races found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>
</body>
</html>