<?php
require_once '../config/dbconn.php';

$query = "
    SELECT 
        t.team_id,
        t.team_name,
        t.country,
        t.team_principal,
        t.founded_year,
        t.engine_supplier,
        COUNT(DISTINCT d.driver_id) AS total_drivers,
        IFNULL(SUM(r.points), 0) AS total_points
    FROM Teams t
    LEFT JOIN Drivers d ON t.team_id = d.team_id
    LEFT JOIN Results r ON d.driver_id = r.driver_id
    GROUP BY 
        t.team_id,
        t.team_name,
        t.country,
        t.team_principal,
        t.founded_year,
        t.engine_supplier
    ORDER BY total_points DESC
";

$result = $conn->query($query);
$total_teams = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teams - F1 Championship</title>
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
    <h2>Teams Overview</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Teams:</b> <?php echo $total_teams; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Team Name</th>
        <th>Country</th>
        <th>Team Principal</th>
        <th>Founded</th>
        <th>Engine</th>
        <th>Total Drivers</th>
        <th>Total Points</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $row['team_name']; ?></b></td>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['team_principal']; ?></td>
                <td><?php echo $row['founded_year']; ?></td>
                <td><?php echo $row['engine_supplier']; ?></td>
                <td><?php echo $row['total_drivers']; ?></td>
                <td><?php echo $row['total_points']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No teams found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>