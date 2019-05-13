<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		if ($_SESSION['seclevel']==1)	{
			if($_SERVER['REQUEST_METHOD'] != "POST") {
				$result=mysql_query("select $db_account_type.account_type_id, $db_account_type.account_type, $db_account_type.active from $db_account_type, $db_user where $db_user.user_id=$user_id and $db_user.group_id=$db_account_type.group_id ORDER BY account_type ASC") or die(mysql_error());
				$account_type = "";
				$seclevel = "";
				while($row = mysql_fetch_array($result)){
			  		if ($row['active']=="yes"){
			  			$account_type = $account_type ."<tr><td class='inntablepad'><input type='checkbox' name='t".$row['account_type_id']."' value'".$row['account_type_id']."'> ".$row['account_type']."</td><td class='inntablepad'><input name='l".$row['account_type_id']."' type='radio' value='2' /> Level 1 <input name='l".$row['account_type_id']."' type='radio' value='3' /> Level 2 <input name='l".$row['account_type_id']."' type='radio' value='4' /> Level 3 </td></tr>";
			  		}	
			  	}
			}else{
				$first_name = $_POST['first_name'];
				$email = $_POST['email'];
				if ($_POST['portfolio_sl']==""){
					$portfolio_sec = 5;
				}else{
					$portfolio_sec = $_POST['portfolio_sl'];
				}
				if ($_POST['budget_sl']==""){
					$budget_sec = 5;
				}else{
					$budget_sec = $_POST['budget_sl'];
				}
				if ($_POST['saving_sl']==""){
					$saving_sec = 5;
				}else{
					$saving_sec = $_POST['saving_sl'];
				}
				$loginid = $_POST['loginid_new'];
				$password = $_POST['password_new'];
				$todate = date('Y-m-d'); 
				
				$result=mysql_query("select group_id from $db_user where user_id='$user_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$group_id = $row['group_id'];
			  	}
				  	
				$sql="INSERT INTO $db_user(first_name, email, admin_sec, portfolio_sec, budget_sec, saving_sec, login_id, password, date_join, group_id, active) VALUES ('$first_name','$email','2','$portfolio_sec','$budget_sec','$saving_sec','$loginid','$password','$todate','$group_id','yes')";
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
				
				$result=mysql_query("select user_id from $db_user where login_id='$loginid'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$add_user_id = $row['user_id'];
			  	}
	
				//determine which account is granted right
				$result=mysql_query("select $db_account_type.account_type_id, $db_account_type.account_type from $db_account_type, $db_user where $db_user.user_id=$user_id and $db_user.group_id=$db_account_type.group_id ORDER BY account_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$account_type_id = $row['account_type_id'];
			  		$account_type_value = $_POST[t.$account_type_id];
			  		$access_right = $_POST[l.$account_type_id];
			  		if ($access_right==""){
			  			$access_right = 4;
			  		}
			  		if ($account_type_value=="on"){
			  			$sql="INSERT INTO $db_account(user_id, access_right, account_type_id, active) VALUES ('$add_user_id','$access_right','$account_type_id','yes')";
						if (!mysql_query($sql,$connection)){
					  		die('Error: ' . mysql_error());
					  	}
			  		}
			  	}
			  	print "<script>";
				print "self.location='".$mx005."';";
				print "</script>";	
			}//end of else statement - post form
			mysql_close($connection);
		} else{//end if statement - user rights
			print "<script>";
			print "self.location='".$mx005."&t=1';";
			print "</script>";
		}
	}else{// end if statement - login
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>