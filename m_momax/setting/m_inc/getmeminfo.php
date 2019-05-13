<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	include ('../../../m_inc/p_hash.php');
		
	$state = $_POST['state'];
	$memberID = $_POST['mid'];
	$memberInfo = "";
	try{//get new accountID
		$result = $db->prepare("SELECT first_name,last_name,email FROM $db_member WHERE member_id=?");
		$result->execute(array($memberID)); //get recurring info
	} catch(PDOException $e) {
		echo "message004 - Sorry, system is experincing problem. Please check back.";
	}
	foreach ($result as $row){
		$memberInfo = '{"first":"'.str_replace('\\','',$row['first_name']).'","last":"'.str_replace('\\','',$row['last_name']).'","email":"'.$row['email'].'"}';
	}
	echo $memberInfo;
	
?>