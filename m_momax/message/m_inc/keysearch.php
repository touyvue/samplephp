<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	
	$memData = "[";
	$fName = $_GET['keyword']."%";
	$foundFlag = 1;
	try{					
		$result = $db->prepare("SELECT first_name,last_name FROM $db_member WHERE first_name LIKE ? LIMIT 5");
		$result->execute(array($fName));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	if ($itemCount > 0){
		foreach ($result as $row){
			if ($foundFlag > 1){
				$memData = $memData.",";
			}
			$foundFlag++;
			$memData = $memData.'{"first":"'.str_replace('\\','',$row['first_name']).'",';
			$memData = $memData.'"last":"'.str_replace('\\','',$row['last_name']).'"}';
		}
	}
	$memData = $memData."]";
	echo $memData; //json_encode($memData);

?>