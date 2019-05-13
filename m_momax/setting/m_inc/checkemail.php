<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$email = $_POST['email'];
	$check = $_POST['check'];
	$active = "yes";
	
	if ($check == "profile"){
		try{//get new accountID
			$result = $db->prepare("SELECT member_id,email FROM $db_member WHERE email=?");
			$result->execute(array($email)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){		
			foreach ($result as $row){
				if ($email == $row['email'] and $memberID == $row['member_id']){
					$foundStatus = "same";
				}else{
					$foundStatus = "match";
				}
			}
		}else{
			$foundStatus = "good";
		}
	}
	if ($check == "consortium"){
		try{//get new accountID
			$result = $db->prepare("SELECT email FROM $db_member WHERE email=?");
			$result->execute(array($email)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){		
			$foundStatus = "match";
		}else{
			$foundStatus = "good";
		}
	}
	echo $foundStatus;
?>