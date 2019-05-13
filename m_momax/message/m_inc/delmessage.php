<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$orgReqID = $_POST['orgReqID'];
	$reqType = $_POST['reqType'];
	$yes = "yes";
	
	if ($reqType == "to"){
		$result = $db->prepare("DELETE FROM $db_request WHERE sender_id=? AND request_id=?");
	}else{
		$result = $db->prepare("DELETE FROM $db_request WHERE receiver_id=? AND request_id=?");
	}
		
	try{//delete project item
		$result->execute(array($memberID,$orgReqID));
	} catch(PDOException $e) {
		print "<script> self.location='".$index_url."?err=d1000'; </script>";
	}
		
	echo "good";

?>