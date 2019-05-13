<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	include ('../../../m_inc/p_hash.php');
		
	$state = $_POST['state'];
	$consortID = $_POST['consortID'];
	$groupID = $_POST['groupID'];
	$exMem = $_POST['exMem'];
	$memberID = $_POST['exMid'];
	$active = "yes";
	$no = "no";
	$todayDate = date('Y-m-d');
	
	$firstName = $_POST['fName'];
	$firstName = str_replace('"', "", $firstName); //remove " from note
	$lastName = $_POST['lName'];
	$lastName = str_replace('"', "", $lastName); //remove " from note
	$email = $_POST['nemail'];
	$password = "Loveland1"; //$_POST['npassword'];
	$adminYN = $_POST['admin'];
	if ($adminYN == "yes"){
		$gRights = 3;
	}else{
		$gRights = 1;
	}
	
	$rstreet = $_POST['rstreet'];
	$rcity = $_POST['rcity'];
	$rstate = $_POST['rstate'];
	$rzip = $_POST['rzip'];
	$rphone = $_POST['rphone'];
	
	if ($exMem != "yes"){
		$hash_cost_log2 = 8;
		$hash_portable = FALSE;
		$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
		$hash = $hasher->HashPassword($password);
		
		$genNum = rand(10000000,99999999);
		try{//insert new member
			$result = $db->prepare("INSERT INTO $db_member (consortium_id,first_name,last_name,street,city,state,zip,phone,email,password,date_active,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
			$result->execute(array($genNum,$firstName,$lastName,$rstreet,$rcity,$rstate,$rzip,$rphone,$email,$hash,$todayDate,$active));
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		unset($hasher);
		try{//get new memberID
			$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=? ORDER BY member_id ASC");
			$result->execute(array($genNum)); //get recurring info
		} catch(PDOException $e) {
			echo "message002 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$memberID = $row['member_id'];
		}
		try{//update project details
			$result = $db->prepare("UPDATE $db_member SET consortium_id=? WHERE member_id=?");
			$result->execute(array($consortID,$memberID));
		} catch(PDOException $e) {
			echo "message003 - Sorry, system is experincing problem. Please check back.";
		}
		
		//insert 1 account/////////////////////////////////////////
		$acctName = "Spending Checking";
		$acctDesc = "General expenses";
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
			$result->execute(array($acctName,1,$acctDesc,$memberID,$active));
		} catch(PDOException $e) {
			echo "message004 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new accountID
			$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? ORDER BY account_id ASC");
			$result->execute(array($memberID)); //get recurring info
		} catch(PDOException $e) {
			echo "message005 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$accountID = $row['account_id'];
		}
		try{//update project details
			$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
			$result->execute(array($memberID,3,$accountID,$active));
		} catch(PDOException $e) {
			echo "message006 - Sorry, system is experincing problem. Please check back.";
		}
		//1 budget/////////////////////////////////////////
		$budName = "Monthly Budget";
		$budDesc= "General budget";
		try{
			$result = $db->prepare("INSERT INTO $db_budget (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
			$result->execute(array($budName,1,$budDesc,$memberID,$active));
		} catch(PDOException $e) {
			echo "message007 - Sorry, system is experincing problem. Please check back.";
		}
		try{//get new budgetID
			$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? ORDER BY budget_id ASC");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			echo "message008 - Sorry, system is experincing problem. Please check back.";
		}
		foreach ($result as $row){
			$budgetID = $row['budget_id'];
		}
		try{//update budget details
			$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
			$result->execute(array($memberID,3,$budgetID,$active));
		} catch(PDOException $e) {
			echo "message009 - Sorry, system is experincing problem. Please check back.";
		}
		$catNameArr = array("Electric & heat","Water","Cell phone","Food","Dinning","Misc expenses","Income","Emergency saving","Credit card");
		$catNameArrCt = count($catNameArr);
		for($i=0;$i<$catNameArrCt;$i++){
			try{
				$result = $db->prepare("INSERT INTO $db_category (list_order,category,description,member_id,active) VALUES (?,?,?,?,?)");
				$result->execute(array($i,$catNameArr[$i],$catNameArr[$i],$memberID,$active));
			} catch(PDOException $e) {
				echo "message010 - Sorry, system is experincing problem. Please check back.";
			}
		}
		
		//send email to reset password
		$todate = date('Y-m-d');
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$len = 19;
		$base='123456789ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
		$max=strlen($base)-1;
		$reset_code='';
		mt_srand((double)microtime()*1000000);
		while (strlen($reset_code)<$len+1)
		$reset_code.=$base{mt_rand(0,$max)};   
		$reset_code = substr($reset_code,0,5)."".substr($memberID,0,3)."".substr($reset_code,5,5)."".substr($memberID,3,3)."".substr($reset_code,10,10);
		
		try{
			$result = $db->prepare("INSERT INTO $db_reset_log(date_request,user_email,member_id,reset_code,ip_address,active) VALUES (?,?,?,?,?,?)");
			$result->execute(array($todate,$email,$memberID,$reset_code,$ip_address,$no));
		} catch(PDOException $e) {
			echo "message011 - Sorry, system is experincing problem. Please check back.";
		}
			
		$message =  "<html><head></head><body><h3>Welcome to Maxmoni!</h3><p>Thank you for signing up.<br></p><p>A Maxmoni account has been set up for you. If you are not the right owner of this email (".$email."), please delete this email.  Otherwise please click on the link below to set up your password.<br>";
		$message =  $message."<br>Link: <a href='".$mx008cp."&sg=new&cid=".$reset_code."'>Click here to set password</a><br>";
		$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br>Easy group budget tracking!<br><br><br></body></html>";
		$to = $email;
		$subject = "Password setup in Maxmoni.com";
		$from = "emailconfirm@maxmoni.com";

		$headers  = "From: Maxmoni<".$from.">\r\n";
		$headers .= "Content-type: text/html\r\n";
		mail($to,$subject,$message,$headers);
		
		$chk_login = "Set up password";
	}
	
	try{//insert new member group rights
		$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,active) VALUES (?,?,?,?,?)");
		$result->execute(array($groupID,$memberID,1,$gRights,$active));
	} catch(PDOException $e) {
		echo "message012 - Sorry, system is experincing problem. Please check back.";
	}
	echo "done";
	
?>