<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
		
	$memberID = $_POST['mid'];
  	$receiver = $_POST['receiver'];
  	$receiverID = $_POST['receiverID'];
  	$subject = $_POST['subject'];
	$subject = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $subject);
	$subject = str_replace('"', "", $subject); //remove " from note
  	$addmessage = $_POST['addmessage'];
	$addmessage = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $addmessage);
	$addmessage = str_replace('"', "", $addmessage); //remove " from note
	$status = "Received";
	$yes = "yes";
	
	$selectID = "";
	try{//get all unqine groupID's
		$result = $db->prepare("SELECT member_id FROM $db_member WHERE member_id=?");
		$result->execute(array($receiverID));
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$foundItems = $result->rowCount(); 
	if ($foundItems > 0){
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_request (sender_id,receiver_id,receiver,subject,message,status,active) VALUES (?,?,?,?,?,?,?)");
			$result->execute(array($memberID,$receiverID,$receiver,$subject,$addmessage,$status,$yes));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		echo "good";
	}else{
		echo "not";
	}
?>