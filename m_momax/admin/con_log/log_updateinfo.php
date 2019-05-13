<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		$todate = date('Y-m-d');
		////make sure only owner can update account info////
		if ($_SESSION['seclevel']==1){
			//update account type //
			if($_POST['update_acct']=="new"){	
				$acct_type_id = $_POST['acct_id'];
				$acct_type = $_POST['acct_type'];
				$acct_desc = $_POST['acct_desc'];
				$sql=sprintf("UPDATE $db_account_type SET account_type='$acct_type', account_description='$acct_desc' WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}	
			//turn off account type but not delete//
			if($_GET['atidd']!=""){	
				$acct_type_id = $_GET['atidd'];
				$sql=sprintf("UPDATE $db_account_type SET active='no' WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	$sql=sprintf("UPDATE $db_account SET active='no' WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			//turn on account type//
			if($_GET['atidto']!=""){	
				$acct_type_id = $_GET['atidto'];
				$sql=sprintf("UPDATE $db_account_type SET active='yes' WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	$sql=sprintf("UPDATE $db_account SET active='yes' WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			//delete account number from account tables, no recovery//
			if($_GET['atidre']!=""){	
				$acct_type_id = $_GET['atidre'];
				$sql=sprintf("DELETE FROM $db_account_type WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	$sql=sprintf("DELETE FROM $db_account WHERE account_type_id='$acct_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			//insert new account type to tables//
			if($_POST['new_acct']=="new"){	
				$acct_type_id = $_POST['acct_id'];
				$acct_type = $_POST['acct_type'];
				$acct_desc = $_POST['acct_desc'];
				$start_amt = $_POST['start_amt'];
				$sql=sprintf("INSERT INTO $db_account_type (account_type, account_usage, account_description, group_id, user_id, active) VALUES ('$acct_type', 'Bank', '$acct_desc', '$group_id', '$user_id', 'yes')");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	$result=mysql_query("select account_type_id from $db_account_type where account_type='$acct_type'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$newAcct_id = $row['account_type_id'];
			  	}
			  	$sql=sprintf("INSERT INTO $db_account (user_id, access_right, account_type_id, active) VALUES ('$user_id', '1', '$newAcct_id','yes')");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			  	//get an income activity ID//		
			  	$activity_id_flag = "no";		
				$result=mysql_query("select activity_type_id from $db_activity_type where user_id='$user_id' and activity_mode='Income' and active='yes'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			  		$activity_type_id = $row['activity_type_id'];
			  		$activity_id_flag = "yes";
			  	}
			  	//if not income activity_id, create one//
			  	if ($activity_id_flag == "no"){
			  		$sql="INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('Income','Income','General income','$user_id','yes')";
					if (!mysql_query($sql,$connection)){
				  		die('Error: ' . mysql_error());
				  	}
				  	$result=mysql_query("select activity_type_id from $db_activity_type where user_id='$user_id' and activity_mode='Income' and active='yes'") or die(mysql_error());
					while($row = mysql_fetch_array($result)){
				  		$activity_type_id = $row['activity_type_id'];
				  	}
			  	}
			  	
			  	//insert new record into $db_transaction table
				$temp_reference_num = $i.date(m).date(d).date(H).date(i).date(s).rand(100000, 999999);
				$sql="INSERT INTO $db_transaction(account_type_id, user_id, transaction_date, transaction_order, amount_in, amount_out, description, reference_number, activity_type_id, saving, transaction_type_id, budget, balance_date,active) VALUES ('$newAcct_id','$user_id','$todate', '1','$start_amt', '$start_amt','Account balance','$temp_reference_num','$activity_type_id','no','100100','no','$todate','yes')";
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
				$result=mysql_query("select transaction_id from $db_transaction where user_id='$user_id' and reference_number='$temp_reference_num'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$temp_trans_id = $row['transaction_id'];
				}
				//generate recurring group id and update recurring set it
				$sql="INSERT INTO $db_recurring_group(recurring_set_id, transaction_id) VALUES ('$temp_trans_id', '$temp_trans_id')";
				if (!mysql_query($sql,$connection)){
					die('Error: ' . mysql_error());
				}
				$result=mysql_query("select recurring_group_id from $db_recurring_group where transaction_id='$temp_trans_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$temp_recurring_set_id = $row['recurring_group_id'];
				}	
				//set recurring set id
				$sql=("UPDATE $db_recurring_group SET recurring_set_id='$temp_recurring_set_id' WHERE transaction_id='$temp_trans_id'");		  		
				if (!mysql_query($sql,$connection)) {
					die('Error: ' . mysql_error());	
				}
				//set reference_number to empty
				$sql=("UPDATE $db_transaction SET reference_number='$temp_recurring_set_id' WHERE user_id='$user_id' and transaction_id='$temp_trans_id'");		  		
				if (!mysql_query($sql,$connection)) {
					die('Error: ' . mysql_error());	
				}
			}
			
			//create a table to display the all account types//
			if($_GET['atide'] !="")	{
				$account_type = "";
				$result=mysql_query("select * from $db_account_type ORDER BY account_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$user_id and $_SESSION['seclevel']==1){
						$owner_lk = $row['user_id'];
						if ($row['account_type_id'] == $_GET['atide']){  //create textbox to get update info
							$owner = getname($owner_lk);
							if ($row['active']=="yes"){
								$account_type = $account_type."<tr><td class='tablepad'><input type='text' name='acct_type' value='".$row['account_type']."'></td><td><input type='text' size='50' name='acct_desc' value='".$row['account_description']."'></td><td>On</td><td><a href='javascript: submitacct(1)'>Update</a> / <a href='".$mx005ua."&wk=acct&atidd=".$row['account_type_id']."'>Turn Off</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."'>Remove</a></td></tr>";
								$account_type = $account_type."<input type='hidden' name='acct_id' value='".$row['account_type_id']."'><input type='hidden' name='update_acct' value='new'>";
							}
							if ($row['active']=="no"){
								$account_type = $account_type."<tr><td class='tablepad'><input type='text' name='acct_type' value='".$row['account_type']."'></td><td><input type='text' size='50' name='acct_desc' value='".$row['account_description']."'></td><td>Off</td><td><a href='".$mx005ua."&wk=acct&atidto=".$row['account_type_id']."'>Turn On</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."'>Remove</a></td></tr>";
								$account_type = $account_type."<input type='hidden' name='acct_id' value='".$row['account_type_id']."'><input type='hidden' name='update_acct' value='new'>";
							}
						}else{ //if no account type id matches, put edit/delete links
							$owner = getname($owner_lk);
							if ($row['active']=="yes"){
								$account_type = $account_type."<tr><td class='tablepad'><label>".$row['account_type']."</label></td><td>".$row['account_description']."</td><td>On</td><td><a href='".$mx005ua."&wk=acct&atide=".$row['account_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=acct&atidd=".$row['account_type_id']."'>Turn Off</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."'>Remove</a></td></tr>";
							}
							if ($row['active']=="no"){
								$account_type = $account_type."<tr><td class='tablepad'><label>".$row['account_type']."</label></td><td>".$row['account_description']."</td><td>Off</td><td><a href='".$mx005ua."&wk=acct&atidto=".$row['account_type_id']."'>Turn On</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."'>Remove</a></td></tr>";
							}
						}
					}
				}//end while loop
			}else{
				$account_type = "";
				$budgetvsactual = "";
				$active_acct = "";
				$active_acct_ct = 0;
				$result=mysql_query("select * from $db_account_type ORDER BY account_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					//load links when page first loaded
					$owner_lk = $row['user_id'];
					if ($row['user_id']==$user_id and $_SESSION['seclevel']==1){
						$owner = getname($owner_lk);
						if ($row['active']=="yes"){
							$account_type = $account_type."<tr><td class='tablepad'><label>".$row['account_type']."</label></td><td>".$row['account_description']."</td><td>On<td><a href='".$mx005ua."&wk=acct&atide=".$row['account_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=acct&atidd=".$row['account_type_id']."'>Turn Off</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."' onclick='return del_acct_warnming();'>Remove</a></td></tr>";
							$check_active_acct = chk_budvsact($user_id, $row['account_type_id']);
							if ($check_active_acct == 'yes'){
								$budgetvsactual = $budgetvsactual."<tr><td class='tablepad'><input type='checkbox' checked name='".$row['account_type_id']."' id='".$row['account_type_id']."'><label>".$row['account_type']."</label></td></tr>";
							}else {
								$budgetvsactual = $budgetvsactual."<tr><td class='tablepad'><input type='checkbox' name='".$row['account_type_id']."' id='".$row['account_type_id']."'><label>".$row['account_type']."</label></td></tr>";
							}
							$active_acct = $active_acct."".$row['account_type_id'];
							$active_acct_ct = $active_acct_ct + 1;
						}
						if ($row['active']=="no"){
							$account_type = $account_type."<tr><td class='tablepad'><label>".$row['account_type']."</label></td><td>".$row['account_description']."</td><td>Off</td><td><a href='".$mx005ua."&wk=acct&atidto=".$row['account_type_id']."'>Turn On</a> / <a href='".$mx005ua."&wk=acct&atidre=".$row['account_type_id']."' onclick='return del_acct_warnming();'>Remove</a></td></tr>";
						}
					}
				}
			}//end else statement
			
			if($_GET['atida'] =="new"){
				$account_type = $account_type."<tr><td class='tablepad'><input type='text' name='acct_type' id='acct_type' onchange='chk_acct_name(this.value,".$user_id.")'></td><td><input type='text' size='50' name='acct_desc'> Start Amount: <input type='text' size='10' name='start_amt' id='start_amt' onchange='isNumber_start_amt(this.value)'></td><td colspan='2'><a href='javascript: submitacct(0)'>Add</a></td></tr>";
				$account_type = $account_type."<input type='hidden' name='acct_id' value='".$row['account_type_id']."'><input type='hidden' name='new_acct' value='new'>";
			}else{
				$account_type = $account_type."<tr><td class='tablepad' colspan='4'><a href='".$mx005ua."&wk=acct&atida=new'>Add new account type</a></td></tr>";
			}
		}//end of account type
		
		////only owner can update transaction/activity type////
		if ($_SESSION['seclevel']==1){
			////transaction type start here///  **transaction has been removed... ////
			////8/8/12 - left in case future need - tou////
			if($_POST['update_trans']=="new"){	
				$trans_type_id = $_POST['trans_id'];
				$trans_type = $_POST['trans_type'];
				$trans_desc = $_POST['trans_desc'];
				$sql=sprintf("UPDATE $db_transaction_type SET transaction_type='$trans_type', transaction_description='$trans_desc' WHERE transaction_type_id='$trans_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}	
			if($_GET['taidd']!=""){	
				$trans_type_id = $_GET['taidd'];
				$sql=sprintf("UPDATE $db_transaction_type SET active='no' WHERE transaction_type_id='$trans_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			if($_POST['new_trans']=="new"){	
				$trans_type_id = $_POST['trans_id'];
				$trans_type = $_POST['trans_type'];
				$trans_desc = $_POST['trans_desc'];
				$sql=sprintf("INSERT INTO $db_transaction_type (transaction_type, transaction_description, group_id, user_id, active) VALUES ('$trans_type', '$trans_desc', '$group_id', '$user_id', 'yes')");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			if($_GET['taide'] !="")	{
				$transaction_type = "";
				$result=mysql_query("select * from $db_transaction_type ORDER BY transaction_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$user_id and $row['active']=="yes"){
						$owner_lk = $row['user_id'];
						if ($row['transaction_type_id'] == $_GET['taide']){
							$owner = getname($owner_lk);
							$transaction_type = $transaction_type."<tr><td class='coltab2'><input type='text' name='trans_type' value='".$row['transaction_type']."'></td><td class='coltab3'><input type='text' size='50' name='trans_desc' value='".$row['transaction_description']."'></td><td class='coltab4'><a href='javascript: submittrans()'>Update</a> / <a href='".$mx005ua."&wk=trans&taidd=".$row['transaction_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
							$transaction_type = $transaction_type."<input type='hidden' name='trans_id' value='".$row['transaction_type_id']."'><input type='hidden' name='update_trans' value='new'>";
						}else{
							$owner = getname($owner_lk);
							$transaction_type = $transaction_type."<tr><td class='coltab2'><label>".$row['transaction_type']."</label></td><td class='coltab3'>".$row['transaction_description']."</td><td class='coltab4'><a href='".$mx005ua."&wk=trans&taide=".$row['transaction_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=trans&taidd=".$row['transaction_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
						}
					}
				}//end of while loop
			}else{
				$transaction_type = "";
				$result=mysql_query("select * from $db_transaction_type ORDER BY transaction_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$owner_lk = $row['user_id'];
					if ($row['user_id']==$user_id and $row['active']=="yes"){
						$owner = getname($owner_lk);
						$transaction_type = $transaction_type."<tr><td class='coltab2'><label>".$row['transaction_type']."</label></td><td class='coltab3'>".$row['transaction_description']."</td><td class='coltab4'><a href='".$mx005ua."&wk=trans&taide=".$row['transaction_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=trans&taidd=".$row['transaction_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
					}
				}//end of while loop
			}
			
			if($_GET['taida'] =="new"){
				$transaction_type = $transaction_type."<tr><td class='coltab2'><input type='text' name='trans_type'></td><td class='coltab3'><input type='text' size='50' name='trans_desc'></td><td class='coltab4'><a href='javascript: submittrans()'>Add</a></td></tr>";
				$transaction_type = $transaction_type."<input type='hidden' name='trans_id' value='".$row['transaction_type_id']."'><input type='hidden' name='new_trans' value='new'>";
			}else{
				$transaction_type = $transaction_type."<tr><td class='coltab2' colspan='3'><a href='".$mx005ua."&wk=trans&taida=new'>Add new transaction type</a></td></tr>";
			}
			
			////activity type start here//////////////////////////
			//////////////////////////////////////////////////////
			
			//update existing activity//
			if($_POST['update_act']=="new"){	
				$act_type_id = $_POST['act_id'];
				$act_type = $_POST['act_type'];
				$act_mode = $_POST['act_mode'];
				$act_desc = $_POST['act_desc'];
				$sql=sprintf("UPDATE $db_activity_type SET activity_type='$act_type', activity_mode='$act_mode', activity_description='$act_desc' WHERE activity_type_id='$act_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			//remove or delete existing activity//	
			if($_GET['avidd']!=""){	
				$act_type_id = $_GET['avidd'];
				$act_found = "no";
			  	$result=mysql_query("select * from $db_transaction where activity_type_id='$act_type_id'") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$act_found = "yes";
				}
				if ($act_found == "yes"){
					$sql=sprintf("UPDATE $db_activity_type SET active='no' WHERE activity_type_id='$act_type_id'");
					if (!mysql_query($sql,$connection)){
						die('Error: ' . mysql_error());
					}
				}else {
					$sql=sprintf("DELETE FROM $db_activity_type WHERE activity_type_id='$act_type_id'");
					if (!mysql_query($sql,$connection)){
						die('Error: ' . mysql_error());
					}
				}
			}
			//add a new activity//
			if($_POST['new_act']=="new"){	
				$act_type_id = $_POST['act_id'];
				$act_type = $_POST['act_type'];
				$act_mode = $_POST['act_mode'];
				$act_desc = $_POST['act_desc'];
				$sql=sprintf("INSERT INTO $db_activity_type (activity_type, activity_mode, activity_description, group_id, user_id, active) VALUES ('$act_type', '$act_mode', '$act_desc', '$group_id', '$user_id', 'yes')");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			//create activity table for edit - one activity has been selected for edit//
			if($_GET['avide'] !="")	{
				$activity_type = "";
				$result=mysql_query("select * from $db_activity_type ORDER BY activity_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if ($row['user_id']==$user_id and $row['active']=="yes"){
						$owner_lk = $row['user_id'];
						//if there is a match activity_id, then process//
						if ($row['activity_type_id'] == $_GET['avide']){
							$owner = getname($owner_lk);
							$activity_type = $activity_type."<tr><td class='coltab2'><input type='text' name='act_type' value='".$row['activity_type']."'></td>";
							if ($row['activity_mode']=="Income")
							{
								$activity_type = $activity_type."<td class='coltab3'><select name='act_mode'><option selected='selected' value='Income'>Income</option><option value='Saving'>Saving</option><option value='Expense'>Expense</option></td>";
								$activity_type = $activity_type."</select>";
							}
							if ($row['activity_mode']=="Saving")
							{
								$activity_type = $activity_type."<td class='coltab3'><select name='act_mode'><option value='Income'>Income</option><option selected='selected' value='Saving'>Saving</option><option value='Expense'>Expense</option></td>";
								$activity_type = $activity_type."</select>";
							}
							if ($row['activity_mode']=="Expense")
							{
								$activity_type = $activity_type."<td class='coltab3'><select name='act_mode'><option value='Income'>Income</option><option value='Saving'>Saving</option><option selected='selected' value='Expense'>Expense</option></td>";
								$activity_type = $activity_type."</select>";
							}
							$activity_type = $activity_type."<td class='coltab3'><input type='text' size='50' name='act_desc' value='".$row['activity_description']."'></td><td class='coltab4'><a href='javascript: submitact()'>Update</a> / <a href='".$mx005ua."&wk=act&avidd=".$row['activity_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
							$activity_type = $activity_type."<input type='hidden' name='act_id' value='".$row['activity_type_id']."'><input type='hidden' name='update_act' value='new'>";
						}else{
							$owner = getname($owner_lk);
							$activity_type = $activity_type."<tr><td class='coltab2'><label>".$row['activity_type']."</label></td>";
							$activity_type = $activity_type."<td class='coltab3'>".$row['activity_mode']."</td>";
							$activity_type = $activity_type."<td class='coltab3'>".$row['activity_description']."</td><td class='coltab4'><a href='".$mx005ua."&wk=act&avide=".$row['activity_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=act&avidd=".$row['activity_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
						}
					}//end if user/active match if statement
				}//end of while loop
			}else{ //create a activity table for display//
				$activity_type = "";
				$result=mysql_query("select * from $db_activity_type ORDER BY activity_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$owner_lk = $row['user_id'];
					if ($row['user_id']==$user_id and $row['active']=="yes"){
						$owner = getname($owner_lk);
						$activity_type = $activity_type."<tr><td class='coltab2'><label>".$row['activity_type']."</label></td>";
						$activity_type = $activity_type."<td class='coltab3'>".$row['activity_mode']."</td>";
						$activity_type = $activity_type."<td class='coltab3'>".$row['activity_description']."</td><td class='coltab4'><a href='".$mx005ua."&wk=act&avide=".$row['activity_type_id']."'>Edit</a> / <a href='".$mx005ua."&wk=act&avidd=".$row['activity_type_id']."' onclick='return re_trans_type()'>Remove</a></td></tr>";
					}
				}//end of while loop
			}//end of else statement
			
			//if new activity needs to be created//
			if($_GET['avida'] =="new"){
				$activity_type = $activity_type."<tr><td class='coltab2'><input type='text' name='act_type' id='act_type' onchange='chk_act_name(this.value,".$user_id.")'></td>";
				$activity_type = $activity_type."<td class='coltab3'><select name='act_mode'><option selected='selected' value='Income'>Income</option><option value='Saving'>Saving</option><option value='Expense'>Expense</option></td></select>";
				$activity_type = $activity_type."<td class='coltab3'><input type='text' size='50' name='act_desc'></td><td class='coltab4'><a href='javascript: submitact()'>Add</a></td></tr>";
				$activity_type = $activity_type."<input type='hidden' name='act_id' value='".$row['activity_type_id']."'><input type='hidden' name='new_act' value='new'>";
			}else{ //if no new activity needs to be created//
				$activity_type = $activity_type."<tr><th colspan='4'><a href='".$mx005ua."&wk=act&avida=new'>Add new category</a></th></tr>";
			}
		}//end of transaction and activity types
		
		////only owner can update budget vs. actual expenses////
		if ($_SESSION['seclevel']==1){
			if ($_POST['acct_set']=="yes" and $_POST['acct_ct']>0){
				$pos_start = 0;
				$pos_end = 6;
				$tot_active_acct = $_POST['acct_ct'];
				for ($i=0; $i<$tot_active_acct; $i++){
					$val = substr($_POST['all_acct_id'],$pos_start,6);
					$found_active = "";
					$no_found = "";
					$acct_found = "";
					
					//if account is checked
					if(isset($_POST[$val])){
						$result=mysql_query("select * from $db_budvsact where user_id='$user_id' and account_id='$val'") or die(mysql_error());
						while($row = mysql_fetch_array($result)){
							if ($row['active']=="no"){
								$found_active = "no";
							}else {
								$acct_found = "yes";
							}
						}
						if ($acct_found == ""){
							if ($found_active == "no"){
								$sql=sprintf("UPDATE $db_budvsact SET active='yes' WHERE user_id='$user_id' and account_id='$val'");
								if (!mysql_query($sql,$connection)){
									die('Error: ' . mysql_error());
								}
							}
							if ($found_active == ""){
								$sql="INSERT INTO $db_budvsact(user_id, account_id, active) VALUES ('$user_id','$val','yes')";
								if (!mysql_query($sql,$connection)){
									die('Error: ' . mysql_error());
								}			
							}
                    	}
					}else { //if account isn't checked and found in tabel, update to no
						$result=mysql_query("select * from $db_budvsact where user_id='$user_id' and account_id='$val'") or die(mysql_error());
						while($row = mysql_fetch_array($result)){
							$sql=sprintf("UPDATE $db_budvsact SET active='no' WHERE user_id='$user_id' and account_id='$val'");
							if (!mysql_query($sql,$connection)){
								die('Error: ' . mysql_error());
							}
						}
					}
					$pos_start = $pos_start + 6;
					$pos_end = $pos_end + 6;
				}//end of for loop of account
				print "<script>";
				print "self.location='".$mx009."';";
				print "</script>";
			}
		}//end of budget vs. actual expenses
		
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>