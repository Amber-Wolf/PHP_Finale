<?php
//Amber Wolfer
//cse383 final project - fall 2014
//

session_start();
$user="";


include 'log.php';


if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == "user") {
		$cmd= $_REQUEST['cmd'];
		$user = htmlspecialchars($_REQUEST['user']);
		$_SESSION['user'] = $user;
		header('location:index.php');		
	}
mylog("IP: " . $_SERVER['REMOTE_ADDR'] . " Port: " .  $_SERVER['REMOTE_PORT']);
?>
<!doctype html
<html>
<head>
<title>Wolfercm Final Project</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="page">
<div id="header">
Wolfercm Final Project
</div>
<h2>
Please enter your username:
<div>
<form method="post">
<input type="text" name="user">
<input type="submit">
<input type="hidden" name="cmd" value="user">
</form>
</div>
</h2>
</body>
</html>
