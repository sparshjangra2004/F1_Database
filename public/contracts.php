<?php
require_once '../config/dbconn.php';

$query = "
    SELECT
        ts.team_sponsor_id,
        t.team_name,
        s.sponsor_name,
        ts.contract_start,
        ts.contract_end
    FROM Team_Sponsors ts
    JOIN Teams t ON ts.team_id = t.team_id
    JOIN Sponsors s ON ts.sponsor_id = s.sponsor_id
    ORDER BY t.team_name ASC
";

$result = $conn->query($query);
$total_contracts = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contracts - F1 Championship</title>
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
    <h2>Team-Sponsor Contracts</h2>
</center>

<fieldset>
    <legend><b>Summary</b></legend>
    <p><b>Total Contracts:</b> <?php echo $total_contracts; ?></p>
</fieldset>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Team</th>
        <th>Sponsor</th>
        <th>Contract Start</th>
        <th>Contract End</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo htmlspecialchars($row['team_name']); ?></b></td>
                <td><?php echo htmlspecialchars($row['sponsor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['contract_start']); ?></td>
                <td><?php echo htmlspecialchars($row['contract_end']); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No contracts found.</td>
        </tr>
    <?php endif; ?>

</table>

<hr>

</body>
</html>
