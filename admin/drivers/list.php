<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';


$sql = "SELECT 
        d.driver_id,
        d.first_name,
        d.last_name,
        d.driver_number,
        d.nationality,
        d.status,
        t.team_name
    FROM Drivers d
    LEFT JOIN Teams t ON d.team_id = t.team_id";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $sql .= " WHERE 
                d.first_name LIKE ?
                OR d.last_name LIKE ?
                OR d.nationality LIKE ?
                OR d.driver_number LIKE ?";

    $types = "ssss";
    $params = [$search, $search, $search, $search];
}

$sql .= " ORDER BY d.last_name ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<head>
    <title> admin_view</title>
</head>
<body>
<center>
<h1> MANAGE DRIVERS vroooooom!!!</h1>
</center>
<hr>
<form method="GET">
    <label>Search Driver:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>
<br>
<a href = "create.php"> ADD A NEW DRIVER </a>
<br><br>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Number</th>
        <th>Nationality</th>
        <th>Team</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php if($result && $result->num_rows>0):?>
        <?php while( $row = $result->fetch_assoc()):?>
        <tr>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td><?php echo $row['driver_number']; ?></td>
                <td><?php echo $row['nationality']; ?></td>
                <td><?php echo $row['team_name'] ?? 'No Team'; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['driver_id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['driver_id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this driver?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No drivers found.</td>
        </tr>
    <?php endif; ?>
</table>
<br><br>
<a href = '../index.php'>BACK TO HOME!</a>
</body>
</html>