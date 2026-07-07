<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$query = "
    SELECT
        se.season_id,
        se.year,
        se.total_races,
        COUNT(r.race_id) AS races_added
    FROM Seasons se
    LEFT JOIN Races r ON se.season_id = r.season_id
";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $query .= " WHERE se.year LIKE ?";

    $types = "s";
    $params = [$search];
}

$query .= "
    GROUP BY se.season_id, se.year, se.total_races
    ORDER BY se.year DESC
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
    <title>Manage Seasons</title>
</head>
<body>

<h1>Manage Seasons</h1>

<a href="create.php">Add New Season</a>

<br><br>
<form method="GET">
    <label>Search Year:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Year</th>
        <th>Total Races (planned)</th>
        <th>Races Added</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
                <td><?php echo htmlspecialchars($row['total_races']); ?></td>
                <td><?php echo htmlspecialchars($row['races_added']); ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['season_id']; ?>">Edit</a> |
                    <form method="POST" action="delete.php"
                          onsubmit="return confirm('Delete this season? All races in this season will also be deleted.');">
                        <input type="hidden" name="id" value="<?php echo $row['season_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No seasons found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">Back to home!</a>
</body>
</html>
