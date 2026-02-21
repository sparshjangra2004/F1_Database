<?php
require_once 'auth.php';
require_once '../config/dbconn.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title> admin page</title>
</head>
<body>
    <center><h1>Admin Area</h1></center>
<hr>
<ul>
    <li><a href="teams/list.php">Manage Teams</a></li>
    <li><a href="drivers/list.php">Manage Drivers</a></li>
    <li><a href="races/list.php">Manage Races</a></li>
    <li><a href="sponsors/list.php">Manage Sponsors</a></li>
    <li><a href = "team_sponsor/list.php">Manage Contracts</a></li>
</ul>
<hr>

<a href= "../mainmenu.php">MAIN MENU<a>
<hr>
<a href="logout.php">Logout</a>

</body>
</html>
