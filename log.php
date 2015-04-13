<?php 

//Attemprts to log messages with date
function mylog($message) {
	try{
		$file = 'log.txt';
		file_put_contents($file, date('l jS \of F Y h:i:s A'). " " . $message . "\n", FILE_APPEND | LOCK_EX);
	}catch (Exception $e) {
		//When logging fails, its the end of the road. :(
	}
}


?>
