<?php
	include('m_inc/def_value.php');
	include ('m_inc/m_config.php');
	include ('/m_inc/p_hash.php');
	$resetID = $_GET['cid'];
	$newGroup = $_GET['sg'];
	$sg = "";
	$todate = date('Y-m-d');
	$user_login = "";
	$userFlag = "";
	$resetFlag = "";
	$yes = "yes";
	$sTitle = "";

    if ($resetID != "" and $_POST['password']==""){
		try{ //get member info		
			$result = $db->prepare("SELECT user_email,member_id,active FROM $db_reset_log WHERE reset_code=?");
			$result->execute(array($resetID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		$itemFound = $result->rowCount();
		foreach ($result as $row) {
			$email = $row['user_email'];
			$memberID = $row['member_id'];
			$active = $row['active'];
		}
		////make sure the link has not been reset - only reset once
		if ($active == "yes"){
			$resetFlag = "reset"; //has been reset
			$resetGrp = "reset"; //has been set
		}else{
			$resetFlag = "";
			$resetGrp = "";
			if ($newGroup == "new"){
				$sg = "new";
			}
		}
		
    }
	
	$sTitle = "Reset Password";
	if ($newGroup == "new"){
		$sTitle = "Set Up Password";
	}
	
    if ($resetID!="" and $_POST['password']!=""){
		$new_pass = $_POST['password'];
		try{//get member info			
			$result = $db->prepare("SELECT member_id,active FROM $db_reset_log WHERE reset_code=?");
			$result->execute(array($resetID));
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		$itemFound = $result->rowCount();
		foreach ($result as $row) {
			$memberID = $row['member_id'];
			$active = $row['active'];
		}
		
		if ($itemFound > 0 and $active == "no"){
			$hash_cost_log2 = 8;
			$hash_portable = FALSE;
			$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
			$hash = $hasher->HashPassword($new_pass);
			try{//update password
				$result = $db->prepare("UPDATE $db_member SET password=? WHERE member_id=?");
				$result->execute(array($hash,$memberID));
			} catch(PDOException $e) {
				echo "message003 - Sorry, system is experincing problem. Please check back.";
			}
			try{//update profile
				$result = $db->prepare("UPDATE $db_reset_log SET date_reset=?,active=? WHERE reset_code=?");
				$result->execute(array($todate,$yes,$resetID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			$userFlag = "good";
			$resetGrp = "good";
		}else{
			$resetFlag = "reset"; //has been reset
			$resetGrp = "reset";
		}
	}
?>