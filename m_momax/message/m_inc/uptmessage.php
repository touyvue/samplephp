<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
	$orgReqID = $_POST['orgReqID'];
  	$receiver = $_POST['receiver'];
  	$receiverID = $_POST['receiverID'];
  	$subject = $_POST['subject'];
  	$addmessage = $_POST['addmessage'];
	$status = $_POST['ustatus'];
	$yes = "yes";
		
	try{//update project details
		$result = $db->prepare("UPDATE $db_request SET sender_id=?,receiver_id=?,receiver=?,subject=?,message=?,status=? WHERE request_id=?");
		$result->execute(array($memberID,$receiverID,$receiver,$subject,$addmessage,$status,$orgReqID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	
	echo "good";

?>