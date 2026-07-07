<?php
require_once '../auth.php';
require_once '../../config/dbconn.php';

$result = $conn->query("SELECT admin_id, username FROM Admins ORDER BY username ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Editors</title>
</head>
<body>

<h1>Manage Editors</h1>

<a href="create.php">Add New Editor</a>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Username</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row['username']); ?>
                    <?php if ($row['admin_id'] == $_SESSION['admin_id']) echo " (you)"; ?>
                </td>
                <td>
                    <a href="update.php?id=<?php echo $row['admin_id']; ?>">Edit</a>
                    <?php if ($row['admin_id'] != $_SESSION['admin_id']): ?>
                        |
                        <form method="POST" action="delete.php"
                              onsubmit="return confirm('Delete this editor account?');">
                            <input type="hidden" name="id" value="<?php echo $row['admin_id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No editors found.</td>
        </tr>
    <?php endif; ?>

</table>
<br><br>
<a href="../index.php">Back to home!</a>
</body>
</html>
