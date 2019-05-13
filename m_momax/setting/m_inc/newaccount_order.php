<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$accountID = $_POST['acctID'];
	$listOrd = $_POST['listOrd'];
	$state = $_POST['state'];
	
	//update account info
	if ($state == "edit"){
		try{//update project details
			$result = $db->prepare("UPDATE $db_account SET list_order=? WHERE account_id=? AND member_id=?");
			$result->execute(array($listOrd,$accountID,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}
	echo "done";
?>