<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	$state = "no";
	$memberID = $_POST['mid'];
	$mileageID = $_POST['mileageID'];
	$actState = $_POST['actState'];
	
	try{					
		$result = $db->prepare("SELECT mileage_id,name,rate,note FROM $db_mileage WHERE member_id=? AND mileage_id=?");
		$result->execute(array($memberID,$mileageID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$trackInfo = "";
	if ($itemCount > 0){
		foreach ($result as $row) {
			$trackInfo = '{"id":"'.$row['mileage_id'].'","name":"'.str_replace('\\','',$row['name']).'","rate":"'.$row['rate'].'","note":"'.str_replace('\\','',$row['note']).'"}';
		}
	}
	echo $trackInfo;

?>