<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();		
		$groupSetting = "Organization";		
		$yes = "yes";
		$no = "no";
		$todayDate = date('Y-m-d');
				
		try{//get consortiumID
			$result = $db->prepare("SELECT consortium_id FROM $db_member WHERE member_id=?");
			$result->execute(array($memberID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortID = $row['consortium_id'];
		}
				
		try{//get license package
			$result = $db->prepare("SELECT admin1,admin2,license_id FROM $db_consortium WHERE consortium_id=? ");
			$result->execute(array($consortID)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			//$licPackage = $row['package'];
			$licenseID =  $row['license_id'];
			if ($row['license_id'] == "1003" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
				$consAdmin = "yes"; //yes, consort
			}
		}
		
		$orgList = "";
		$orgAdmin = "no";
		if ($_GET['oid'] != "new"){ //update Org
			$organID = $_GET['oid']; //get OrgID
			try{//get OrgID and Name
				$result = $db->prepare("SELECT organization,organization_id,admin1,admin2 FROM $db_organization WHERE organization_id=?");
				$result->execute(array($organID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					if ($licenseID == "1003" or $licenseID == "1002"){
						$orgList = $orgList."<tr><td><a href='".$mx005ot."&oid=".$row['organization_id']."#organSetup'>".str_replace('\\','',$row['organization'])."</a></td><td><a href='".$mx005gt."&sid=".$licenseID."&oid=".$organID."&gid=new'><button type='button' class='btn btn-xs btn-warning'>New Group</button></a></td></tr>";
						if ($memberID == $row['admin1'] or $memberID == $row['admin2']){
							$orgAdmin = "yes"; //validate Org admin rights
						}
					}else{
						$orgList = $orgList."<tr><td><a href='".$mx005ot."&oid=".$row['organization_id']."#organSetup'>".str_replace('\\','',$row['organization'])."</a></td><td></td></tr>";
					}
				}
			}else{
				$orgList = '<tr><td>No organization available</td><td><a href="'.$mx005gt.'&sid='.$licenseID.'&gid=new"><button type="button" class="btn btn-xs btn-warning">New Group</button></a></td></tr>';
			}		
		}
				
		$chgState = "none";
		$organButton = "Organization";
		if ($_GET['oid'] != "" and $_GET['oid'] != "new"){
			$organID = $_GET['oid'];
			try{//get Org info
				$result = $db->prepare("SELECT organization,street,city,state,zip,email,website,admin1,admin2 FROM $db_organization WHERE organization_id=?");
				$result->execute(array($organID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$organization = str_replace('\\','',$row['organization']);
				$street = str_replace('\\','',$row['street']);
				$city = str_replace('\\','',$row['city']);
				$state = str_replace('\\','',$row['state']);
				$zip = $row['zip'];
				$email = $row['email'];
				$website = str_replace('\\','',$row['website']);
				if ($row['admin1'] != "" or $row['admin1'] != NULL or $row['admin1'] != 0){
					$admin1 = $row['admin1'];
				}else{
					$admin1 = 0;
				}
				if ($row['admin2'] != "" or $row['admin2'] != NULL or $row['admin2'] != 0){
					$admin2 = $row['admin2'];
				}else{
					$admin2 = 0;
				}
			}
			
			$organPix = "";
			try{//get pix name
				$result = $db->prepare("SELECT pix_name FROM $db_organization_pix WHERE organization_id=?");
				$result->execute(array($organID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$organPix = "<a href='images/c_pix/".$row['pix_name']."' class='prettyPhoto'><img src='images/c_pix/".$row['pix_name']."'></a>";
			}			
			$chgState = "edit";
			if ($licenseID == "1000" or $licenseID == "1001"){
				$organButton = "Update";
			}else{
				$organButton = "Update Organization";
			}
			//get Org member status
			$allActMem = "";
			if ($orgAdmin == "yes" or $consAdmin == "yes"){
				try{
					$result = $db->prepare("SELECT $db_member.member_id,$db_member.first_name,$db_member.last_name,$db_group_rights.active,$db_group_rights.org_rights FROM $db_organization,$db_group,$db_group_rights,$db_member WHERE $db_organization.organization_id=? AND $db_organization.organization_id=$db_group.organization_id AND $db_group.group_id=$db_group_rights.group_id AND $db_group_rights.member_id=$db_member.member_id GROUP BY $db_member.member_id");
					$result->execute(array($organID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; script>";
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						if ($row['member_id'] == $admin1 or $row['member_id'] == $admin2){
							$iAdmin = "yes";
							if($row['member_id'] == $memberID){
								$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" checked="checked" value="yes" onclick="chkAdmin('.$row['member_id'].','.$organID.',2,0)"></td>';
							}else{
								$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" checked="checked" value="yes" onclick="chkAdmin('.$row['member_id'].','.$organID.',2,1)"></td>';
							}
						}else{
							$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" value="yes" onclick="chkAdmin('.$row['member_id'].','.$organID.',2,1)"></td>';
						}
						$allActMem = $allActMem.'<td>'.str_replace('\\','',$row['first_name']).' '.str_replace('\\','',$row['last_name']).'</td>';
						if ($row['active']=="yes"){
							$allActMem = $allActMem.'<td><input type="radio" id="sy'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="yes" onclick="uptMemSta('.$row['member_id'].','.$organID.',2)" /> Yes</td><td><input type="radio" id="sn'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="no" onclick="uptMemSta('.$row['member_id'].','.$organID.',2)" /> No</td><td></td></tr>';
						}else{
							$allActMem = $allActMem.'<td><input type="radio" id="sy'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="yes" onclick="uptMemSta('.$row['member_id'].','.$organID.',2)" /> Yes</td><td><input type="radio" id="sn'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="no" onclick="uptMemSta('.$row['member_id'].','.$organID.',2)" /> No</td><td></td></tr>';
						}
					}//end foreach
				}
			}
		}//end if - get Org info
		if ($_GET['oid'] == "new"){ //to create new Org
			$chgState = "new";
			$organID = "";
			$organButton = "Create Organization";
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if ($_POST['chgstate'] == "new"){//new organization set up
				if ($_POST['organization'] != "" and $_POST['cemail'] != ""){
					$consortiumID = $_POST['conID'];
					$organization = $_POST['organization'];
					//$organization = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $organization);
					$organization = str_replace('"', "", $organization); //remove " from note
					$cstreet = $_POST['cstreet'];
					//$cstreet = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstreet);
					$cstreet = str_replace('"', "", $cstreet); //remove " from note
					$ccity = $_POST['ccity'];
					//$ccity = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $ccity);
					$ccity = str_replace('"', "", $ccity); //remove " from note
					$cstate = $_POST['cstate'];
					$cstate = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstate);
					$cstate = str_replace('"', "", $cstate); //remove " from note
					$czip = $_POST['czip'];
					$cemail = $_POST['cemail'];
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					
					$genNum = date(h).date(i).date(s).rand(100,999);
					try{//insert new org
						$result = $db->prepare("INSERT INTO $db_organization (organization,street,city,state,zip,email,website,consortium_id,admin1,active) VALUES (?,?,?,?,?,?,?,?,?,?)");
						$result->execute(array($organization,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$consortiumID,$genNum,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get orgID
						$result = $db->prepare("SELECT organization_id FROM $db_organization WHERE admin1=? ORDER BY organization_id ASC");
						$result->execute(array($genNum)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$organID = $row['organization_id'];
					}
					try{//create first group for the Org
						$result = $db->prepare("INSERT INTO $db_group (group_name,website,organization_id,active) VALUES (?,?,?,?)");
						$result->execute(array($organization,$cwebsite,$organID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//ger groupID
						$result = $db->prepare("SELECT group_id FROM $db_group WHERE organization_id=? ORDER BY group_id ASC");
						$result->execute(array($organID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$groupID = $row['group_id'];
					}
	
					//if upload Org logo, if there is one
					if ($_FILES['cmx_self']['name']!=""){ //make sure there is file
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/o_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/o_pix/"; //all pics save in the main root
						$target_path = $target_path.$organID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $organID.".". end(explode(".", $_FILES['cmx_self']['name']));
						try{//insert Org logo, since this is new Org setup
							$result = $db->prepare("INSERT INTO $db_organization_pix (pix_name,organization_id,active) VALUES (?,?,?)");
							$result->execute(array($pix_name,$organID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						if (file_exists($target_path_on.$pix_name)){//check for existing file
							unlink($target_path_on.$pix_name); //delete old file
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)){
							//upload logo
						} 						
					}
				}//end of consortium
				
				//add admin1
				if($_POST['meadmin'] == "admin1"){
					try{//update Org with admin1
						$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
						$result->execute(array($memberID,$organID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$admin1ID = $memberID; //admin1 is selft
				}else{ //new Org admin1
					if ($_POST['cd1email'] != ""){
						$cd1firstname = $_POST['cd1firstname'];
						//$cd1firstname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cd1firstname);
						$cd1firstname = str_replace('"', "", $cd1firstname); //remove " from note
						$cd1lastname = $_POST['cd1lastname'];
						//$cd1lastname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cd1lastname);
						$cd1lastname = str_replace('"', "", $cd1lastname); //remove " from note
						$cd1email = $_POST['cd1email'];
						$cd1password = "Loveland1"; //pre-set password
						
						$genNum = date(h).date(i).date(s).rand(100,999);
						try{//insert new member
							$result = $db->prepare("INSERT INTO $db_member (consortium_id,first_name,last_name,email,date_active,active) VALUES (?,?,?,?,?,?)");
							$result->execute(array($genNum,$cd1firstname,$cd1lastname,$cd1email,$todayDate,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//get memberID
							$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=? ORDER BY member_id ASC");
							$result->execute(array($genNum));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$admin1ID = $row['member_id'];
						}
						try{//set memberID as Org admin
							$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
							$result->execute(array($admin1ID,$organID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//update member consortiumID fiedl
							$result = $db->prepare("UPDATE $db_member SET consortium_id=? WHERE member_id=?");
							$result->execute(array($consortiumID,$admin1ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						
						//hash and save password
						$hash_cost_log2 = 8;
						$hash_portable = FALSE;
						$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
						$hash = $hasher->HashPassword($cd1password);
						try{//update profile
							$result = $db->prepare("UPDATE $db_member SET password=? WHERE member_id=?");
							$result->execute(array($hash,$admin1ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						unset($hasher);
						
						//insert 1 account/////////////////////////////////////////
						$acctName1 = "Checking";
						$acctDesc1 = "General expenses";
						try{//update project details
							$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
							$result->execute(array($acctName1,1,$acctDesc1,$admin1ID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//get new accountID
							$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? ORDER BY account_id ASC");
							$result->execute(array($admin1ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$accountID1 = $row['account_id'];
						}
						try{//insert account level 3, admin, rights
							$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
							$result->execute(array($admin1ID,3,$accountID1,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						//1 budget/////////////////////////////////////////
						$budName1 = "Monthly Budget";
						$budDesc1 = "General budget";
						try{//insert new budget sheet
							$result = $db->prepare("INSERT INTO $db_budget (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
							$result->execute(array($budName1,1,$budDesc1,$admin1ID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//get new budgetID
							$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? ORDER BY budget_id ASC");
							$result->execute(array($admin1ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$budgetID1 = $row['budget_id'];
						}
						try{//insert budget level 3, admin, rights
							$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
							$result->execute(array($admin1ID,3,$budgetID1,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						$catNameArr = array("Mortgage payment","Electric & heat","Water","Cell phone","Food","Dinning","Misc expenses","Work income","Revenue","Emergency saving","Credit card");
						$catNameArrCt = count($catNameArr);
						for($i=0; $i<$catNameArrCt; $i++){
							try{//insert new categories
								$result = $db->prepare("INSERT INTO $db_category (list_order,category,description,member_id,active) VALUES (?,?,?,?,?)");
								$result->execute(array($i,$catNameArr[$i],$catNameArr[$i],$admin1ID,$yes));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						}
						
						//send email to reset password
						$todate = date('Y-m-d');
						$ip_address = $_SERVER['REMOTE_ADDR'];
						$len = 19;
						$base='123456789ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
						$max=strlen($base)-1;
						$reset_code='';
						mt_srand((double)microtime()*1000000);
						while (strlen($reset_code)<$len+1)
						$reset_code.=$base{mt_rand(0,$max)};   
						$reset_code = substr($reset_code,0,5)."".substr($admin1ID,0,3)."".substr($reset_code,5,5)."".substr($admin1ID,3,3)."".substr($reset_code,10,10);
						
						try{//insert record for reset password
							$result = $db->prepare("INSERT INTO $db_reset_log(date_request,user_email,member_id,reset_code,ip_address,active) VALUES (?,?,?,?,?,?)");
							$result->execute(array($todate,$cd1email,$admin1ID,$reset_code,$ip_address,$no));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
							
						$message =  "<html><head></head><body><h3>Welcome to Maxmoni!</h3><p>Thank you for signing up.<br></p><p>A Maxmoni account has been set up for you. If you are not the right owner of this email (".$cd1email."), please delete this email.  Otherwise please click on the link below to set up your password.<br>";
						$message =  $message."<br>Link: <a href='".$mx008cp."&sg=new&cid=".$reset_code."'>Click here to set password</a><br>";
						$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br>Easy group budget tracking!<br><br><br></body></html>";
						$to = $cd1email;
						$subject = "Password setup in Maxmoni.com";
						$from = "emailconfirm@maxmoni.com";
		
						$headers  = "From: Maxmoni<".$from.">\r\n";
						$headers .= "Content-type: text/html\r\n";
						mail($to,$subject,$message,$headers);
						
						$chk_login = "Set up password";
					}//end of admin1
				}//end of else admin1
				
				try{//insert group rights for adminI
					$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,guest,active) VALUES (?,?,?,?,?,?)");
					$result->execute(array($groupID,$admin1ID,3,3,$no,$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
						
				//add admin2
				if($_POST['meadmin'] == "admin2"){
					try{//update Org admin2
						$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
						$result->execute(array($memberID,$organID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$admin2ID = $memberID; //admin2 is self
				}else{
					if ($_POST['cd2email'] != ""){
						$cd2firstname = $_POST['cd2firstname'];
						//$cd2firstname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cd2firstname);
						$cd2firstname = str_replace('"', "", $cd2firstname); //remove " from note
						$cd2lastname = $_POST['cd2lastname'];
						//$cd2lastname = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cd2lastname);
						$cd2lastname = str_replace('"', "", $cd2lastname); //remove " from note
						$cd2email = $_POST['cd2email'];
						$cd2password = "Loveland1"; 
	
						$genNum = date(h).date(i).date(s).rand(100,999);
						try{//insert new member
							$result = $db->prepare("INSERT INTO $db_member (consortium_id,first_name,last_name,email,date_active,active) VALUES (?,?,?,?,?,?)");
							$result->execute(array($genNum,$cd2firstname,$cd2lastname,$cd2email,$todayDate,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//get memberID
							$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=? ORDER BY member_id ASC");
							$result->execute(array($genNum)); 
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$admin2ID = $row['member_id'];
						}
						try{//update org with Admin 
							$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
							$result->execute(array($admin2ID,$organID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//update member consortiumID
							$result = $db->prepare("UPDATE $db_member SET consortium_id=? WHERE member_id=?");
							$result->execute(array($consortiumID,$admin2ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
					
						//hash and save password	
						$hash_cost_log2 = 8;
						$hash_portable = FALSE;
						$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
						$hash = $hasher->HashPassword($cd1password);
						try{//update password
							$result = $db->prepare("UPDATE $db_member SET password=? WHERE member_id=?");
							$result->execute(array($hash,$admin2ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						unset($hasher);
						
						//insert 1 account/////////////////////////////////////////
						$acctName2 = "Checking";
						$acctDesc2 = "General expenses";
						try{//update project details
							$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
							$result->execute(array($acctName2,1,$acctDesc2,$admin2ID,$yes));
						} catch(PDOException $e) {
							echo "message001 - Sorry, system is experincing problem. Please check back.";
						}
						try{//get new accountID
							$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? ORDER BY account_id ASC");
							$result->execute(array($admin2ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$accountID2 = $row['account_id'];
						}
						try{//insert level 3 rights admin
							$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
							$result->execute(array($admin2ID,3,$accountID2,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						//1 budget/////////////////////////////////////////
						$budName2 = "Monthly Budget";
						$budDesc2= "General budget";
						try{
							$result = $db->prepare("INSERT INTO $db_budget (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
							$result->execute(array($budName2,1,$budDesc2,$admin2ID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						try{//get new budgetID
							$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? ORDER BY budget_id ASC");
							$result->execute(array($admin2ID));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$budgetID2 = $row['budget_id'];
						}
						try{//insert level 3 admin rights
							$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
							$result->execute(array($admin2ID,3,$budgetID2,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						$catNameArr = array("Mortgage payment","Electric & heat","Water","Cell phone","Food","Dinning","Misc expenses","Work income","Others income","Emergency saving","Credit card");
						$catNameArrCt = count($catNameArr);
						for($i=0;$i<$catNameArrCt;$i++){
							try{ //insert new categories
								$result = $db->prepare("INSERT INTO $db_category (list_order,category,description,member_id,active) VALUES (?,?,?,?,?)");
								$result->execute(array($i,$catNameArr[$i],$catNameArr[$i],$admin2ID,$yes));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						}
						
						//send email to reset password
						$todate = date('Y-m-d');
						$ip_address = $_SERVER['REMOTE_ADDR'];
						$len = 19;
						$base='123456789ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
						$max=strlen($base)-1;
						$reset_code='';
						mt_srand((double)microtime()*1000000);
						while (strlen($reset_code)<$len+1)
						$reset_code.=$base{mt_rand(0,$max)};   
						$reset_code = substr($reset_code,0,5)."".substr($admin2ID,0,3)."".substr($reset_code,5,5)."".substr($admin2ID,3,3)."".substr($reset_code,10,10);
						
						try{ //insert reset password record
							$result = $db->prepare("INSERT INTO $db_reset_log(date_request,user_email,member_id,reset_code,ip_address,active) VALUES (?,?,?,?,?,?)");
							$result->execute(array($todate,$cd2email,$admin2ID,$reset_code,$ip_address,$no));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
							
						$message =  "<html><head></head><body><h3>Welcome to Maxmoni!</h3><p>Thank you for signing up.<br></p><p>A Maxmoni account has been set up for you. If you are not the right owner of this email (".$cd2email."), please delete this email.  Otherwise please click on the link below to set up your password.<br>";
						$message =  $message."<br>Link: <a href='".$mx008cp."&sg=new&cid=".$reset_code."'>Click here to set password</a><br>";
						$message =  $message."<br>Thank you for using Maxmoni!<br><br> --The Maxmoni Team<br><a href='http://www.maxmoni.com'>http://www.maxmoni.com</a><br>Easy group budget tracking!<br><br><br></body></html>";
						$to = $cd2email;
						$subject = "Password setup in Maxmoni.com";
						$from = "emailconfirm@maxmoni.com";
		
						$headers  = "From: Maxmoni<".$from.">\r\n";
						$headers .= "Content-type: text/html\r\n";
						mail($to,$subject,$message,$headers);
						
						$chk_login = "Set up password";
					}//end admin2
				}//end else admin2
				try{//insert group rights for admin2
					$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,guest,active) VALUES (?,?,?,?,?,?)");
					$result->execute(array($groupID,$admin2ID,3,3,$no,$yes));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; </script>";
				}
				print "<script> self.location='".$mx005ot."&sid=".$licenseID."&oid=".$organID."'; </script>";
			}//end of new process
			
			//update Org
			if ($_POST['chgstate'] == "edit"){
				if ($_POST['organization'] != "" and $_POST['cemail'] != ""){
					$organizationID = $_POST['organID'];
					$organization = $_POST['organization'];
					//$organization = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $organization);
					$organization = str_replace('"', "", $organization); //remove " from note
					$cstreet = $_POST['cstreet'];
					//$cstreet = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstreet);
					$cstreet = str_replace('"', "", $cstreet); //remove " from note
					$ccity = $_POST['ccity'];
					//$ccity = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $ccity);
					$ccity = str_replace('"', "", $ccity); //remove " from note
					$cstate = $_POST['cstate'];
					$cstate = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstate);
					$cstate = str_replace('"', "", $cstate); //remove " from note
					$czip = $_POST['czip'];
					$cemail = $_POST['cemail'];
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					
					try{//update Org
						$result = $db->prepare("UPDATE $db_organization SET organization=?,street=?,city=?,state=?,zip=?,email=?,website=? WHERE organization_id=?");
						$result->execute(array($organization,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$organizationID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
	
					//if upload image
					if ($_FILES['cmx_self']['name']!=""){
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/o_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/o_pix/"; //all pics save in the main root
						$target_path = $target_path.$organizationID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $organizationID.".". end(explode(".", $_FILES['cmx_self']['name']));
					
						try{//get pix name
							$result = $db->prepare("SELECT pix_name FROM $db_organization_pix WHERE organization_id=?");
							$result->execute(array($organizationID)); 
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$cur_pix = $row['pix_name'];
						}
						
						if (file_exists($target_path_on.$cur_pix)){
							unlink($target_path_on.$cur_pix); //delete old file
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)) {  
							try{//update Org pix
								$result = $db->prepare("UPDATE $db_organization_pix SET pix_name=? WHERE organization_id=?");
								$result->execute(array($pix_name,$organizationID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						} 						
					}
				}
				print "<script> self.location='".$mx005ot."&sid=".$licenseID."&oid=".$organizationID."'; </script>";
			}//end of edit consortium
		}//end of form post
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>