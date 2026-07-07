<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$query = "SELECT circuit_id, circuit_name, country, length_km, turns, lap_record FROM Circuits";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $query .= " WHERE circuit_name LIKE ? OR country LIKE ?";

    $types = "ss";
    $params = [$search, $search];
}

$query .= " ORDER BY circuit_name ASC";

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
    <title>Manage Circuits</title>
</head>
<body>

<h1>Manage Circuits</h1>

<a href="create.php">Add New Circuit</a>

<br><br>
<form method="GET">
    <label>Search Circuit:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Circuit Name</th>
        <th>Country</th>
        <th>Length (km)</th>
        <th>Turns</th>
        <th>Lap Record</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['circuit_name']); ?></td>
                <td><?php echo htmlspecialchars($row['country']); ?></td>
                <td><?php echo htmlspecialchars($row['length_km']); ?></td>
                <td><?php echo htmlspecialchars($row['turns']); ?></td>
                <td><?php echo htmlspecialchars($row['lap_record']); ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['circuit_id']; ?>">Edit</a> |
                    <form method="POST" action="delete.php"
                          onsubmit="return confirm('Delete this circuit?');">
                        <input type="hidden" name="id" value="<?php echo $row['circuit_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No circuits found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">Back to home!</a>
</body>
</html>
