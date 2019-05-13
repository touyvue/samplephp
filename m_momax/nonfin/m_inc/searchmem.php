<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$memberTotInst = new memberInfoC();
	$generalInfo = new generalInfoC();
	$memberID = $_GET['mid'];
	$groupID = $_GET['groupid'];
	
	try{//get all groups with admin rights
		$result = $db->prepare("SELECT DISTINCT member_id FROM $db_group_rights WHERE group_id=?");
		$result->execute(array($groupID)); 
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$grpList = '[{"value":"9999","label":"All Member"}';
	if ($itemCount > 0){
		foreach ($result as $row){
			$name = $memberTotInst->memberNameReqF($row['member_id']);
			$grpList = $grpList.',{"value":"'.$row['member_id'].'","label":"'.$name.'"}';
		}
		$grpList = $grpList."]";
	}
	
	echo $grpList;

?>