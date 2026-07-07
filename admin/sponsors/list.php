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
";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $query .= " WHERE
                s.sponsor_name LIKE ?
                OR s.industry LIKE ?
                OR s.country LIKE ?";

    $types = "sss";
    $params = [$search, $search, $search];
}

$query .= "
    GROUP BY
        s.sponsor_id,
        s.sponsor_name,
        s.industry,
        s.country
    ORDER BY s.sponsor_name ASC
";

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
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

<br><br>
<form method="GET">
    <label>Search Sponsor:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

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
                <td><?php echo htmlspecialchars($row['sponsor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['industry']); ?></td>
                <td><?php echo htmlspecialchars($row['country']); ?></td>
                <td><?php echo htmlspecialchars($row['total_teams']); ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['sponsor_id']; ?>">Edit</a> |
                    <form method="POST" action="delete.php"
                          onsubmit="return confirm('Delete this sponsor?');">
                        <input type="hidden" name="id" value="<?php echo $row['sponsor_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
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