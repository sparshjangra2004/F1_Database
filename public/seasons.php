<?php
require_once '../config/dbconn.php';

$query = "
    SELECT
        s.season_id,
        s.year,
        s.total_races,
        COUNT(r.race_id) AS races_held
    FROM Seasons s
    LEFT JOIN Races r ON s.season_id = r.season_id
    GROUP BY
        s.season_id,
        s.year,
        s.total_races
    ORDER BY s.year DESC
";

$result = $conn->query($query);
$total_seasons = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seasons - F1 Championship</title>
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
<a href="sponsors.php">Sponsors</a> |
<a href="seasons.php">Seasons</a> |
<a href="contracts.php">Contracts</a> |
<a href="driver_standings.php">Driver Standings</a> |
<a href="team_standings.php">Team Standings</a>

<hr>

<center>
    <h2>Seasons Overview</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Seasons:</b> <?php echo $total_seasons; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Year</th>
        <th>Planned Races</th>
        <th>Races Held</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo htmlspecialchars($row['year']); ?></b></td>
                <td><?php echo htmlspecialchars($row['total_races']); ?></td>
                <td><?php echo htmlspecialchars($row['races_held']); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No seasons found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>
