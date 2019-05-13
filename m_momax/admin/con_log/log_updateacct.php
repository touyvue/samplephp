<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];	
		$result=mysql_query("select * from $db_user where user_id = '$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if ($row['user_id']==$user_id){	
		  		$first_name = $row['first_name'];
				$email = $row['email'];
				$seclevel = $row['user_right'];
				$loginid = $row['login_id'];
				$password = $row['password'];
				$user_flag = "yes";
			}
		}//end while loop
		$miss_pass = "no";
		if ($email=="" or $password==""){
			if ($password==""){
				$miss_pass = "yes";
			}
			$password = "";
		}else{
			$password = "password";
		}
		
		//get group name
		$result=mysql_query("select * from $db_group_user where user_id = '$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_admin_id = $row['group_admin_id'];
			$gr_admin_set = $row['admin'];
		}
		$result=mysql_query("select * from $db_group_admin where group_admin_id='$grp_admin_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_name = $row['group_name'];
			$grp_cod =  $row['group_code'];
			if ($user_id==$row['user_id']){
				$gr_admin = "yes";
			}else {
				$gr_admin = "no";
			}
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$first_name = $_POST['first_name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			//if ($_POST['grp_admin_yn']=="yes"){
			$change_grp = "no";
			$inactive_my_grp = "no";
			$grp_code = $_POST['grp_cod'];
			$org_grp_code = $_POST['grp_cod_yn'];
			$miss_pass = $_POST['mis_pas'];
			if ($grp_code != $org_grp_code) {
				//check for group subscription & get group_admin_id
				$result=mysql_query("select * from $db_group_admin where group_code='$grp_code'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$grp_no_user = $row['no_user'];
					$grp_package = $row['lic_package'];
					if (($grp_package=="medium" and $grp_no_user<5)or($grp_package=="small" and $grp_no_user<2)or($grp_package=="large")){
						$grp_admin_id = $row['group_admin_id'];
						$change_grp = "yes";	
					}
				}
				//check for owner to original group_admin
				$result=mysql_query("select * from $db_group_admin where group_code='$org_grp_code'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$user_id){	
						$inactive_my_grp = "yes";
					}
				}
			}				
			if ($_POST['new_user']=="yes"){
				$email = $_POST['email'];
				$status = "no";
			}else {
				$email = $_POST['ex_email'];
				$status = "yes";
			}
			
			if ($email!="" and $password!=""){				  	
				//rename and upload file
				$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/m_pic/"; //all pics save in the main root
				$target_path = $_SERVER['DOCUMENT_ROOT']."/images/m_pic/"; //all pics save in the main root
				$target_path = $target_path.$user_id.".". end(explode(".", $_FILES['mx_self']['name']));
				$pix_name = $user_id.".". end(explode(".", $_FILES['mx_self']['name']));
				//if upload image
				if ($_FILES['mx_self']['name']!=""){
					$result=mysql_query("select * from $db_post_pix where user_id = '$user_id'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$cur_pix = $row['post_pix_name'];
					}
					if (file_exists($target_path_on.$cur_pix)){
						unlink($target_path_on.$cur_pix); //rename($target_path,$target_path_old);
					}
					if(move_uploaded_file($_FILES['mx_self']['tmp_name'], $target_path)) {  //chmod($target_path, 777); 
						$sql=sprintf("UPDATE $db_post_pix SET post_pix_name='$pix_name' WHERE user_id='$user_id'");
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());
						} 
					} 
				}
				
				//update group_admin_id
				if ($change_grp == "yes"){
					$sql=sprintf("UPDATE $db_group_user SET group_admin_id='$grp_admin_id', admin='no' WHERE user_id='$user_id'");
					if (!mysql_query($sql,$connection)){
						die('Error: ' . mysql_error());
					}
					if ($inactive_my_grp == "yes"){
						$sql=sprintf("UPDATE $db_group_admin SET active='no' WHERE user_id='$user_id' and group_code='$org_grp_code'");
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());
						}
					}
				}	
				if ($password != "password"){
					$hash_cost_log2 = 8;
					$hash_portable = FALSE;
					$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
					$hash = $hasher->HashPassword($password);
					$sql=sprintf("UPDATE $db_user SET first_name='$first_name', email='$email', password='$hash', active='$status' WHERE user_id='$user_id'");
					if (!mysql_query($sql,$connection)){
			  			die('Error: ' . mysql_error());
			  		}
					//if new invited user group, update active_dadte
					if ($miss_pass == "yes"){
						$a_date = date('Y-m-d');
						$sql=sprintf("UPDATE $db_user SET date_active='$a_date' WHERE user_id='$user_id'");
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());
						}
					}
				}else {
					$sql=sprintf("UPDATE $db_user SET first_name='$first_name', email='$email', active='$status' WHERE user_id='$user_id'");
					if (!mysql_query($sql,$connection)){
			  			die('Error: ' . mysql_error());
			  		}
				}
				$_SESSION['user']=$first_name;
				unset($hasher);
				if ($_POST['new_user']=="yes"){
					$form_submit = "yes";
					$len = 19;
					$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
					$max=strlen($base)-1;
					$confirmcode='';
					mt_srand((double)microtime()*1000000);
					while (strlen($confirmcode)<$len+1)
					$confirmcode.=$base{mt_rand(0,$max)};  
					$confirmcode = substr($confirmcode,0,5)."".substr($user_id,0,3)."".substr($confirmcode,5,5)."".substr($user_id,3,3)."".substr($confirmcode,10,10);
					
					$message =  "<html><head></head><body><h2>Welcome to Maxmoni</h2><h3>A friendly mobile app for sharing budget plans and tracking daily expenses.</h3><p>Please <a href='".$mx008cu."&cid=".$confirmcode."'>click here</a> to confirm your e-mail address.<br>";
					//$message =  $message."<br>Thank you for using ".$grp_name."!<br><br></body></html>";
					$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>www.maxmoni.com</a><br><br></body></html>";
					$message_record = "<html><head></head><body><h1>".$grp_name." New User</h1><p>Email: ".$email."</p><br><br><br></body></html>";
					//$message_record = "<html><head></head><body><h1>Maxmoni New User</h1><p>Email: ".$email."</p><br><br><br></body></html>";
					$to = $email;
					$to_record = "info@maxmoni.com";
					$subject = "Confirm registration for Maxmoni";
					$subject_record = "New user - Maxmoni";
					$from = "emailconfirm@maxmoni.com";
					$headers  = "From: Maxmoni<".$from.">\r\n";
					$headers .= "Content-type: text/html\r\n";
										
					mail($to,$subject,$message,$headers);
					mail($to_record,$subject_record,$message_record,$headers);
					
					$_SESSION['user']="";
					$_SESSION['uid']="";
					$_SESSION['login']="";
					
				}else {
					print "<script>";
					print "self.location='".$mx005."';";
					print "</script>";	
				}
			}else{
				echo "Form is not completed!";
			}
		}
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>