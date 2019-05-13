<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	$state = "no";
	$memberID = $_POST['mid'];
	$genDetID = $_POST['genDetID'];
	$actState = $_POST['actState'];
	
	try{					
		$result = $db->prepare("SELECT track_detail_id,purpose,value,category,date,note FROM $db_track_detail WHERE track_detail_id=?");
		$result->execute(array($genDetID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$trackInfo = "";
	if ($itemCount > 0){
		foreach ($result as $row) {
			$orgDate = $row['date'];
			$partsArr = explode("-",$orgDate);
			$date = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy
			$trackInfo = '{"id":"'.$row['track_detail_id'].'","purpose":"'.str_replace('\\','',$row['purpose']).'","value":"'.$row['value'].'","category":"'.str_replace('\\','',$row['category']).'","date":"'.$date.'","note":"'.str_replace('\\','',$row['note']).'"}';
		}
	}
	echo $trackInfo;

?>