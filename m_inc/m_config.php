<?php
	$host = "127.0.0.1"; //"localhost"; -using localhost is somehow really slow
	$user = "maxmoni";
	$pass = "maxx2019";
	$db = "maxmoni";
	       
	try {// institiate PDO_MYSQL connection
		$db = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
    	echo "Sorry1, the system is experiencing difficulty. Please check back shortly.";
	}
?>