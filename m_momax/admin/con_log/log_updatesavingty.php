<?php
	if ($_SESSION['login']=="yes"){
		$user_id = $_SESSION['uid'];
		mysql_select_db($db, $connection) or die (mysql_error());
		$result=mysql_query("select * from $db_user where user_id = '$user_id'") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$group_id = $row['group_id'];
		}
		
		if ($_SESSION['seclevel']==1 or $_SESSION['seclevel']==2){
			//saving type start here
			if($_POST['update_sav']=="new"){	
				$sav_type_id = $_POST['sav_id'];
				$sav_type = $_POST['sav_type'];
				$sav_desc = $_POST['sav_desc'];
				$sql=sprintf("UPDATE $db_saving_type SET saving_type='$sav_type', saving_description='$sav_desc' WHERE saving_type_id='$sav_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}	
			if($_GET['taidd']!=""){	
				$trans_type_id = $_GET['taidd'];
				$sql=sprintf("UPDATE $db_saving_type SET active='no' WHERE saving_type_id='$sav_type_id'");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			if($_POST['new_sav']=="new"){	
				$sav_type_id = $_POST['sav_id'];
				$sav_type = $_POST['sav_type'];
				$sav_desc = $_POST['sav_desc'];
				$sql=sprintf("INSERT INTO $db_saving_type (saving_type, saving_description, group_id, user_id, active) VALUES ('$sav_type', '$sav_desc', '$group_id', '$user_id', 'yes')");
				if (!mysql_query($sql,$connection)){
			  		die('Error: ' . mysql_error());
			  	}
			}
			
			if($_GET['taide'] !="")	{
				$saving_type = "";
				$result=mysql_query("select * from $db_saving_type ORDER BY saving_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					if (($row['user_id']==$user_id or $_SESSION['seclevel']==1)and $row['active']=="yes" and $row['group_id']==$group_id){
						$owner_lk = $row['user_id'];
						if ($row['saving_type_id'] == $_GET['taide']){
							if ($_SESSION['seclevel']==1){
								$owner = getname($owner_lk);
								$saving_type = $saving_type."<tr><td class='coltab1'>".$owner."</td><td class='coltab2'><input type='text' name='sav_type' value='".$row['saving_type']."'></td><td class='coltab3'><input type='text' size='50' name='sav_desc' value='".$row['saving_description']."'></td><td class='coltab4'><a href='javascript: submitsav()'>Update</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
							}else{
								$saving_type = $saving_type."<tr><td class='coltab2'><input type='text' name='sav_type' value='".$row['saving_type']."'></td><td class='coltab3'><input type='text' size='50' name='sav_desc' value='".$row['saving_description']."'></td><td class='coltab4'><a href='javascript: submitsav()'>Update</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
							}
							$saving_type = $saving_type."<input type='hidden' name='sav_id' value='".$row['saving_type_id']."'><input type='hidden' name='update_sav' value='new'>";
						}else{
							if ($_SESSION['seclevel']==1){
								$owner = getname($owner_lk);
								$saving_type = $saving_type."<tr><td class='coltab1'>".$owner."</td><td class='coltab2'><label>".$row['saving_type']."</label></td><td class='coltab3'>".$row['saving_description']."</td><td class='coltab4'><a href='".$index_url."?pa=mx007us&wk=sav&taide=".$row['saving_type_id']."'>Edit</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
							}else{
								$saving_type = $saving_type."<tr><td class='coltab2'><label>".$row['saving_type']."</label></td><td class='coltab3'>".$row['saving_description']."</td><td class='coltab4'><a href='".$index_url."?pa=mx007us&wk=sav&taide=".$row['saving_type_id']."'>Edit</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
							}
						}
					}
				}
			}else{
				$saving_type = "";
				$result=mysql_query("select * from $db_saving_type ORDER BY saving_type ASC") or die(mysql_error());
				while($row = mysql_fetch_array($result)){
					$owner_lk = $row['user_id'];
					if (($row['user_id']==$user_id or $_SESSION['seclevel']==1)and $row['active']=="yes" and $row['group_id']==$group_id){
						if ($_SESSION['seclevel']==1){
							$owner = getname($owner_lk);
							$saving_type = $saving_type."<tr><td class='coltab1'>".$owner."</td><td class='coltab2'><label>".$row['saving_type']."</label></td><td class='coltab3'>".$row['saving_description']."</td><td class='coltab4'><a href='".$index_url."?pa=mx007us&wk=sav&taide=".$row['saving_type_id']."'>Edit</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
						}else{
							$saving_type = $saving_type."<tr><td class='coltab2'><label>".$row['saving_type']."</label></td><td  class='coltab3'>".$row['saving_description']."</td><td class='coltab4'><a href='".$index_url."?pa=mx007us&wk=sav&taide=".$row['saving_type_id']."'>Edit</a> / <a href='".$index_url."?pa=mx007us&wk=sav&taidd=".$row['saving_type_id']."'>Delete</a></td></tr>";
						}
					}
				}
			}
			
			if($_GET['taida'] =="new"){
				if ($_SESSION['seclevel']==1){
					$saving_type = $saving_type."<tr><td class='coltab'></td><td class='coltab2'><input type='text' name='sav_type'></td><td class='coltab3'><input type='text' size='50' name='sav_desc'></td><td class='coltab4'><a href='javascript: submitsav()'>Add</a></td></tr>";
				}else{
					$saving_type = $saving_type."<tr><td class='coltab2'><input type='text' name='sav_type'></td><td class='coltab3'><input type='text' size='50' name='sav_desc'></td><td class='coltab4'><a href='javascript: submitsav()'>Add</a></td></tr>";
				}
				$saving_type = $saving_type."<input type='hidden' name='sav_id' value='".$row['saving_type_id']."'><input type='hidden' name='new_sav' value='new'>";
			}else{
				if ($_SESSION['seclevel']==1){
					$saving_type = $saving_type."<tr><td class='tablepad' colspan='4'><a href='".$index_url."?pa=mx007us&wk=sav&taida=new'>Add new saving type</a></td></tr>";
				}else{
					$saving_type = $saving_type."<tr><td class='tablepad' colspan='3'><a href='".$index_url."?pa=mx007us&wk=sav&taida=new'>Add new saving type</a></td></tr>";
				}
			}//saving type end here
		}//end of saving types with security level 1 and 2
		mysql_close($connection);
	}else{
		print "<script>";
		print "self.location='".$index_url."';";
		print "</script>";
	}
?>