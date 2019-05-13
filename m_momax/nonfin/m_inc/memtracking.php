<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	$state = "no";
	$memberID = $_POST['mid'];
	$trkmemID = $_POST['trkmemID'];
	$actState = $_POST['actState'];
	
	try{					
		$result = $db->prepare("SELECT trkmember_id,name,group_id,category_id,account_id,budget_id,note,date FROM $db_trkmember WHERE member_id=? AND trkmember_id=?");
		$result->execute(array($memberID,$trkmemID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$trkMemInfo = "";
	if ($itemCount > 0){
		foreach ($result as $row) {
			$orgDate = $row['date'];
			$partsArr = explode("-",$orgDate);
			$date = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];//alter yyyy-mm-dd to mm-dd-yyyy 
			$trkMemInfo = '{"id":"'.$row['trkmember_id'].'","name":"'.str_replace('\\','',$row['name']).'","groupid":"'.$row['group_id'].'","cat":"'.$row['category_id'].'","act":"'.$row['account_id'].'","bud":"'.$row['budget_id'].'","date":"'.$date.'","note":"'.str_replace('\\','',$row['note']).'"}';
		}
	}
	echo $trkMemInfo;

?>