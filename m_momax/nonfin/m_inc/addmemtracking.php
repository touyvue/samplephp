<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	$generalInfo = new generalInfoC();
	
	$memberID = $_POST['mid'];
	$trkmemDetID = $_POST['trkmemid'];
	$actState = $_POST['actState'];
	
	if ($actState == "save" or $actState == "delete"){ 
		$amount = $_POST['amount'];
		$amount = preg_replace('/[\$,]/', '', $amount); //remove $ and common from amount and assign zero if needs
		if ($amount=="" or $amount==0 or $amount==0.00 or $amount<0){
			$amount = 0;
		}
		$attend = $_POST['attend'];
		$note = $_POST['note'];
		$note = str_replace('"', "", $note); //remove " from note
		$accountID = $_POST['accountid'];
	}
	$actionFlag = "";
	
	if ($actState == "add"){
		try{//add specific member member track details
			$result = $db->prepare("INSERT INTO $db_trkmember_detail (trkmember_id,value,member_id,present,note) VALUES (?,?,?,?,?)");
			$result->execute(array($trkmemDetID,0,$memberID,"no",""));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$actionFlag = "add";
	}
	
	if ($actState == "save"){
		try{//delete specific member
			$result = $db->prepare("UPDATE $db_trkmember_detail SET value=?,present=?,note=? WHERE trkmember_detail_id=?");
			$result->execute(array($amount,$attend,$note,$trkmemDetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		
		try{ //get main trkmemberID			
			$result = $db->prepare("SELECT trkmember_id FROM $db_trkmember_detail WHERE trkmember_detail_id=?");
			$result->execute(array($trkmemDetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row) {
			$trkmemberID = $row['trkmember_id'];
		}
		
		//add member track to transaction
		if ($accountID != 0 or $accountID != "NULL"){
			$pathLink = "nonlog";
			$generalInfo->addTrkmemberRecordF($memberID,$trkmemberID,$pathLink);
		}		
		$actionFlag = "save";
	}
	if ($actState == "delete"){
		try{//delete all details member tracking
			$result = $db->prepare("DELETE FROM $db_trkmember_detail WHERE trkmember_detail_id=?");
			$result->execute(array($trkmemDetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$actionFlag = "delete";
	}
	
	echo $actionFlag;
?>