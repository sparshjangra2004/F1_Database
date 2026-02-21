<?php
require_once '../config/dbconn.php';

$query = "
    SELECT
        d.driver_id,
        d.first_name,
        d.last_name,
        t.team_name,
        IFNULL(SUM(r.points), 0) AS total_points,
        COUNT(CASE WHEN r.finish_position <= 3 THEN 1 END) AS podiums,
        ROUND(AVG(r.finish_position), 2) AS avg_finish
    FROM Drivers d
    LEFT JOIN Teams t ON d.team_id = t.team_id
    LEFT JOIN Results r ON d.driver_id = r.driver_id
    GROUP BY 
        d.driver_id,
        d.first_name,
        d.last_name,
        t.team_name
    ORDER BY total_points DESC
";

$result = $conn->query($query);

$total_drivers = $result ? $result->num_rows : 0;
$total_points_awarded = 0;

if ($result && $result->num_rows > 0) {
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $total_points_awarded += $row['total_points'];
    }
    $result->data_seek(0);
}

$position = 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Championship Standings</title>
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
    <h2>Driver Championship Standings</h2>
</center>

<fieldset>
    <legend><b>Season Summary</b></legend>
    <p><b>Total Drivers:</b> <?php echo $total_drivers; ?></p>
    <p><b>Total Points Awarded:</b> <?php echo $total_points_awarded; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Position</th>
        <th>Driver</th>
        <th>Team</th>
        <th>Total Points</th>
        <th>Podiums</th>
        <th>Average Finish</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $position++; ?></td>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td><?php echo $row['team_name'] ?? 'No Team'; ?></td>
                <td><?php echo $row['total_points']; ?></td>
                <td><?php echo $row['podiums']; ?></td>
                <td><?php echo $row['avg_finish'] ?? 'N/A'; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No data available.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>