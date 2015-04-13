<?php
//Amber Wolfer
//cse383 final project - fall 2014
//

session_start();
$user="";
$error="NONE"; //THIS TRACKS AND CONTAINS THE ERROR TO SHOW THE HUMAN

include 'log.php';
include 'tools.php';

$mysqlservername = "";  //mysql info is defined.
$mysqlusername = "";
$mysqlpassword = "";
$dbname = "";

$picks = 3;

// Create connection
$conn = new mysqli($mysqlservername, $mysqlusername, $mysqlpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    $error = "database connection failure";
} 

//'Rolls' the virtual dice to see if a value is picked.
//Changed global variable, $pick which corresponds to 
//the number items that need to be selected still.
//$num = The items number, from 0 to 4
//$value = The items value.
//Returns 0 if not picked, value if picked
function roll($num, $value) {
	global $picks;
	$counter = 0;
	while($counter < $picks){ //repeat for each available pick still available
		$temp = rand(0,4-$num); //'Rolls' the dice. More likely to be picked on later
		if($temp == 0){
			//echo "select " . $num . " "; //uncomment this to cheat
			$picks = $picks - 1;
			if($picks == 0){
				// echo "Success! //uncomment to show that three items are
				// indeed picked
			}
			return $value; //chosen
		}
		$counter++;
	}
	if($picks == 5-$num){  //if the number of items remain is equal to the number picks available
			       //take this one
		// echo "select " . $num . " "; //uncomment to cheat
		$picks = $picks - 1;
		return $value;
	}
	return 0; //not chosen
}



$item=array();
$itemurl=array();
$cost = 0;

//Gets the values from an array!
function getItems() {
	global $item;
	global $itemurl;
	global $cost;
	global $result;
	global $conn;
	global $error;

	$cost = 0;
	try{
		$sql = "select * from items order by rand() limit 5";
		$result = $conn->query($sql);

		if ($result->num_rows > 4) {
    		// take the data from the first 5 rows and store them into the objects
			$counter = 0;
			while($counter < 5){ //reads five rows
				$row = $result->fetch_assoc();
        			//echo "name: " . $row["itemName"]. " - Url: " . $row["itemURL"]. " " . $row["cost"]. "<br>";
				$item[$counter] = $row["itemName"];
				$itemurl[$counter] = $row["itemURL"]; 
				if(!url_exists($row["itemURL"])){  //Replace with missing image link if imaage is broken.
					$itemurl[$counter] = "http://img1.wikia.nocookie.net/__cb20141028171337/pandorahearts/images/a/ad/Not_available.jpg";
				}
				$cost = $cost + roll($counter, $row["cost"]);
				$_SESSION["itemcost$counter"] = $row["cost"];
				$counter = $counter + 1;	
    			}
		} else { //something is wrong in the database if there is less than 5 entries
			mylog("Error in  database");
			$error = "Error in database.";
		}
		$_SESSION['targetcost'] = $cost;
	}catch(Exception $e){ //A general error occurred. Unlikely, but this prevents users from seeing
			      //raw error data
		mylog($e->getMessage());
		$error = "General error occurred. Contact admin for details.";
	}
}


if (isset($_SESSION['user'])){
	$user = $_SESSION['user'];
	mylog($user . " is on the index page ");
	mylog($user . " IP: " . $_SERVER['REMOTE_ADDR'] . " Port: " .  $_SERVER['REMOTE_PORT']);
}else {
	header("Location: login.php");
}


?>
<!doctype html
<html>
<head>
<title>Wolfercm Final Project</title>
<link href="http://fonts.googleapis.com/css?family=Corben:bold" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Nobile" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="page">
<div id="header">
Wolfercm Final Project
</div>
<h1>
Welcome <?php echo $user;
if($error == "NONE"){ //Only continue if not in error state
	getItems();
}
?>
</h1>


<?php
if ($error == "NONE"): //Only continue if not in error state
?>
<h2>
Please select from the following list 3 items that you think add up to <?php print $cost;?>
</h2>
<form method='post' action="play.php">
<div id='bigtable'>
<table>
<?php
print "<tr>";
for ($i=0;$i<5;$i++) {
	print "<td id='content'><img src='" . $itemurl[$i] . "'><br>" . $item[$i] . "<input type='checkbox' name='item$i' onclick='onClick(this,".$i.")'></div>";
	if (($i%3)==2) 
		print "</tr><tr>";
}
?>
</tr>
</table>
</div>
<h3>
<input type="hidden" name="cmd" value="play">
<input id="send" type='Submit'>
</h3>
</form>
<script src="click.js"></script>

<?php else:?>
<h2>
An error occured: <?php print $error;?>

<?php endif;?>



</div>
</body>
</html>
