<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$fName = $_GET['keyword']."%";
	$memberID = $_GET['memID'];
	$firstVal = array();
	$nameVal = "[";
	$memIDVal = "[";
	
	try{//first name					
		$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
		$result->execute(array($memberID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	foreach ($result as $row) {
		$consortID = $row['consortium_id'];
	}
	try{//first name					
		$result = $db->prepare("SELECT DISTINCT first_name,last_name,member_id FROM $db_member WHERE member_id!=? AND consortium_id=? AND first_name LIKE ? ORDER BY first_name ASC");
		$result->execute(array($memberID,$consortID,$fName));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$firstArrCt = 0;
	if ($itemCount > 0){
		foreach ($result as $row) {
			if ($firstArrCt > 0){
				$nameVal = $nameVal.",";
				$memIDVal = $memIDVal.",";
			}
			$firstValArr[$firstArrCt] = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
			$nameVal = $nameVal.'"'.str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']).'"';
			$memIDVal = $memIDVal.'{"value": "'.$row['member_id'].'", "label": "'.str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']).'"}';
			$firstArrCt++;
		}		
	}
	
	try{//last name				
		$result = $db->prepare("SELECT DISTINCT last_name,first_name,member_id FROM $db_member WHERE member_id!=? AND  consortium_id=? AND last_name LIKE ? ORDER BY last_name ASC");
		$result->execute(array($memberID,$consortID,$fName));
		
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	if ($itemCount > 0){
		foreach ($result as $row) {
			$foundFlag = "no";
			for ($i = 0; $i < $firstArrCt; $i++){
				$lastNameVal = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
				if ($firstValArr[$i] == $lastNameVal){
					$foundFlag = "yes";
				}	
			}
			if ($foundFlag == "no"){
				$nameVal = $nameVal.",";
				$memIDVal = $memIDVal.",";
				$nameVal = $nameVal.'"'.str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']).'"';
				$memIDVal = $memIDVal.'{"value": "'.$row['member_id'].'", "label": "'.str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']).'"}';
			}
		}		
	}
	$nameVal = $nameVal.']';
	$memIDVal = $memIDVal.']';
	echo $nameVal."<=>".$memIDVal; 
?>