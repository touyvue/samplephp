<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		$submit_flag = "";
		if ($_POST['submit_flag']== "yes"){		
			$submit_flag = $_POST['submit_flag'];
		}
		
		//get group name
		$result=mysql_query("select * from $db_group_user where user_id = '$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_admin_id = $row['group_admin_id'];
		}
		$result=mysql_query("select * from $db_group_admin where group_admin_id='$grp_admin_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_name = $row['group_name'];
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST" and $submit_flag=="yes"){
			$name = $_POST['f_name'];
			$user_email = $_POST['email'];//$_POST['phone'];
			$owner_email = $_POST['owner_email'];
			$loginid = $_POST['loginid'];
			$invited_code = $_POST['invited_code'];
			$todate = date('Y-m-d');
			$user_id_found = "no";
			
			if ($owner_email!=""){			
				$result=mysql_query("select user_id from $db_user where email='$owner_email'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$owner_group_id = $row['user_id'];
			  	}
				$result=mysql_query("select group_id from $db_invite where group_id='$owner_group_id' and user_id='$user_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$user_id_found = "yes";
			  	}
			  	
				if ($user_id_found=="no"){
					$sql="INSERT INTO $db_invite(group_id, user_id, invite_code, date_invite, active) VALUES ('$owner_group_id','$user_id','$invited_code','$todate','no')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}
					
					$message =  "<html><head></head><body><h2>Budget & Accounts Request</h2><br><p>".$name." would like to have rights to your budget or/and accounts.  Please <a href='".$index_url."'>log in</a> to accept the invitation and grant the appropriate permission.<br>";
					$message =  $message."<br>Invitation code: ".$invited_code."<br>";
					$message =  $message."<br>Thank you<br><br><br><br></body></html>";
					$to = $owner_email;
					$subject = "Confirm rights in Maxmoni";
					$from = "emailconfirm@maxmoni.com";
					//$headers = "From:" . $from;
					$headers  = "From: <".$from.">\r\n";
					$headers .= "Content-type: text/html\r\n";
					mail($to,$subject,$message,$headers);
					$submit_flag = "post";
				}else{
					$submit_flag = "no";
				}
			}else { //if no owner's email, go back
				print "<script>";
				print "self.location='".$mx005."';";
				print "</script>";
			}
		}else{  //display form for request to be submitted
			$result=mysql_query("select * from $db_user") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				if ($row['user_id']==$user_id){	
					$name = $row['first_name'];
					$email = $row['email'];
					$seclevel = $row['user_right'];
					$loginid = $row['login_id'];
					$user_flag = "yes";
				}
			}//end while loop
			
			$len = 19;
			$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
			$max=strlen($base)-1;
			$invited_code='';
			mt_srand((double)microtime()*1000000);
			while (strlen($invited_code)<$len+1)
			$invited_code.=$base{mt_rand(0,$max)};  
			$invited_code = substr($invited_code,0,5)."".substr($user_id,0,2)."".substr($invited_code,5,5)."".substr($user_id,2,2)."".substr($invited_code,10,5)."".substr($user_id,4,6)."".substr($invited_code,15,5);
		}
		mysql_close($connection);
	}else{//end else statement
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>