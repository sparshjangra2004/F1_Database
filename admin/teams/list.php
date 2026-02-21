<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$sql = "SELECT 
            t.team_id,
            t.team_name,
            t.country,
            t.team_principal,
            t.founded_year,
            t.engine_supplier
        FROM Teams t";

$params = [];
$types = "";

if (isset($_GET['search']) && trim($_GET['search']) !== "") {

    $search = "%" . trim($_GET['search']) . "%";

    $sql .= " WHERE 
                t.team_name LIKE ?
                OR t.country LIKE ?
                OR t.engine_supplier LIKE ?";

    $types = "sss";
    $params = [$search, $search, $search];
}

$sql .= " ORDER BY t.team_name ASC";

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
<h1> MANAGE TEAMS vroooooom!!!</h1>
</center>
<hr>
<form method="GET">
    <label>Search Team:</label>
    <input type="text" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<br>
<a href = "create.php"> ADD A NEW TEAM </a>
<br><br>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Team Name</th>
        <th>Country</th>
        <th>Principal</th>
        <th>Founded</th>
        <th>Engine</th>
        <th>Actions</th>
    </tr>
    <?php if($result && $result-> num_rows>0):?>
        <?php while($row = $result-> fetch_assoc()):?>
             <tr>
                <td><?php echo $row['team_name']; ?></td>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['team_principal']; ?></td>
                <td><?php echo $row['founded_year']; ?></td>
                <td><?php echo $row['engine_supplier']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['team_id']; ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo $row['team_id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this team?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No teams found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">BACK TO HOME!</a>

</body>
</html>


