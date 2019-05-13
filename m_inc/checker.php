<?php
	session_start();
	$id = (isset($_GET['id']) ? $_GET['id'] : null);
	$id = substr($id,5,1).substr($id,9,2).substr($id,13,2).substr($id,20,2);
	$yes = "yes";
	$no = "no";
	
	////Check for p_start session - public active session if is not set////
	if(!isset($_SESSION['p_start'])){
		try{
			$result = $db->prepare("SELECT guest_id FROM $db_guest ORDER BY guest_id ASC");
			$result->execute();
		} catch(PDOException $e){
			echo "Sorry, the system is experiencing difficulty.";
		}
		foreach ($result as $row){
			$guest_id = $row['guest_id'];
			$guest_id += 1;	
		}
		$_SESSION['p_start'] = $guest_id;
	}
	////check for session timeout at 30 minutes
	if (isset($_SESSION['last_act']) and (time() - $_SESSION['last_act'] > 1800)){// last request was more than 30 minutes ago
		$datetime_stamp = date('Y-m-d-h-i-s-a');
		$time_out = $_SESSION['log_time'];
		$log_ss_id = $_SESSION['log_ss'];
		$act_logout = "sess_end";
		
		try{
			$sql = $db->prepare("UPDATE $db_inout SET logout=?,logout_action=?,active=? WHERE inout_id=? AND login_ss_id=?");
			$sql->execute(array($datetime_stamp,$act_logout,$no,$time_out,$log_ss_id));
		} catch(PDOException $e){
			echo "Sorry, the system is experiencing difficulty.";
		}
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
	}
	if (isset($_SESSION['last_act']) and (time() - $_SESSION['last_act'] <= 1800)){
		$_SESSION['last_act'] = time(); // update last activity time stamp
	}
	
	if ($id == "mx00000"){
		$datetime_stamp = date('Y-m-d-h-i-s-a');
		$time_out = $_SESSION['log_time'];
		$log_ss_id = $_SESSION['log_ss'];
		if ($act_logout != "terminate"){
			$act_logout = "logout";
		}
		try{
			$sql = $db->prepare("UPDATE $db_inout SET logout=?,logout_action=?,active=? WHERE inout_id=? AND login_ss_id=?");
			$sql->execute(array($datetime_stamp,$act_logout,$no,$time_out,$log_ss_id));
		} catch(PDOException $e) {
			echo "Sorry, the system is experiencing difficulty.";
		}	
		
		//delete all report files when logout
		$oldReport001act = 'm_reports/rpt001act_r'.$_SESSION['mid'].'.php'; //account report001
		$oldReport001bud = 'm_reports/rpt001bud_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldReport001trkm = 'm_reports/rpt001trk_m'.$_SESSION['mid'].'.php'; //budget report001
		$oldReport001trkr = 'm_reports/rpt001trk_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldReport001trkdt = 'm_reports/rpt001trkdt_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldReport001trkCa = 'm_reports/rpt001trkCa_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldbud001mview_r = 'm_reports/bud001mview_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldbud001qview_r = 'm_reports/bud001qview_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldact001mview_r = 'm_reports/act001mview_r'.$_SESSION['mid'].'.php'; //budget report001
		$oldact001qview_r = 'm_reports/act001qview_r'.$_SESSION['mid'].'.php'; //budget report001
		
		if (file_exists($oldReport001act)){
			unlink($oldReport001act);
		}
		if (file_exists($oldReport001bud)){
			unlink($oldReport001bud);
		}
		if (file_exists($oldReport001trkm)){
			unlink($oldReport001trkm);
		}
		if (file_exists($oldReport001trkr)){
			unlink($oldReport001trkr);
		}
		if (file_exists($oldReport001trkr)){
			unlink($oldReport001trkdt);
		}
		if (file_exists($oldReport001trkCa)){
			unlink($oldReport001trkCa);
		}
		if (file_exists($oldbud001mview_r)){
			unlink($oldbud001mview_r);
		}
		if (file_exists($oldbud001qview_r)){
			unlink($oldbud001qview_r);
		}
		if (file_exists($oldact001mview_r)){
			unlink($oldact001mview_r);
		}
		if (file_exists($oldact001qview_r)){
			unlink($oldact001qview_r);
		}
		
		$_SESSION['member']="";
		$_SESSION['login']="";
		$_SESSION['mid']="";
		$_SESSION['log_ss']="";
		$_SESSION['seclevel']="";
		$_SESSION['budget_sec']="";
		$_SESSION['log_time']="";
		session_destroy();
			
		print "<script>";
		print "self.location='".$index_url_out."'";
		print "</script>";
	} else{
		if($_SERVER['REQUEST_METHOD'] == "POST" and $_GET["p"] == "fgpass"){
			if ($_POST["inputEmail"]!=""){
				$inEmail = trim($_POST["inputEmail"],"");
				try{					
					$result = $db->prepare("SELECT email,member_id FROM $db_member WHERE email=? and active=?");
					$result->execute(array($inEmail,$yes));
				} catch(PDOException $e) {
					echo "Sorry, the system is experiencing difficulty.";
				}
				foreach ($result as $row) {
					$email = $row['email'];
					$foundMemberID = $row['member_id'];
				}
				if ($email == ""){
					$chk_login = 'Email not found, please contact <a href="mailto:info@maxmoni.com">Admin</a>';
				}else{
					//catcher infor
					$todate = date('Y-m-d');
					$ip_address = $_SERVER['REMOTE_ADDR'];
					$len = 19;
					$base='123456789ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
					$max=strlen($base)-1;
					$reset_code='';
					mt_srand((double)microtime()*1000000);
					while (strlen($reset_code)<$len+1)
					$reset_code.=$base{mt_rand(0,$max)};   
					$reset_code = substr($reset_code,0,5)."".substr($foundMemberID,0,3)."".substr($reset_code,5,5)."".substr($foundMemberID,3,3)."".substr($reset_code,10,10);
					
					try{
						$result = $db->prepare("INSERT INTO $db_reset_log(date_request,user_email,member_id,reset_code,ip_address,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($todate,$email,$foundMemberID,$reset_code,$ip_address,$no));
					} catch(PDOException $e) {
						echo "Sorry, the system is experiencing difficulty.";
					}
						
					$message =  "<html><head></head><body><h3>Reset Password for your Maxmoni account.</h3><p>You requested for a password change.  If you are not the right owner of this email (".$email."), please delete this email.  Otherwise please click on the link below to reset your password.<br>";
					$message =  $message."<br>Link: <a href='".$mx008cp."&cid=".$reset_code."'>Click here to reset password</a><br>";
					$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br><br><br><br></body></html>";
					$to = $email;
					$subject = "Password reset in Maxmoni.com";
					$from = "emailconfirm@maxmoni.com";
	
					$headers  = "From: Maxmoni<".$from.">\r\n";
					$headers .= "Content-type: text/html\r\n";
					mail($to,$subject,$message,$headers);
					
					$chk_login = "Reset email is coming your way!";
				}
			}else{
				$chk_login = "Please enter email";  //enter email
			}
		}
		if($_SERVER['REQUEST_METHOD'] == "POST" and $_GET["p"] == "login"){
			if ($_POST["inputEmail"]!="" and $_POST["inputPassword"]!=""){
				$inEmail = trim($_POST["inputEmail"],""); 
				$inPassword = trim($_POST["inputPassword"],"");
				
				try{					
					$result = $db->prepare("SELECT * FROM $db_member WHERE email=?");
					$result->execute(array($inEmail));
				} catch(PDOException $e) {
					echo "Sorry, the system is experiencing difficulty.";
				}
				foreach ($result as $row) {
					$member_active = $row['active'];
					$member_pass = $row['password'];
					$member_id = $row['member_id'];
					$first_name = str_replace('\\','',$row['first_name']);
				}				
				if ($result->rowCount() == 0){
					$_SESSION['member']="";
					$_SESSION['mid']="";
					$_SESSION['login']="no";
					$chk_login = 1; //incorrect id & password
				}else{
					$db_pass = $member_pass;
					//check password
					$hash_cost_log2 = 8;
					$hash_portable = FALSE;
					$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
					if ($hasher->CheckPassword($inPassword, $db_pass) and $member_active=="yes") { 
						$datetime_stamp = date('Y-m-d-h-i-s-a');
						$datetime_stamp = (string)$datetime_stamp;
						$ip_address = $_SERVER['REMOTE_ADDR'];
						$lg_id = $member_id.date('his');

						try{
							$result = $db->prepare("INSERT INTO $db_inout(member_id,login_ss_id,login,ip_address,active) VALUES (?,?,?,?,?)");
							$result->execute(array($member_id, $lg_id, $datetime_stamp,$ip_address,$yes));
						} catch(PDOException $e) {
							echo "Sorry, the system is experiencing difficulty.";
						}
						try{					
							$result = $db->prepare("SELECT inout_id FROM $db_inout WHERE member_id=? AND login_ss_id=? ORDER BY inout_id ASC");
							$result->execute(array($member_id,$lg_id));
						} catch(PDOException $e) {
							echo "Sorry, the system is experiencing difficulty.";
						}
						foreach ($result as $row) {
							$time_in_id = $row['inout_id'];
						}
						$_SESSION['member']=$first_name;
						$_SESSION['mid']=$member_id;
						$_SESSION['login']="yes";
						$_SESSION['log_ss']=$lg_id;
						$_SESSION['log_time']=$time_in_id;
						$_SESSION['last_act'] = time(); //set session for 30 minutes
						//go to the main overview page
						print "<script>";
						print "self.location='".$index_url."'";
						print "</script>";
					} else { 
						$_SESSION['member']="";
						$_SESSION['mid']="";
						$_SESSION['login']="no";
						$chk_login = 2; //incorrect id & password
					} 
					unset($hasher);
				}//end of else statement
			}else{ 
				$_SESSION['member']="";
				$_SESSION['mid'] = "";
				$_SESSION['login']="no";
				$chk_login = 3;  //enter id & password
			}
		}//end of login form posting data
	}//end of else if $id != out
	
	$active_page = $_GET["pa"]; 
	$active_page = substr($active_page,5,1).substr($active_page,9,2).substr($active_page,13,2).substr($active_page,20,2);
?>