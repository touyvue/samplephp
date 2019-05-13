<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$budgetTotInst = new budgetInfoC();
	$accountInfo = new accountInfoC();
	
	$memberID = $_POST['mid'];
	$state = $_POST['state'];
	$active = "yes";
	
	if ($state == "add"){
		$tagName = $_POST['tagName'];
		$tagName = str_replace('"', "", $tagName); //remove " from note
		$tagDesc = $_POST['tagDesc'];
		$tagDesc = str_replace('"', "", $tagDesc); //remove " from note
		$setActive = $_POST['active'];
		$itemCount = 1;
		try{//get new accountID
			$result = $db->prepare("SELECT list_order FROM $db_tag WHERE member_id=? ORDER BY list_order ASC");
			$result->execute(array($memberID)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		$itemCount = $result->rowCount();
		$itemCount++;
		
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_tag (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
			$result->execute(array($tagName,$itemCount,$tagDesc,$memberID,$active));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new accountID
			$result = $db->prepare("SELECT tag_id FROM $db_tag WHERE member_id=? ORDER BY tag_id ASC");
			$result->execute(array($memberID)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$accountID = $row['tag_id'];
		}
		echo "done";
	}//end of add state
	
	//get account info
	if ($state == "getdata"){
		$tagID = $_POST['tagID'];
	
		try{//get new accountID
			$result = $db->prepare("SELECT tag_id,name,description,active FROM $db_tag WHERE member_id=? AND tag_id=?");
			$result->execute(array($memberID,$tagID)); //get recurring info
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$tagInfo = '{"id":"'.$row['tag_id'].'","act":"'.$row['active'].'","name":"'.str_replace('\\','',$row['name']).'","desc":"'.str_replace('\\','',$row['description']).'"}';
		}
		echo $tagInfo;
	}
	
	//update account info
	if ($state == "edit"){
		$tagID = $_POST['tagID'];
		
		$tagName = $_POST['tagName'];
		$tagName = str_replace('"', "", $tagName); //remove " from note
		$tagDesc = $_POST['tagDesc'];
		$tagDesc = str_replace('"', "", $tagDesc); //remove " from note
		$setActive = $_POST['active'];
		
		try{//update project details
			$result = $db->prepare("UPDATE $db_tag SET name=?,description=?,active=? WHERE tag_id=?");
			$result->execute(array($tagName,$tagDesc,$setActive,$tagID));
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
	}
	
	//delete account
	if ($state == "delete"){
		$tagID = $_POST['tagID'];
		$tagName = $_POST['tagName'];
		$tagDesc = $_POST['tagDesc'];
		$setActive = $_POST['active'];
				
		try{//delete account
			$result = $db->prepare("DELETE FROM $db_tag WHERE tag_id=? AND member_id=?");
			$result->execute(array($tagID,$memberID));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
	}
?>