<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$tagID = $_POST['tagID'];
	$listOrd = $_POST['listOrd'];
	$state = $_POST['state'];
	
	//update account info
	if ($state == "edit"){
		try{//update project details
			$result = $db->prepare("UPDATE $db_tag SET list_order=? WHERE tag_id=? AND member_id=?");
			$result->execute(array($listOrd,$tagID,$memberID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
	}
	echo "done";
?>