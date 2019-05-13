<?php
////generate code for unquie pages and public session indentifier////
function get_link_code(){
	$len = 19;
	$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
	$max=strlen($base)-1;
	$pa_code='';
	mt_srand((double)microtime()*1000000);
	while (strlen($pa_code)<$len+1)
	$pa_code.=$base{mt_rand(0,$max)};
	return $pa_code;
}
////create $ sign formatting for number////
function moneyFormat($number, $currencySymbol = '$',$decPoint = '.', $thousandsSep = ',', $decimals = 2) {
	return $currencySymbol . number_format($number, $decimals,$decPoint, $thousandsSep);
}
	
function getname($owner){
	include('com_momax/functions/path.php');
	$result=mysql_query("select first_name, last_name from $db_user where user_id='$owner'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$owner = $row['first_name'];
		$x = $row['first_name'];
		$y = $row['last_name'];
	}
	return $owner;
}
function getemail($user_id){
	include('com_momax/functions/path.php');
	$result=mysql_query("select email from $db_user where user_id='$user_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$email = $row['email'];
	}
	return $email;
}
function getAccountType($account_type_id){
	include('com_momax/functions/path.php');
	$result=mysql_query("select account_type from $db_account_type where account_type_id='$account_type_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$account_type = $row['account_type'];
	}
	return $account_type;
}
////mx00500::calculate and return the budget balance and actual balance value for a regular bank account////
function getBalance($account_id,$user_id){
	include('com_momax/functions/path.php');
	//get account type
	$result=mysql_query("select account_type from $db_account_type where account_type_id='$account_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$account_type = $row['account_type'];
	}
	
	//get user's security level
	$result=mysql_query("select access_right from $db_account where user_id='$user_id' and account_type_id='$account_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$seclevel = $row['access_right'];
	}
	
	$result=mysql_query("select COUNT(transaction_id) from $db_transaction where account_type_id='$account_id'") or die(mysql_error());
	$row = mysql_fetch_row($result); 
	$transaction_count = $row[0];	
	
	$trans_date_arr = array();
	$amt_in_arr = array();
	$amt_out_arr = array();
	$desc_arr = array();
	$bal_date_arr = array();
	$plan_bal_arr = array();
	$act_bal_arr = array();
	$action_arr = array();
	
	$arr_counter = 0;
	$trans_row = "";
	$bal_date = "";
	$round_counter = 1;
	$actual_balance_pre = 0;
	$plan_balance_pre = 0;
	
	$today_da = strtotime(date('Y-m-d'));  
	
	$result=mysql_query("select * from $db_transaction where account_type_id=$account_id ORDER BY transaction_date ASC, transaction_order ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row['active']=="yes"){
			$transaction_date = strtotime($row['transaction_date']);
			
			if($row['balance_date']==NULL or $row['balance_date']=="0000-00-00"){
				$bal_date = "";
			}else {
				$bal_date = date("m/d/y", strtotime($row['balance_date']));
			}
			if ($row['amount_in']==NULL or $row['amount_in']==""){
				$amount_in = 0;
			}else{
				$amount_in = $row['amount_in'];
			}
			if ($row['amount_out']==NULL or $row['amount_out']==""){
				$amount_out = 0;
			}else{
				$amount_out = $row['amount_out'];
			}
							
			//caculate plan and actual balance - updat to today's date
			if ($transaction_date<=$today_da){
				if ($round_counter == 1){
					if($row['transaction_type_id']=="100100"){
						$plan_balance = $amount_in;
						$actual_balance = $amount_in;
					}
					if($row['transaction_type_id']=="100101"){
						if ($amount_out > 0){
							$plan_balance = -$amount_out;
							$actual_balance = -$amount_out;
						}else{
							$plan_balance = $amount_out;
							$actual_balance = $amount_out;
						}
					}
				}else{
					if($row['transaction_type_id']=="100100"){
						$plan_balance = ($plan_balance_pre + $amount_in);
					}
					if($row['transaction_type_id']=="100101"){
						$plan_balance = ($plan_balance_pre - $amount_out);
					}
					if($row['balance_date']==NULL or $row['balance_date']=="0000-00-00"){
						$actual_balance = $actual_balance_pre;
					}else{
						if($row['transaction_type_id']=="100100"){
							$actual_balance = ($actual_balance_pre + $amount_in);
						}
						if($row['transaction_type_id']=="100101"){
							$actual_balance = ($actual_balance_pre - $amount_out);
						}
					}
				}
			}//end calculation updat to today's date
			
			if ($amount_in==0){
				$amount_in = "";
			}else{
				$amount_in = moneyFormat($amount_in);
			}
			if ($amount_out==0){
				$amount_out = "";
			}else{
				$amount_out = moneyFormat($amount_out);
			}
			
			if ($plan_balance < 0){
				$b_balance_str = strval($plan_balance);
				$b_balance_str = substr($b_balance_str, 1);
				$b_balance_int = intval($b_balance_str);
				$b_balance = "(".moneyFormat((float)$b_balance_int).")"; 
			}else {
				$b_balance = moneyFormat((float)$plan_balance);
			}
			if ($actual_balance < 0){
				$a_actual_str = strval($actual_balance);
				$a_actual_str = substr($a_actual_str, 1);
				$a_actual_int = intval($a_actual_str);
				$a_balance = "(".moneyFormat((float)$a_actual_int).")"; 
			}else {
				$a_balance = moneyFormat((float)$actual_balance);
			}
			
			$round_counter += 1;
			$plan_balance_pre = $plan_balance;
			$actual_balance_pre = $actual_balance;
			$page_trans_counter += 1;	
			
			$arr_counter += 1;  		
		}
	}
	return array($b_balance, $a_balance);
}// end of function getBalance($account_id,$user_id)

//report overview page: get monthly/weekly forecast and actual
function getbud_vs_act($account_id,$user_id){
	include('com_momax/functions/path.php');
	
	//get account type
	$result=mysql_query("select account_type from $db_account_type where account_type_id='$account_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$account_type = $row['account_type'];
	}
	
	//get user's security level
	$result=mysql_query("select access_right from $db_account where user_id='$user_id' and account_type_id='$account_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$seclevel = $row['access_right'];
	}
	
	$result=mysql_query("select COUNT(transaction_id) from $db_transaction where account_type_id='$account_id'") or die(mysql_error());
	$row = mysql_fetch_row($result); 
	$transaction_count = $row[0];	
	
	$trans_date_arr = array();
	$amt_in_arr = array();
	$amt_out_arr = array();
	$desc_arr = array();
	$bal_date_arr = array();
	$plan_bal_arr = array();
	$act_bal_arr = array();
	$action_arr = array();
	
	$arr_counter = 0;
	$trans_row = "";
	$bal_date = "";
	$round_counter = 1;
	$actual_balance = 0;
	$plan_balance = 0;
	$actual_balance_wek = 0;
	$plan_balance_wek = 0;
	$wek_forecast_flag = "";
	$mon_forecast_flag = "";
	
	$today_da = strtotime(date('Y-m-d'));  //today's date
	//current month
	$current_yr = date(Y);	
	$current_mon = date(m);  
	//current week
	$ts = strtotime(date(date(Y).'-'.date(m).'-'.date(d)));
	$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
	$start_wk = date('Y-m-d', $start);
	$end_wk = date('Y-m-d', strtotime('next saturday', $start));
	$end_wk1 = date('Y-m-d', strtotime('next saturday', $start));
	
	$result=mysql_query("select * from $db_transaction where account_type_id='$account_id' and user_id = '$user_id' ORDER BY transaction_date ASC, transaction_order ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row['active']=="yes"){
			//$transaction_date = strtotime($row['transaction_date']);
			
			$orgDate = $row['transaction_date'];
			$partsArr = explode("-",$orgDate);
			$transaction_date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
			$transaction_yr = $partsArr[0]; 
			$transaction_mo = $partsArr[1];
			
			if ($row['amount_in']==NULL or $row['amount_in']==""){
				$amount_in = 0;
			}else{
				$amount_in = $row['amount_in'];
			}
			if ($row['amount_out']==NULL or $row['amount_out']==""){
				$amount_out = 0;
			}else{
				$amount_out = $row['amount_out'];
			}
							
			//caculate this monthly
			if ($transaction_yr==$current_yr and $transaction_mo==$current_mon){
				if($row['transaction_type_id']=="100101"){
					if($row['balance_date']==NULL or $row['balance_date']=="0000-00-00" or $row['balance_date']==""){
						$plan_balance = $plan_balance + $amount_out;
						$mon_forecast_flag = "yes";
					}else {
						$actual_balance = $actual_balance + $amount_out;
					}
					if ($mon_forecast_flag == ""){
						$plan_balance = $plan_balance + $amount_out;
					}
					$mon_forecast_flag = "";
				}
			}
			//calculate weekly forecast/actual
			if ($start_wk <= $orgDate and $orgDate <= $end_wk){
				if($row['transaction_type_id']=="100101"){
					if($row['balance_date']==NULL or $row['balance_date']=="0000-00-00" or $row['balance_date']==""){
						$plan_balance_wek = $plan_balance_wek + $amount_out;
						$wek_forecast_flag = "yes";
					}else {
						$actual_balance_wek = $actual_balance_wek + $amount_out;
					}
					if ($wek_forecast_flag == ""){
						$plan_balance_wek = $plan_balance_wek + $amount_out;
					}
					$wek_forecast_flag = "";
				}//end of weekly calucation
			}//end weekly calculation 						  		
		}//end of active account
	}//end of for loop
	$b_balance = (float)$plan_balance;
	$a_balance = (float)$actual_balance;
	$b_balance_wek = (float)$plan_balance_wek;
	$a_balance_wek = (float)$actual_balance_wek;
	
	return array($b_balance, $a_balance, $b_balance_wek, $a_balance_wek);
}// end of function getBalance($account_id,$user_id)

////mx00400::get transaction_type_id//
function get_trans_id($transaction_type_id, $activity_type_id){
	include('com_momax/functions/path.php');
	$result=mysql_query("select transaction_type from $db_transaction_type where transaction_type_id='$transaction_type_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$trans_type = $row['transaction_type'];
	}

	$result=mysql_query("select activity_type from $db_activity_type where activity_type_id='$activity_type_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$act_type = $row['activity_type'];
	}
	return array($trans_type, $act_type);
}

//get account forecast and actual balance
function get_forecast_actual($user_id){
	include('com_momax/functions/path.php');
	$active_acct = array();
	$acct_ct = 0;
	$result=mysql_query("select DISTINCT $db_transaction.account_type_id from $db_transaction, $db_account where $db_transaction.account_type_id=$db_account.account_type_id and $db_account.user_id='$user_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$active_acct[$acct_ct] = $row['account_type_id'];
		$acct_ct += 1;
	}
			
	$acct_match = "";
	$acct_active = "";
	$active_acct_len = count($active_acct);
	$result=mysql_query("select $db_account_type.account_type_id, $db_account_type.user_id, $db_account_type.account_type, $db_account_type.account_usage, $db_account_type.account_description from $db_account_type, $db_account where $db_account.user_id='$user_id' and $db_account.account_type_id=$db_account_type.account_type_id and $db_account_type.active='yes' and $db_account.active='yes' ORDER BY $db_account_type.account_type ASC") or die(mysql_error());		
	while($row = mysql_fetch_array($result)){  			  			
		for ($i = 0; $i < $active_acct_len; $i++) {
			if ($row['account_type_id']==$active_acct[$i]){
				if ($row['account_usage']=="Bank"){
					//if ($row['user_id']!=$user_id){
					//	$invited_owner_id = $row['user_id'];
					//	$invited_owner_name = "";
					//	$invited_owner_name = get_invited_owner($invited_owner_id);
					//}
					if ($row['user_id']==$user_id){
						$acct_match = $acct_match."<tr><td class='col_sav1'><a href='".$mx003ac."&aid=".$row['account_type_id'];
						//if ($invited_owner_name!=""){
						//	$acct_match = $acct_match."&ioi=".$row['user_id']."'>".$row['account_type']."</a> (".$invited_owner_name.")</td>";	
						//}else{
							$acct_match = $acct_match."'>".$row['account_type']."</a></td>";	
						//}
						//$invited_owner_name = "";
						list($b_balance, $a_balance) = getBalance($row['account_type_id'],$user_id);
						//$b_balance = number_format(str_replace(",","",str_replace("$","",$b_balance)),2);
						//$a_balance = number_format(str_replace(",","",str_replace("$","",$a_balance)),2);
						$acct_match = $acct_match."<td class='col_sav2'>".$b_balance."</td><td class='col_sav2'>".$a_balance."</td></tr>";
					}
				}
				$acct_active = "yes";
				break;
			}else{
				$acct_active = "";
			}
		}//end of for loop
		
		if ($acct_active!="yes"){
			if ($row['account_usage']=="Bank"){
				//if ($row['user_id']!=$user_id){
				//	$invited_owner_id = $row['user_id'];
				//	$invited_owner_name = "";
				//	$invited_owner_name = get_invited_owner($invited_owner_id);
				//}
				if ($row['user_id']!=$user_id){
					$acct_match = $acct_match."<tr><td class='col_sav1'><a href='".$mx003ac."&aid=".$row['account_type_id'];
					//if ($invited_owner_name!=""){
					//	$acct_match = $acct_match."&ioi=".$row['user_id']."'>".$row['account_type']."</a> (".$invited_owner_name.")</td>";	
					//}else{
						$acct_match = $acct_match."'>".$row['account_type']."</a></td>";	
					//}
					//$invited_owner_name = "";
					$acct_match = $acct_match."<td colspan='2'><i>No transaction in this account.</i></td></tr>";
				}
			}
			$acct_active = "";
		}
	}//end of while loop 
	return $acct_match;
}	

//progress-bar
function progress_bar($percentage, $value, $color){
	$prog_bar_result = "<div id='progress-bar'>";
	$prog_bar_result = $prog_bar_result."<div id='progress-bar-percentage' style='width:".$percentage."%; background:".$color."'>";
	//if ($percentage > 5) 
	//	{
		$prog_bar_result = $prog_bar_result."".moneyFormat($value);
	//	} 
	//else 
	//	{$prog_bar_result = $prog_bar_result."<div class='progress-spacer'>&nbsp;</div>";}
	$prog_bar_result = $prog_bar_result."</div></div>";
	return $prog_bar_result;
}
function get_saving_amt($transaction_id, $amount_in){
	include('com_momax/functions/path.php');
	$saving_amount = 0;
	$result=mysql_query("select percent_of_income, addnl_amount from $db_saving where transaction_id='$transaction_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
		if ($row['percent_of_income']<=0){
			$saving_amount = number_format(($amount_in*($row['percent_of_income']/100))+$row['addnl_amount']);
		}else{
			if ($row['addnl_amount']<=0){
				$saving_amount = 0;	
			}else{
				$saving_amount = number_format($row['addnl_amount']);
			}
		}
	}
	return $saving_amount;
}
//sort
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

//get invited owner name
function get_invited_owner($invited_owner_id){
	include('com_momax/functions/path.php');
	$result=mysql_query("select first_name from $db_user where user_id='$invited_owner_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$invited_owner_name = $row['first_name'];
	}
	return $invited_owner_name;
}
//get activity_type name//
function get_activity_type($activity_type_id){
	include('com_momax/functions/path.php');		
	$result=mysql_query("select activity_type from $db_activity_type where activity_type_id='$activity_type_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$activity_type = $row['activity_type'];
	}
	return $activity_type;
}
//get activity_type name//
function get_activity_mode($activity_type_id){
	include('com_momax/functions/path.php');		
	$result=mysql_query("select activity_mode from $db_activity_type where activity_type_id='$activity_type_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$activity_mode = $row['activity_mode'];
	}
	return $activity_mode;
}
//get invited owner name
function get_account_owner_id($owner_account_id){
	include('com_momax/functions/path.php');
	$result=mysql_query("select user_id from $db_account_type where account_type_id='$owner_account_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$account_owner_id = $row['user_id'];
	}
	return $account_owner_id;
}
//get invited owner name
function check_invited_right($user_id,$group_id,$account_type_id){
	include('com_momax/functions/path.php');
	$check_id = "no1";
	$result=mysql_query("select * from $db_invite where user_id='$user_id' and group_id='$group_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$check_id = "yes";
	}
	if ($check_id == "yes"){
		$result=mysql_query("select * from $db_account where account_type_id='$account_type_id' and user_id='$user_id' and active='yes'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if ($row['access_right']<=2){	
				$check_id = "found";
			}
		}
		if ($check_id == "found"){
			$check_id = "yes";
		}else{
			$check_id = "no2";
		}
	}
	return $check_id;
}
//check account assignment//
function check_account_right($account_type_id,$user_id){
	include('com_momax/functions/path.php');
	$check_acct = "no";
	$result=mysql_query("select * from $db_account where account_type_id='$account_type_id' and user_id='$user_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row['access_right']<= 2){
			$check_acct = "yes";
		}
	}
	return $check_acct;
}
//process guest budget sheet and account set up//
function process_guest_setting($user_id,$account_type,$account_mode,$account_type_id,$transaction_date,$recurring_type,$amount_in,$amount_out,$transaction_type_id){
	include('com_momax/functions/path.php');
	
	$process_flag = "yes";
	if ($account_mode == "Saving"){
		$saving = "yes";
	}else {
		$saving = "no";
	}
	//create the activity type//
	$sql = mysql_query("INSERT INTO $db_activity_type(activity_type, activity_mode, activity_description, user_id, active) VALUES ('$account_type','$account_mode','$account_type','$user_id','yes')") or die(mysql_error());
	
	//get the newly income activity ID//				
	$result=mysql_query("select activity_type_id from $db_activity_type where user_id='$user_id' and activity_type='$account_type'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$activity_type_id = $row['activity_type_id'];
	}
				
	if ($recurring_type=="irregular"){
		//determine transaction_order on specific date
		$trans_order_counter = 0;
		$result=mysql_query("select * from $db_transaction where account_type_id='$account_type_id' and transaction_date='$transaction_date' ORDER BY transaction_date ASC") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$trans_order_counter += 1;
		}
		$trans_order_counter += 1;
		//create transaction for guest//
		$temp_reference_num = $i.date(m).date(d).date(H).date(i).date(s).rand(100000, 999999);
		$sql = mysql_query("INSERT INTO $db_transaction(account_type_id,user_id,transaction_date,transaction_order,amount_in,amount_out,reference_number,activity_type_id,saving,transaction_type_id,budget,active) VALUES ('$account_type_id','$user_id','$transaction_date', '$trans_order_counter','$amount_in', '$amount_out','$temp_reference_num','$activity_type_id','$saving','$transaction_type_id','yes','yes')") or die(mysql_error());
		//get transaction_id
		$result=mysql_query("select transaction_id from $db_transaction where user_id='$user_id' and reference_number='$temp_reference_num'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$temp_trans_id = $row['transaction_id']; //get current transaction_id
		}
		//generate recurring group id and update recurring set it
		$sql=mysql_query("INSERT INTO $db_recurring_group(recurring_set_id, transaction_id) VALUES ('$temp_trans_id', '$temp_trans_id')") or die(mysql_error());
		//get recurring_group_id
		$result=mysql_query("select recurring_group_id from $db_recurring_group where transaction_id='$temp_trans_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$temp_recurring_set_id = $row['recurring_group_id'];
		}	
		//set recurring set id
		$sql=mysql_query("UPDATE $db_recurring_group SET recurring_set_id='$temp_recurring_set_id' WHERE transaction_id='$temp_trans_id'") or die(mysql_error());		  		
		//set reference_number to empty
		$sql=mysql_query("UPDATE $db_transaction SET reference_number='$temp_recurring_set_id' WHERE user_id='$user_id' and transaction_id='$temp_trans_id'") or die(mysql_error());		  		
	}
	if ($recurring_type=="month"){
		$trans_date_arr = array(); 
		$trans_order_arr = array();
		$trans_date_arr[0] = $transaction_date;
		$no_of_recurring = 12;
		$trans_order_counter = 0;
		$recurring_ty = "monthly";
		for ($i=1; $i<$no_of_recurring; $i++){
			$trans_date_arr[$i] = date( 'Y-m-j' , strtotime("+1 month",strtotime($trans_date_arr[$i-1])));
		}
		for ($i=0; $i<$no_of_recurring; $i++){
			$result=mysql_query("select transaction_date from $db_transaction where account_type_id='$account_type_id' and transaction_date='$trans_date_arr[$i]'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				$trans_order_counter += 1;
			}
			$trans_order_arr[$i] = $trans_order_counter + 1;
			$trans_order_counter = 0;
		}
		//insert recurring info to $db_recurring table
		$sql = mysql_query("INSERT INTO $db_recurring(recurring_type, no_of_recurring, open_ended, active) VALUES ('$recurring_ty','$no_of_recurring','no','yes')") or die(mysql_error());
		//get recurring id from $db_recurring
		$result=mysql_query("select recurring_id from $db_recurring ORDER BY recurring_id ASC") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$recurring_id = $row['recurring_id'];
		}
		for ($i=0; $i<$no_of_recurring; $i++){
			$temp_reference_num = $i.date(m).date(d).date(H).date(i).date(s).rand(100000, 999999);
			$sql = mysql_query("INSERT INTO $db_transaction(account_type_id, user_id, transaction_date, transaction_order, amount_in, amount_out, reference_number, activity_type_id, saving, transaction_type_id, budget,recurring_id,active) VALUES ('$account_type_id','$user_id','$trans_date_arr[$i]', '$trans_order_arr[$i]','$amount_in', '$amount_out','$temp_reference_num','$activity_type_id','$saving','$transaction_type_id','yes','$recurring_id','yes')") or die(mysql_error());
			//get transaction_id
			$result=mysql_query("select transaction_id from $db_transaction where user_id='$user_id' and reference_number='$temp_reference_num'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				$temp_trans_id = $row['transaction_id']; //get current transaction_id
			}
			//generate recurring group id and update recurring set it
			$sql=mysql_query("INSERT INTO $db_recurring_group(recurring_set_id, transaction_id) VALUES ('$temp_trans_id', '$temp_trans_id')") or die(mysql_error());
			//get recurring_group_id
			$result=mysql_query("select recurring_group_id from $db_recurring_group where transaction_id='$temp_trans_id'") or die(mysql_error());
			while($row = mysql_fetch_array($result)){
				$temp_recurring_set_id = $row['recurring_group_id'];
			}	
			//set recurring set id
			$sql=mysql_query("UPDATE $db_recurring_group SET recurring_set_id='$temp_recurring_set_id' WHERE transaction_id='$temp_trans_id'") or die(mysql_error());	  		
			//set reference_number to empty
			$sql=mysql_query("UPDATE $db_transaction SET reference_number='$temp_recurring_set_id' WHERE user_id='$user_id' and transaction_id='$temp_trans_id'") or die(mysql_error());		  		
		}
	}
	$process_flag = "yes";
	return $process_flag;
}

//get user_id for project budgeting
function get_project_id($prj_id){
	include('com_momax/functions/path.php');
	$found_user_id = "";
	$result=mysql_query("select user_id from $db_transaction where project_id='$prj_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$found_user_id = $row['user_id'];
	}
	return $found_user_id;
}

//check if account is set for budget vs. actual
function chk_budvsact($set_user_id, $set_account_id){
	include('com_momax/functions/path.php');
	$found_set_id = "";
	$result=mysql_query("select user_id from $db_budvsact where user_id='$set_user_id' and account_id='$set_account_id' and active='yes'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$found_set_id = "yes";
	}
	return $found_set_id;
}

function cal_budvsact($user_id){
	include('com_momax/functions/path.php');
	include('in_cludesax/config.php');
	//declare variables
	$current_yr = date(Y);	
	$current_mon = date(m);
	
	$current_wek_expense = 0;
	$current_wek_expense_for = 0;
	$current_wek_expense_act = 0;
	$current_mon_expense = 0;
	$current_mon_expense_for = 0;
	$current_mon_expense_act = 0;
	$mon_expense_for = 0;
	$mon_expense_act = 0;
	$current_mon_net = 0;
	
	$current_mon_income_ytd = 0;
	$current_mon_saving_ytd = 0;
	$current_mon_saved_ytd = 0;
	$current_mon_expense_ytd = 0;
	$current_mon_net_ytd = 0;
	
	$saving_act_type = array(); //saving type of activity
	$found_act_type_id = array(); //activity type id
	$all_activity_mode = array(); //activity mode
	
	$saved_act_mon_amt = array();
	$saving_act_mon_amt_all = array();
	
	$saved_act_ytd_amt = array();
	$saving_act_ytd_amt_all = array();
	
	$ts = strtotime(date(date(Y).'-'.date(m).'-'.date(d)));
	$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
	$start_wk = date('Y-m-d', $start);
	$end_wk = date('Y-m-d', strtotime('next saturday', $start));
	$end_wk1 = date('Y-m-d', strtotime('next saturday', $start));
						
	//get all unquie activity type ids that in transaction table
	$saving_arr_counter = 0;
	$result=mysql_query("select DISTINCT activity_type_id from $db_transaction where user_id='$user_id' and budget='yes' and active='yes' ORDER BY transaction_date ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$found_act_type_id[$saving_arr_counter] = $row['activity_type_id'];	
		$saving_arr_counter += 1;
	}
	
	//get activity_type that match the unquie activity type id
	for ($i = 0; $i < count($found_act_type_id); $i++){
		$result=mysql_query("select activity_type, activity_mode from $db_activity_type where activity_type_id='$found_act_type_id[$i]'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$saving_act_type[$i] = $row['activity_type'];
			$all_activity_mode[$i] = $row['activity_mode'];
		}
	}
	
	//Loop through the transaction table and do the neccessary calculation  	
	$result=mysql_query("select * from $db_transaction where '$start_wk'<=transaction_date and transaction_date<='$end_wk' and user_id='$user_id' and active='yes' ORDER BY $db_transaction.transaction_date ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		//budget & saving are both budget
		$orgDate = $row['transaction_date'];
		$partsArr = explode("-",$orgDate);
		$transaction_date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
		$transaction_yr = $partsArr[0]; 
		$transaction_mo = $partsArr[1]; 
		
		if ($current_yr==$transaction_yr){  //calculate current year transaction only
			if ($current_mon==$transaction_mo){ //calculat the current week only
				for ($i = 0; $i < count($found_act_type_id); $i++){
					if ($found_act_type_id[$i] == $row['activity_type_id']){
						$activity_mode_found = $all_activity_mode[$i];
					}
				}
				if ($row['budget']=="yes"){
					if ($activity_mode_found=="Expense"){
						$current_wek_expense = $current_wek_expense + $row['amount_out'];
					}
				}else { //end if statement to do budget calculation - not working, not user - tou 6/23/13
					if ($row['transaction_type_id']=="100101"){
						if ($row['balance_date']!="0000-00-00" and $row['balance_date']!="NULL" and $row['balance_date']!=""){
							$current_wek_expense_act = $current_wek_expense_act + $row['amount_out'];
						}else {
							$current_wek_expense_for = $current_wek_expense_for + $row['amount_out'];
						}
					}
				}
			}//end if month matchs
		}//end if year matches
	}//end of while loop	
	
	//Loop through the transaction table and do the neccessary calculation  	
	$result=mysql_query("select * from $db_transaction where user_id='$user_id' and active='yes' ORDER BY $db_transaction.transaction_date ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		//budget & saving are both budget
		$orgDate = $row['transaction_date'];
		$partsArr = explode("-",$orgDate);
		$transaction_date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
		$transaction_yr = $partsArr[0]; 
		$transaction_mo = $partsArr[1]; 
		
		//calculate all transactions
		if ($row['balance_date']!="0000-00-00" and $row['balance_date']!="NULL" and $row['balance_date']!=""){
			$mon_expense_act = $current_mon_expense_act + $row['amount_out'];
		}
		$mon_expense_for = $current_mon_expense_for + $row['amount_out'];
		
		if ($current_yr==$transaction_yr){  //calculate current year transaction only
			if ($current_mon==$transaction_mo){ //calculat the current month only
				for ($i = 0; $i < count($found_act_type_id); $i++){
					if ($found_act_type_id[$i] == $row['activity_type_id']){
						$activity_mode_found = $all_activity_mode[$i];
					}
				}
				if ($row['budget']=="yes"){
					if ($activity_mode_found=="Expense"){
						$current_mon_expense = $current_mon_expense + $row['amount_out'];
					}
				}//end if statement to do budget calculation - not working, not user - tou 6/23/13
				if ($row['transaction_type_id']=="100101"){
					if ($row['balance_date']!="0000-00-00" and $row['balance_date']!="NULL" and $row['balance_date']!=""){
						$current_mon_expense_act = $current_mon_expense_act + $row['amount_out'];
					}
					$current_mon_expense_for = $current_mon_expense_for + $row['amount_out'];
				}
			}//end if month matchs
		}//end if year matches
	}//end of while loop																
	return array($current_mon_expense, $current_wek_expense);
}

//get transaction account number and transaction type
function get_trans_acct($ref_num, $account_id){
	include('com_momax/functions/path.php');
	$trans_type_id = "";
	$result=mysql_query("select amount_in, transaction_type_id from $db_transaction where reference_number='$ref_num' and account_type_id='$account_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$trans_amt = $row['amount_in'];
		$trans_type_id = $row['transaction_type_id'];
	}
	return array($trans_amt, $trans_type_id);
}
//////get share status for group user
function get_share_sta($group_id, $user_id){
	include('com_momax/functions/path.php');
	$not_invited = "no";
	$result=mysql_query("select * from $db_invite where group_id='$group_id' and user_id='$user_id' and active<>'non' and active<>'no'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row['active']=="yes"){
			$invite_status = "Share";
		}else{
			$invite_status ="Not Share";
		}
		$not_invited = "yes";
	}
	//if ($not_invited == "no"){
	//	$invite_status ="Not Share";
	//}
	return $invite_status;
}
//////get image extension
function getExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; } 
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}
////delete attachments
function del_attach($user_id, $recurring_id){
	include('com_momax/functions/path.php');
	$target_path = $m_attach_path; //$_SERVER['DOCUMENT_ROOT']."/in_attach/"; //all pics save in the main root
	//delete original attach before insert new one to prevent duplication
	$result=mysql_query("select * from $db_transaction_attach where user_id='$user_id' and recurring_id='$recurring_id'") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$org_attach_name = $m_attach_path.$row['attach_name'];
		unlink($org_attach_name);
	}
}

class account{
	var $name;
		function set_name($new_name) {
				$this->name = $new_name;
		}
		function get_name() {
				return $this->name;
		}
}

?>