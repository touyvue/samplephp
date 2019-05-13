<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];		
		$requestor_id = $_GET['rid'];
		$action_code = $_GET['act'];
		$requestor_no = $_GET['rin'];
		$dir_flag = "";
		$todate = date('Y-m-d');
				
		////display all invited users////
		if ($action_code=="" and $requestor_id==""){
			$requestor_user_id = array();
			$requestor_invite_code = array();
			$requestor_name = array();
			$requestor_email = array();
			$requestor_display = "";
			$requestor_ct = 0;
			
			//get all waiting invited users//
			$result=mysql_query("select user_id, invite_code from $db_invite where group_id='$user_id' and active='no'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				$requestor_user_id[$requestor_ct] = $row['user_id'];
				$requestor_invite_code[$requestor_ct] = $row['invite_code'];
				$requestor_ct += 1;
			}
			//get invited users name and email//
			for ($i=0; $i<count($requestor_user_id); $i++){
				$result=mysql_query("select first_name, email from $db_user where user_id='$requestor_user_id[$i]'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$requestor_display = $requestor_display."<tr><td class='tablepad1'>".$row['first_name']."</td><td class='tablepad'>".$row['email']."</td><td class='tablepad'><input type='textbox' name='i".$requestor_user_id[$i]."' id='i".$requestor_user_id[$i]."' onchange=".'"'."check_invitecode(document.invite_frm_view.i".$requestor_user_id[$i].".value,'".$requestor_user_id[$i]."')".'"'."></td><td class='tablepad'><a href='".$mx005cr."&rid=".$requestor_user_id[$i]."&act=acc&rin=".$requestor_ct."' onclick=".'"'."return(value_invitecode(document.invite_frm_view.i".$requestor_user_id[$i].".value,'".$requestor_user_id[$i]."'))".'"'.">accept</a> / <a href='".$mx005cr."&rid=".$requestor_user_id[$i]."&act=den&rin=".$requestor_ct."' onclick=".'"'."return(value_invitecode(document.invite_frm_view.i".$requestor_user_id[$i].".value,'".$requestor_user_id[$i]."'))".'"'.">denied</a></td></tr>";
				}
			}
		}
		////denie requestor processing////
		if ($requestor_id!="" and $action_code=="den"){
			$result=mysql_query("select * from $db_user where user_id='$requestor_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
		  		$first_name = $row['first_name'];
				$email = $row['email'];
			}
			$sql=sprintf("UPDATE $db_invite SET date_denied='$todate', active='non' WHERE group_id='$user_id' and user_id='$requestor_id'");
			if (!mysql_query($sql,$connection)){
		  		die('Error: ' . mysql_error());
		  	}
		  	$dir_flag = "den";
		  	//if more than 1 requestor, process the next one//
		  	if ($requestor_no > 1){
			  	print "<script>";
				print "self.location='".$mx005cr."';";
				print "</script>";
			}
		}
		////accept requestor processing////
		if ($requestor_id!="" and $action_code=="acc"){
			$result=mysql_query("select * from $db_user where user_id='$requestor_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
		  		$first_name = $row['first_name'];
				$email = $row['email'];
				$active = $row['active'];
			}
			
			$account_type = "";
			//get all checked accounts info//
			$result=mysql_query("select * from $db_account_type ORDER BY account_type_id ASC") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				if ($row['user_id']==$user_id and $row['active']=="yes"){
					$account_type = $account_type ."<tr><td class='inntablepad'><input type='checkbox' id='t".$row['account_type_id']."' name='t".$row['account_type_id']."' value'".$row['account_type_id']."' onclick='con_acct_sec(".$row['account_type_id'].")'> ".$row['account_type']."</td>";
					$account_type = $account_type ."<td class='inntablepad'><input id='l2".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='3' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 1 <input id='l3".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='2' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 2 <input id='l4".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='1' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 3</td></tr>";
				}//end if statement when user found
			}//end while loop to display active account within the group
			$dir_flag = "acc";
		}//end of if statement, accept
		
		if ($_SERVER['REQUEST_METHOD'] == "POST" and $action_code=="go"){
			$requestor_id = $_POST['requestor_id'];
			if ($_POST['grant_budget']!="yes"){
				$budget_sec = 4;	
			}else{
				$budget_sec = $_POST['budget_sl'];	
			}
			
			$result=mysql_query("select * from $db_user where user_id='$requestor_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
		  		$first_name = $row['first_name'];
				$email = $row['email'];
			}
			$result=mysql_query("select * from $db_user where user_id='$user_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
		  		$group_first_name = $row['first_name'];
			}
			//don't remove any account in $db_account table since we are adding only
			//mysql_query("DELETE FROM $db_account WHERE user_id='$requestor_id'");
			
			//determine which account is granted right
			$result=mysql_query("select * from $db_account_type ORDER BY account_type_id ASC") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
		  		if ($row['user_id']==$user_id and $row['active']=="yes"){
			  		$account_type_id = $row['account_type_id'];
			  		$account_type_value = $_POST["t".$account_type_id];
			  		$access_right = $_POST[l.$account_type_id];
			  		
			  		if ($access_right==""){
			  			$access_right = 4;
			  		}
			  		if ($account_type_value=="on"){
			  			$sql="INSERT INTO $db_account(user_id, access_right, account_type_id, active) VALUES ('$requestor_id','$access_right','$account_type_id','yes')";
						if (!mysql_query($sql,$connection)){
					  		die('Error: ' . mysql_error());
					  	}
				  		$active_invite = "yes";
			  		}
			  	}//end if statement
		  	}//end while loop
		  	////if a least one account has been granted////
		  	//if ($active_invite=="yes"){  -- commented out - either account or budget is checked, update update status to accept.
		  	if ($action_code=="go"){
			  	$sql=sprintf("UPDATE $db_invite SET invite_budget_sec='$budget_sec', date_accept='$todate', active='yes' where user_id='$requestor_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	
			  	$message =  "<html><head></head><body><h1>Budget/Account Permission Granted</h1><br><p>".$group_first_name.", has confirmed and granted permission.  Please login to view budget sheet and accounts.<br>";
				$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br><br><br><br></body></html>";
				$to = $email;
				$subject = "Permission Granted - Maxmoni";
				$from = "emailconfirm@maxmoni.com";
				$headers  = "From: Maxmoni<".$from.">\r\n";
				$headers .= "Content-type: text/html\r\n";
				mail($to,$subject,$message,$headers);
			  	
			  	if($_POST['requestor_no']>1){
				  	print "<script>";
					print "self.location='".$mx005cr."';";
					print "</script>";
				}else {
					print "<script>";
					print "self.location='".$mx005uu."';";
					print "</script>";
				}
			}else{
				$dir_flag = "no";
			}
		}
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>