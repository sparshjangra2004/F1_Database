<?php
require_once '../config/dbconn.php';

$query = "
    SELECT
        s.sponsor_id,
        s.sponsor_name,
        s.industry,
        s.country,
        COUNT(ts.team_id) AS total_teams
    FROM Sponsors s
    LEFT JOIN Team_Sponsors ts ON s.sponsor_id = ts.sponsor_id
    GROUP BY
        s.sponsor_id,
        s.sponsor_name,
        s.industry,
        s.country
    ORDER BY s.sponsor_name ASC
";

$result = $conn->query($query);
$total_sponsors = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sponsors - F1 Championship</title>
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
    <h2>Sponsors Overview</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Sponsors:</b> <?php echo $total_sponsors; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Sponsor</th>
        <th>Industry</th>
        <th>Country</th>
        <th>Teams Sponsored</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $row['sponsor_name']; ?></b></td>
                <td><?php echo $row['industry']; ?></td>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['total_teams']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No sponsors found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>