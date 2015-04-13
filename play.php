<?php
//scott campbell
//cse383 final project - fall 2014
//

session_start();
$user="";
$score = 0;
$errorMode = "";
$out = "";


include 'log.php';
include 'tools.php';

if (isset($_SESSION['user']))  //Checks for initialized users
	$user = $_SESSION['user'];
else {
	header("Location: login.php");
}

$targetcost = $_SESSION['targetcost'];
$numItems = 0;
$cost = 0;

for ($i=0;$i<5;$i++) {
	if (isset($_REQUEST["item$i"])) {
		$cost = $cost + $_SESSION["itemcost$i"];
		$numItems++;
	}
}

//set the score
$score = $cost - $targetcost;
if($score < 0){
	$score = -$score;
}
//set your rank info
if($score < 20){
	$rank = "You are a price whiz!";
	$img = "Happy-face.png";
}else if($score < 50){
	$rank = "You were okay this round.";
	$img = "ok.jpg";
}else{
	$rank = "Better luck next time.";
	$img = "The-Price-is-Wrong.jpg";
}

try{
	$fp = fsockopen("ceclnx01.cec.miamioh.edu", 4000, $errno, $errstr, 30);//Opens a port to ceclnx01
	if (!$fp) { //Connection failed
		mylog("" . $errno);
		$errorMode = "FAIL";
	} else {   //connection success
    		$out = "wolfercm " . $score . " " . $user .  "\r\n";
    		fwrite($fp, $out);
    		$errorMode = "FAIL";  //If unchanged, shows that there was a failure in posting 
    		while (!feof($fp)) {
        		$ret = fgets($fp, 128);
			if(substr($ret,0,2) == "OK"){  //substr chops off an noise after the string
				$errorMode = "OKAY";  //signifies there has been no error.
			}
    		}
    		fclose($fp);
	}
}catch(Exception $e) {  //general failure
	mylog("Error talking to campbell server: " . $e->getMessage(), "\n");
	$errorMode = "FAIL";
}


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
<br>
</div>
<h1>
Welcome <?php echo $user;?>
</h1>
<h2>
<?php
if ($errorMode == "FAIL"):  //If the score posting was a failire...
?>
	Posting score attempt has failed.

<?php endif;?>

<?php
if ($numItems != 3 ):
	?>
	Sorry - you did not select 3 items
	<?else:?>

	Your total of the selected items is <?print $cost;?>

	Your score is <?print $score;?>
</h2>
<h2>

	<?print $rank;?>
</h2>
<h2>
	<?print "<img src='" . $img . "'>" ?>	

	<?php endif;?>
</h2>
	<div id="footer">
<h3>
<a href='index.php'>Play again</a>
</h3>
<h4>
<a href='http://ceclnx01.cec.miamioh.edu/~campbest/scores.php'>View scores!</a>
</h4>
</div>
</body>
</html>

