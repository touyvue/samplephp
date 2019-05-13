<?php
    $active_page = $_GET["pa"]; 
	$active_page = substr($active_page,5,1).substr($active_page,9,2).substr($active_page,13,2).substr($active_page,20,2);
	if ($active_page=="mx005nu"){
		if ($_SESSION['login']!="yes"){
			$form_submit = "";			
			mysql_select_db($db, $connection) or die (mysql_error());
			if($_SERVER['REQUEST_METHOD'] == "POST" and $_POST['act']=="go"){
				$first_name = $_POST['first_name'];
				$email = $_POST['email'];
				$loginid = $_POST['loginid_new'];
				$password = $_POST['password_new'];
				$todate = date('Y-m-d'); 
	            $found_user = "";
					
				if ($first_name!="" and $email!="" and $loginid!="" and $password!=""){
					$sql="INSERT INTO $db_user(first_name, email, admin_sec, portfolio_sec, budget_sec, saving_sec, login_id, password, date_join, active) VALUES ('$first_name','$email','1','1','1','1','$loginid','$password','$todate','no')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}
					
					$result=mysql_query("select user_id from $db_user where login_id='$loginid'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
				  		$user_id = $row['user_id'];
				  	}
					  				  	
				  	//Phase one of Maxmoni only do Bank type account... 9/21/11
				  	////create one bank account for initial signup////
				  	$sql="INSERT INTO $db_account_type(account_type, account_usage, account_description, user_id, active) VALUES ('Checking Account','Bank','My bank checking account','$user_id','no')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}
	
				  	////create the first activity type for the new user - stop using activity type for now -3/24/12 Tou
				  	////turn back on 8/3/12 - need to expense type tracking report - tou
				  	$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES 
				  	     ('Income','Income','General income','$user_id','no'),
				  	     ('Phone','Expense','Phone bill','$user_id','no'),
				  	     ('Rent','Expense','Rent payment','$user_id','no'),
				  	     ('Electric bill','Expense','Electric bill','$user_id','no'),
				  	     ('Credit card bill','Expense','Electric bill','$user_id','no'),
				  	     ('Water bill','Expense','Water utility bill','$user_id','no'),
				  	     ('Clothing','Expense','Clothing for family','$user_id','no'),
				  	     ('Gorceries','Expense','Household gorcery','$user_id','no'),
				  	     ('Car insurance','Expense','Car insurance expense','$user_id','no'),
				  	     ('Dining out','Expense','Dining out','$user_id','no'),
				  	     ('Internet bill','Expense','Internet connection expense','$user_id','no'),
				  	     ('Emergency saving','Saving','Emergency fund saving','$user_id','no'),
				  	     ('Vacation saving','Saving','vacation saving','$user_id','no'),
				  	     ('Retirement saving','Saving','Retirement saving','$user_id','no'),
				  	     ('Student loan','Expense','Student loan payment','$user_id','no')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}	  	
				  	
					////get the newly income activity ID////				
					$result=mysql_query("select activity_type_id from $db_activity_type where user_id='$user_id' and activity_type='Income'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
				  		$activity_type_id = $row['activity_type_id'];
				  	}
					////get the newly created account ID////				
					$result=mysql_query("select account_type_id from $db_account_type where user_id='$user_id'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
				  		$account_type_id = $row['account_type_id'];
				  	}
				  	////insert account rights for the specific account - 1 respresents the owner////
				  	$sql="INSERT INTO $db_account(user_id, access_right, account_type_id,active) VALUES ('$user_id','1','$account_type_id','no')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}
				  	////create first transaction for the account////
				  	$sql="INSERT INTO $db_transaction(account_type_id, user_id, transaction_date, transaction_order, amount_in, amount_out, description, activity_type_id, saving, transaction_type_id, budget, balance_date,active) VALUES ('$account_type_id','$user_id','$todate', '1','0', '0','Starting','$activity_type_id','no','100100','no','$todate','no')" or die(mysql_error());
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
					$confirmcode = substr($confirmcode,0,5)."".substr($user_id,0,3)."".substr($confirmcode,5,5)."".substr($user_id,3,3)."".substr($confirmcode,10,10);
					
					$message =  "<html><head></head><body><h1>Maxmoni</h1><h2>A simple and consistent way to manage your finance.</h2><p>Please <a href='".$mx008cu."&cid=".$confirmcode."'>Click here</a> to confirm your e-mail address.<br>";
					$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br><br><br><br></body></html>";
					$message_record = "<html><head></head><body><h1>Maxmoni New User</h1><p>Name: ".$first_name."<br>Email: ".$email."</p><br><br><br></body></html>";
					$to = $email;
					$to_record = "info@maxmoni.com";
					$subject = "Confirm registration for Maxmoni";
					$subject_record = "New user - Maxmoni";
					$from = "emailconfirm@maxmoni.com";
					$headers  = "From: Maxmoni<".$from.">\r\n";
					$headers .= "Content-type: text/html\r\n";
					mail($to,$subject,$message,$headers);
					mail($to_record,$subject_record,$message_record,$headers);
				}else{
					echo "Form is not completed!";
				}
			}
			mysql_close($connection);
		}else{
			print "<script>";
			print "self.location='".$mx005nu."';";
			print "</script>";
		}
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>