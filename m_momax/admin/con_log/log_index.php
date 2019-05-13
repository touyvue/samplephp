<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		$num_group = 0;
		$num_invite = 0;
		if($_GET['act']=="0"){
			$nofound = "<h6>Update your info here!</h6>";
		}
		//////////////////////////////////////////////
		//get for group admin rights
		$grp_admin = "no";
		$result=mysql_query("select * from $db_group_user where user_id='$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if($row['admin']=="yes"){
				$grp_admin = "yes";
			}
			$grp_admin_id = $row['group_admin_id'];
		}
		$result=mysql_query("select * from $db_group_user where group_admin_id='$grp_admin_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if ($row['user_id']!=$user_id){
				$num_group += 1;
			}
		}
		
		//get group admin code and check to make sure group is active
		$grp_active = "no";
		$result=mysql_query("select * from $db_group_admin where group_admin_id='$grp_admin_id' and active='yes'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$grp_active = "yes";
		}
		if ($grp_active == "no"){
			$grp_admin = "no";
		}
		//////////////////////////////////////////////
		//mysql_select_db($db, $connection) or die (mysql_error());
		$result=mysql_query("select login_id, password, email, admin_sec from $db_user where user_id='$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$seclevel = $row['admin_sec'];
			$temp_email = $row['email'];
			$temp_pass = $row['password'];
		}
		
		$result=mysql_query("select * from $db_invite where group_id='$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			if ($row['active']=="no"){
				$num_invite += 1;
			}
			if ($row['active']=="yes" or $row['active']=="ina"){
				$num_group += 1;
			}
		}
		
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>