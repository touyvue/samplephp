<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$memberTotInst = new memberInfoC();
	$memberID = $_POST['mid'];
  	$requestID = $_POST['reqID'];
	$sendType = $_POST['sendType'];
	$yes = "yes";
	
	try{//last name				
		$result = $db->prepare("SELECT request_id,sender_id,receiver_id,receiver,subject,message,mdate,status FROM $db_request WHERE request_id=?");
		$result->execute(array($requestID));
		
	} catch(PDOException $e) {
		echo "message001 - Sorry, system is experincing problem. Please check back.";
	}
	$itemCount = $result->rowCount();
	$requestMsg = "";
	if ($itemCount > 0){
		foreach ($result as $row) {
			$requestMsg = $requestMsg.'{"reqid":"'.$row['request_id'].'",';
			if ($sendType == 1){
				$requestMsg = $requestMsg.'"from":"'.$memberTotInst->memberNameReqF($row['sender_id']).'",';
				$requestMsg = $requestMsg.'"to":"no",';
				$requestMsg = $requestMsg.'"senderid":"'.$row['sender_id'].'",';
			}
			if ($sendType == 2){
				$requestMsg = $requestMsg.'"from":"'.$memberTotInst->memberNameReqF($row['receiver_id']).'",';
				$requestMsg = $requestMsg.'"to":"yes",';
				$requestMsg = $requestMsg.'"receiver_id":"'.$row['receiver_id'].'",';
			}
			$requestMsg = $requestMsg.'"subject":"'.str_replace('\\','',$row['subject']).'",';
			$msg = str_replace(array("\r", "\n"), "<br>", str_replace('\\','',$row['message']));
			$requestMsg = $requestMsg.'"message":"'.$msg.'",';
			$mdate = date('m-d-Y', strtotime($row['mdate']));
			$requestMsg = $requestMsg.'"mdate":"'.$mdate.'",';
			$requestMsg = $requestMsg.'"status":"'.$row['status'].'"}';
		}
	}
	echo $requestMsg;
?>