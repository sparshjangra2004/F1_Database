<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$query = "
    SELECT 
        r.race_id,
        r.race_name,
        r.race_date,
        r.weather_condition,
        r.laps,
        s.year AS season_year,
        c.circuit_name
    FROM Races r
    JOIN Seasons s ON r.season_id = s.season_id
    JOIN Circuits c ON r.circuit_id = c.circuit_id
    ORDER BY r.race_date DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Races</title>
</head>
<body>

<h1>Manage Races</h1>
<br><br>

<a href="create.php"> Add New Race</a>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Race Name</th>
        <th>Date</th>
        <th>Season</th>
        <th>Circuit</th>
        <th>Weather</th>
        <th>Laps</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['race_name']; ?></td>
                <td><?php echo $row['race_date']; ?></td>
                <td><?php echo $row['season_year']; ?></td>
                <td><?php echo $row['circuit_name']; ?></td>
                <td><?php echo $row['weather_condition']; ?></td>
                <td><?php echo $row['laps']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['race_id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['race_id']; ?>"
                       onclick="return confirm('Delete this race?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No races found.</td>
        </tr>
    <?php endif; ?>

</table>
<BR><BR>
<a href="../index.php">BACK TO HOME</a>
</body>
</html>