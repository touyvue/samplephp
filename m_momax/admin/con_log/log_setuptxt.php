<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		
	  	//get all checked accounts for this user
		$account_sec = 3;
		$result=mysql_query("select * from $db_account where user_id='$user_id' ORDER BY account_type_id ASC") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if ($row['access_right']<3){
				$account_sec = 2;
			}
		}
	  	
	  	//Get security level - don't use $row['admin_sec'] anymore - tou 9/23/11
		$result=mysql_query("select admin_sec from $db_user where user_id='$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$seclevel = $row['admin_sec'];
		}
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>