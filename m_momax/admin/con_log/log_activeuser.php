<?php
	$user_id = $_SESSION['uid'];
	if ($_SESSION['login']=="yes"){// and ($user_id == "100156" or $user_id == "100101")){
		$user_id = $_SESSION['uid'];	
				
		//get group name and status
		$user_group = "";
		$result=mysql_query("select * from $db_group_admin where associate='$user_id' order by active DESC, group_admin_id ASC ") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_name = $row['group_name'];
			$grp_id = $row['group_admin_id'];
			$grp_active = $row['active'];
			$grp_sub = $row['lic_package'];
			$grp_sub_active = $row['lic_active'];
			$grp_no = $row['no_user'];
			$grp_owner_name = getname($row['user_id']);
			$user_group = $user_group."<tr><td>".$grp_name."</td><td>".$grp_owner_name."</td><td>".$grp_sub."</td><td>".$grp_no."</td><td>".$grp_active."</td></tr>";
		}
		
		//update group
		if ($_GET['grp'] == "upt"){
			$group_id = $_GET['igr'];
			$result=mysql_query("select * from $db_group_admin where group_admin_id='$group_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				$group_name = $row['group_name'];
				//$group_web = $row['website'];
				$group_code = $row['group_code'];
				$group_active = $row['active'];
				$grp_owner_email = getemail($row['user_id']);
			}
		}
		if ($_GET['grp'] == "new"){
			//generate temp reference_number 
			$group_code = $user_id.rand(111000, 999999);
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST" and $_GET['grp']=="new"){
			//when new or update group information is posted, update the group_admin table here// Tou - 4/3/14
			//come back to this for group admin monitoring//
			$grp_name = $_POST['grp_nam'];
			$grp_owner = $_POST['grp_owner'];
			$grp_email = $_POST['email'];
			if ($grp_email != ""){
				//get user id//
				$first_name = $grp_owner;
				$email = $grp_email;
				$loginid = "mengmozhong123";
				$password = "";
				$todate = date('Y-m-d');
				
				//create guest user account with name, email, login_id and password//
				$sql="INSERT INTO $db_user(first_name, email, admin_sec, portfolio_sec, budget_sec, saving_sec, login_id, password, date_join, active) VALUES ('$first_name','$email','1','1','1','1','$loginid','$password','$todate','no')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				//get user id//
				$result=mysql_query("select user_id from $db_user where login_id='$loginid'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$user_id_new = $row['user_id'];
				}
				
				//set login field to space since just guest//
				$sql=sprintf("UPDATE $db_user SET login_id='' WHERE user_id='$user_id_new'");
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());	
				}
				//create one bank account for initial signup//
				$sql="INSERT INTO $db_account_type(account_type, account_usage, account_description, user_id, active) VALUES ('Checking Account','Bank','My bank checking account','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				//get the newly created account ID//			
				$result=mysql_query("select account_type_id from $db_account_type where user_id='$user_id_new'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$account_type_id = $row['account_type_id'];
				}
				//insert account rights for the specific account - 1 respresents the owner////
				$sql="INSERT INTO $db_account(user_id, access_right, account_type_id,active) VALUES ('$user_id_new','1','$account_type_id','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				
				$sql="INSERT INTO $db_post_pix(post_pix_name, user_id, active) VALUES ('person.png','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				
				//generate temp reference_number 
				$grp_id = $user_id_new.rand(111000, 999999);
				$sql="INSERT INTO $db_group_admin(group_name, website, group_code, lic_package, lic_active, no_user, user_id, associate, active) VALUES ('$grp_name','','$grp_id','F-Group','no','1','$user_id_new','$user_id','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}						
				$result=mysql_query("select * from $db_group_admin where group_code='$grp_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$grp_admin_id = $row['group_admin_id'];
				}
				$sql="INSERT INTO $db_group_user(group_admin_id, user_id, admin, active) VALUES ('$grp_admin_id','$user_id_new','yes','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				
				//create income activity_type 
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Work paycheck','Income','Money from work','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				//create saving activity_type
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Saving for emergency','Saving','Money from emergency','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				//create expense activity_type
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Mortgage','Expense','Mortgage payment','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Rent','Expense','Rent payment','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Electric and Gas','Expense','Electric and gas bill','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Cell phone','Expense','Cell phone bill','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Credit Card','Expense','Credit Card bill','$user_id_new','yes')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}

				$form_submit = "yes";
				$len = 19;
				$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
				$max=strlen($base)-1;
				$confirmcode='';
				mt_srand((double)microtime()*1000000);
				while (strlen($confirmcode)<$len+1)
				$confirmcode.=$base{mt_rand(0,$max)};  
				$confirmcode = substr($confirmcode,0,5)."".substr($user_id_new,0,3)."".substr($confirmcode,5,5)."".substr($user_id_new,3,3)."".substr($confirmcode,10,10);
				$qry_string = $mx002gb."&cid=".$confirmcode."&gid=".$grp_id."&act=act";
				
				$message =  "<html><head></head><body><h2>Welcome to Maxmoni</h2><h3>A friendly mobile app for sharing budget plans and tracking daily expenses.</h3><p>Please <a href='".$qry_string."'>click here</a> to confirm your registration and start using Maxmoni.<br>";
				$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>www.maxmoni.com</a><br><br></body></html>";
				$message_record = "<html><head></head><body><h1>".$grp_name." New Signup</h1><p>Email: ".$email."</p><br><br><br></body></html>";
				$to = $email;
				$to_record = "info@maxmoni.com";
				$subject = "Confirm your registration at Maxmoni.com";
				$subject_record = "New signup - Maxmoni";
				$from = "emailconfirm@maxmoni.com";
				$headers  = "From: Maxmoni<".$from.">\r\n";
				$headers .= "Content-type: text/html\r\n";
									
				mail($to,$subject,$message,$headers);  //to group
				mail($to_record,$subject_record,$message_record,$headers); //to maxmoni admin				
			}	
		}
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>