<?php
	if ($_SESSION['login']=="yes"){
		if ($_SESSION['seclevel']==1){
			$user_id = $_SESSION['uid'];
			if($_GET['act']=="1"){
				$nofound = "<h3>No such user found!</h3>";
			}
			
			//get all users below to the current select group
			if ($_GET['act']==""){
				//get group users
				$group_mem_ar = array();
				$group_mem_ar_ct = 0;
				$group_mem = "";
				$group_mem_mob = "";
				$result=mysql_query("select * from $db_group_user where user_id='$user_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$grp_admin_id = $row['group_admin_id'];
				}
				$result=mysql_query("select * from $db_group_user where group_admin_id='$grp_admin_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']!=$user_id){
						$group_mem_ar[$group_mem_ar_ct] = $row['user_id'];
						$group_mem_ar_ct += 1;
					}
				}
				$group_mem_ar_len = count($group_mem_ar);
				for ($i=0; $i<$group_mem_ar_len; $i++){
					$result=mysql_query("select * from $db_user where user_id='$group_mem_ar[$i]' and active='yes'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$share_sta = get_share_sta($user_id, $group_mem_ar[$i]);
						if ($share_sta == ""){
							$share_sta = "Inactive";
						}
						$group_mem = $group_mem."<tr><td  class='tablepad'>".$row['first_name']."</td><td>".$row['email']."</td><td>Group</td><td>".$share_sta."</td><td><a href='".$mx005uu."&uid=".$row['user_id']."&act=search&mty=invited'>Update</a></td></tr>";
						$group_mem_mob = $group_mem_mob."<tr><td  class='tablepad'>".$row['first_name']."</td><td>Invited</td><td>".$share_sta."</td><td><a href='".$mx005uu."&uid=".$row['user_id']."&act=search&mty=invited'>Update</a></td></tr>";
					}//end of while loop
				}
				
				//get outside group - invited only
				$invite_id = array();
				$invite_status = array();
				$user_group = "";
				$user_group_mob = ""; //for mobile site
								
				//get invited member from $db_invite table
				$invite_id_ct = 0;
				$result=mysql_query("select * from $db_invite where group_id='$user_id' and active<>'non' and active<>'no'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$no_group_flag = "no";
					for ($i=0; $i<$group_mem_ar_len; $i++){
						if ($row['user_id']==$group_mem_ar[$i]){
							$no_group_flag = "yes";
							break;
						}
					}
					if ($no_group_flag == "no"){
						$invite_id[$invite_id_ct] = $row['user_id'];
						if ($row['active']=="yes"){
							$invite_status[$invite_id_ct] = "Share";
						}else{
							$invite_status[$invite_id_ct] ="Not Share";
						}
						$invite_id_ct += 1;
					}
				}
				
				$invite_id_len = count($invite_id);
				for ($i=0; $i<$invite_id_len; $i++){
					$result=mysql_query("select * from $db_user where user_id='$invite_id[$i]' and active='yes'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$user_group = $user_group."<tr><td  class='tablepad'>".$row['first_name']."</td><td>".$row['email']."</td><td>Invited</td><td>".$invite_status[$i]."</td><td><a href='".$mx005uu."&uid=".$row['user_id']."&act=search&mty=invited'>Update</a></td></tr>";
						$user_group_mob = $user_group_mob."<tr><td  class='tablepad'>".$row['first_name']."</td><td>Invited</td><td>".$invite_status[$i]."</td><td><a href='".$mx005uu."&uid=".$row['user_id']."&act=search&mty=invited'>Update</a></td></tr>";
					}//end of while loop
				}
			}//end if statement
			
			//get selected user info to update rights
			if($_GET['act']=="search") {
				$user_flag = "";
				$right_grp = "";
				$update_user_id = $_GET['uid'];
								
				$result=mysql_query("select * from $db_user") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($update_user_id == $row['user_id']){	
				  		$first_name = $row['first_name'];
						$email = $row['email'];
						$seclevel = $row['admin_sec'];
						$active = $row['active'];
						$user_flag = "yes";
					}
				}//end while loop
				
				//get assigned budget security
				if ($_GET['mty']=="invited"){
					$add_invited = "no";
					$result=mysql_query("select invite_budget_sec, invite_saving_sec, active from $db_invite where group_id='$user_id' and user_id='$update_user_id'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						if ($row['active']=="yes"){
							$status = "<input name='u_active' type='radio' checked='yes' value='yes' /> Share <input name='u_active' type='radio' value='no' /> Not Share";				
							$budget_sec = $row['invite_budget_sec'];
							$saving_sec = $row['invite_saving_sec'];
						}else{
							$status = "<input name='u_active' type='radio' value='yes' /> Share <input name='u_active' type='radio' checked='yes' value='no' /> Not Share";
							$budget_sec = $row['invite_budget_sec'];
							$saving_sec = $row['invite_saving_sec'];
						}
						$add_invited = "yes";
					}
				}
				if ($add_invited == "no"){ //if user isn't in the invited group yet
					$status = "<input name='u_active' type='radio' value='yes' /> Share <input name='u_active' type='radio' checked='yes' value='no' /> Not Share";
					$budget_sec = 4;
					$saving_sec = 0;
				}
												
				$account_type = "";
				$flag = 0;
				$arr_ct = 0;
				$accfound_id_arr = array();
				$accfound_access_arr = array();
				////get all checked accounts for this user
				$result=mysql_query("select * from $db_account ORDER BY account_type_id ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$update_user_id){
						$accfound_id_arr[$arr_ct] = $row['account_type_id'];
						$accfound_access_arr[$arr_ct] = $row['access_right'];
						$arr_ct += 1; 
					}
				}
				
				//get all checked accounts info
				$result=mysql_query("select * from $db_account_type ORDER BY account_type_id ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$user_id){
						for ($i = 0; $i < count($accfound_id_arr); $i++) {
							if ($row['account_type_id']==$accfound_id_arr[$i] and $row['active']=="yes"){
								$account_type = $account_type ."<tr><td class='inntablepad'><input type='checkbox' checked='yes' id='t".$row['account_type_id']."' name='t".$row['account_type_id']."' value'".$row['account_type_id']."' onclick='con_acct_sec(".$row['account_type_id'].")'> ".$row['account_type']."</td>";
								$flag = 1;								
								if ($accfound_access_arr[$i]==3){
									$account_type = $account_type ."<td class='inntablepad'><input id='l2".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' checked='yes' value='3' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 1 <input id='l3".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='2' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 2 <input id='l4".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='1' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 3</td></tr>";
								}
								if ($accfound_access_arr[$i]==2){
									$account_type = $account_type ."<td class='inntablepad'><input id='l2".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='3' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 1 <input id='l3".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' checked='yes' value='2' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 2 <input id='l4".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='1' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 3</td></tr>";
								}
								if ($accfound_access_arr[$i]==1){
									$account_type = $account_type ."<td class='inntablepad'><input id='l2".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='3' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 1 <input id='l3".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='2' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 2 <input id='l4".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' checked='yes' value='1' onclick='con_acct_sec_chk(".$row['account_type_id'].")' /> Level 3</td></tr>";
								}
							}
						} 
						if ($flag != 1 and $row['active']=="yes"){
							$account_type = $account_type ."<tr><td class='inntablepad'><input type='checkbox' id='t".$row['account_type_id']."' name='t".$row['account_type_id']."' value'".$row['account_type_id']."' onclick='con_acct_sec(".$row['account_type_id'].")'> ".$row['account_type']."</td>";
							$account_type = $account_type ."<td class='inntablepad'><input id='l2".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='3' disabled='true' /> Level 1 <input id='l3".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='2' disabled='true' /> Level 2 <input id='l4".$row['account_type_id']."' name='l".$row['account_type_id']."' type='radio' value='1' disabled='true' /> Level 3</td></tr>";
						}else{
							$flag = 0;
						}
					}
				}//end while loop
			}//end if statement update
			
			if($_SERVER['REQUEST_METHOD'] == "POST" and $_GET['act']=="go"){
				$update_user_id = $_POST['uid'];
				$first_name = $_POST['first_name'];
				$email = $_POST['email'];
				$status = $_POST['u_active'];
				$invited_yn = $_POST['not_added'];
				$todate = date('Y-m-d');
				
				if ($_POST['grant_budget']!="yes"){
					$budget_sec = 4;	
				}else{
					$budget_sec = $_POST['budget_sl'];	
				}
				
				if ($_POST['saving_sl']=="no"){
					$saving_sec = 4;	
				}else{
					$saving_sec = $_POST['saving_sl'];	
				}
								
				//remove all account in $db_account table
				if ($_POST['mty']=="invited"){
					$result=mysql_query("select account_type_id from $db_account_type where user_id='$user_id'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$del_account_id = $row['account_type_id'];
						mysql_query("DELETE FROM $db_account WHERE user_id='$update_user_id' and account_type_id='$del_account_id'");
					}
				}
				
				if ($status == "yes"){
					if ($invited_yn == "yes"){
						//update budget/saving rights
						$sql=("UPDATE $db_invite SET invite_budget_sec='$budget_sec', invite_saving_sec='$saving_sec', active='$status' WHERE user_id='$update_user_id' and group_id='$user_id'");
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());	
						}
					}else{
						$sql="INSERT INTO $db_invite(group_id, user_id, invite_budget_sec, invite_saving_sec, date_invite, date_accept, active) VALUES ('$user_id','$update_user_id','$budget_sec','$saving_sec','$todate','$todate','$status')";
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());	
						}
					}
					
					//determine which account is granted right
					$result=mysql_query("select account_type_id, account_type from $db_account_type where user_id='$user_id' and active='yes' ORDER BY account_type ASC") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$account_type_id = $row['account_type_id'];
						$account_type_value = $_POST["t".$account_type_id];
						$access_right = $_POST[l.$account_type_id];
						
						if ($access_right=="")
						{	$access_right = 4;	}
						
						if ($account_type_value=="on"){
							$sql="INSERT INTO $db_account(user_id, access_right, account_type_id, active) VALUES ('$update_user_id','$access_right','$account_type_id','$status')";
							if (!mysql_query($sql,$connection)){
								die('Error: ' . mysql_error());	
							}
						}
					}//end of while loop
			  	}else {
					if ($invited_yn == "yes"){
						$sql=sprintf("UPDATE $db_invite SET invite_budget_sec='4', invite_saving_sec='0', active='ina' WHERE user_id='$update_user_id' and group_id='$user_id'");
						if (!mysql_query($sql,$connection)){
							die('Error: ' . mysql_error());	
						}
					}
				}
							  	
			  	print "<script>";
				print "self.location='".$mx005uu."';";
				print "</script>";	
			}// end if statement - post data update
		}else{//end if statement - have rights to add user
			print "<script>";
			print "self.location='".$mx005uu."';";
			print "</script>";
		}
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>