<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$query = "
    SELECT
        res.result_id,
        ra.race_name,
        ra.race_date,
        d.first_name,
        d.last_name,
        res.grid_position,
        res.finish_position,
        res.points,
        res.fastest_lap,
        res.status
    FROM Results res
    JOIN Races ra ON res.race_id = ra.race_id
    JOIN Drivers d ON res.driver_id = d.driver_id
";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $query .= " WHERE
                ra.race_name LIKE ?
                OR d.first_name LIKE ?
                OR d.last_name LIKE ?";

    $types = "sss";
    $params = [$search, $search, $search];
}

$query .= " ORDER BY ra.race_date DESC, res.finish_position ASC";

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
    <title>Manage Results</title>
</head>
<body>

<h1>Manage Results</h1>

<a href="create.php">Add New Result</a>

<br><br>
<form method="GET">
    <label>Search Race/Driver:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Race</th>
        <th>Driver</th>
        <th>Grid</th>
        <th>Finish</th>
        <th>Points</th>
        <th>Fastest Lap</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['race_name']); ?> (<?php echo htmlspecialchars($row['race_date']); ?>)</td>
                <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['grid_position']); ?></td>
                <td><?php echo htmlspecialchars($row['finish_position']); ?></td>
                <td><?php echo htmlspecialchars($row['points']); ?></td>
                <td><?php echo $row['fastest_lap'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['result_id']; ?>">Edit</a> |
                    <form method="POST" action="delete.php"
                          onsubmit="return confirm('Delete this result?');">
                        <input type="hidden" name="id" value="<?php echo $row['result_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No results found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">Back to home!</a>
</body>
</html>
