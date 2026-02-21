<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Team Sponsors</title>
</head>
<body>

<h1>Manage Team Sponsors</h1>

<a href="create.php">Add New Sponsorship</a>
<hr>

<table border="1" cellpadding="10">
    <tr>
        <th>Team</th>
        <th>Sponsor</th>
        <th>Contract Start</th>
        <th>Contract End</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['team_name']; ?></td>
                <td><?php echo $row['sponsor_name']; ?></td>
                <td><?php echo $row['contract_start']; ?></td>
                <td><?php echo $row['contract_end']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['team_sponsor_id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['team_sponsor_id']; ?>">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No sponsorships found.</td>
        </tr>
    <?php endif; ?>

</table>
<a href="../index.php">Back to home!</a>
</body>
</html>