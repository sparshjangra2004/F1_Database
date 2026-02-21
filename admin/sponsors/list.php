<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Sponsors</title>
</head>
<body>

<h1>Manage Sponsors</h1>
<br><br>

<a href="create.php">Add New Sponsor</a>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Sponsor Name</th>
        <th>Industry</th>
        <th>Country</th>
        <th>Teams Sponsored</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['sponsor_name']; ?></td>
                <td><?php echo $row['industry']; ?></td>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['total_teams']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['sponsor_id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['sponsor_id']; ?>"
                       onclick="return confirm('Delete this sponsor?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No sponsors found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">Back to home!</a>
</body>
</html>