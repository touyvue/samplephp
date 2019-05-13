<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();		
		$groupSetting = "Consortium Setting";		
		$yes = "yes";
		$no = "no";
		$spVal = "";
		$todayDate = date('Y-m-d');
		
		if ($memberID == "100100100"){//list all consortium for Master Admin
			$consortiumList = "";
			$consortTit = "Consortium Setup";
			try{//create account list				
				$result = $db->prepare("SELECT $db_consortium.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_license_type.package FROM $db_consortium, $db_license_type WHERE $db_consortium.license_id = $db_license_type.license_type_id AND $db_consortium.active=? ORDER BY $db_consortium.license_id");
				$result->execute(array($yes));
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			$itemCount = $result->rowCount();
			if ($itemCount > 0){
				foreach ($result as $row){
					$consortiumList = $consortiumList."<tr><td><a href='".$mx005ct."&cid=".$row['consortium_id']."'>".str_replace('\\','',$row['consortium'])."</a></td><td>".$row['package']."</td></tr>";
				}
			}
		}else{
			$consortTit = "Update Consortium"; //consortium level update only - shouldn't able to create consortium, only Master Admin
		}
				
		//gert consortiumID - determine licenseType and consortium level admin
		$consortSetup = "";
		try{				
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			$consortSetup = "";
			foreach ($result as $row){
				$consortiumID = $row['consortium_id'];
				$licenseID =  $row['license_id'];
				if ($licenseID == "1003" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
					$consAdmin = "yes"; //yes, consort
				}
			}
		}
				
		$statechg = "none";
		$consortButton = "Consortium";
		if ($_GET['cid'] != "" and $_GET['cid'] != "new"){
			$consortID = $_GET['cid'];
			try{//get pix name
				$result = $db->prepare("SELECT consortium,street,city,state,zip,email,website,license_id,admin1,admin2 FROM $db_consortium WHERE consortium_id=?");
				$result->execute(array($consortID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$consortium = str_replace('\\','',$row['consortium']);
				$street = str_replace('\\','',$row['street']);
				$city = str_replace('\\','',$row['city']);
				$state = str_replace('\\','',$row['state']);
				$zip = $row['zip'];
				$email = $row['email'];
				$website = str_replace('\\','',$row['website']);
				$license_type = $row['license_id'];
				$admin1 = $row['admin1'];
				$admin2 = $row['admin2'];
				if ($row['license_id'] == "1003" and ($memberID == $row['admin1'] or $memberID == $row['admin2'])){
					$consAdmin = "yes"; //yes, consort
				}
			}
			$consortPix = "";
			try{//get pix name
				$result = $db->prepare("SELECT pix_name FROM $db_consortium_pix WHERE consortium_id=?");
				$result->execute(array($consortID)); 
			} catch(PDOException $e) {
				print "<script> self.location='".$index_url."?err=d1000'; </script>";
			}
			foreach ($result as $row){
				$consortPix = "<a href='images/c_pix/".$row['pix_name']."' class='prettyPhoto'><img src='images/c_pix/".$row['pix_name']."'></a>";
			}			
			$statechg = "edit";
			$consortButton = "Update Consortium";
			
			//get member status
			$allActMem = "";
			if ($consAdmin == "yes"){
				try{
					$result = $db->prepare("SELECT member_id,first_name,last_name,active FROM $db_member WHERE consortium_id=?");
					$result->execute(array($consortID));
				} catch(PDOException $e) {
					print "<script> self.location='".$index_url."?err=d1000'; script>";
				}
				$itemCount = $result->rowCount();
				if ($itemCount > 0){
					foreach ($result as $row) {
						if ($license_type == "1003"){
							if($row['member_id'] == $admin1 or $row['member_id'] == $admin2){
								if($row['member_id'] == $memberID){ //am I an admin?
									$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" checked="checked" value="yes" onchange="chkAdmin('.$row['member_id'].','.$consortID.',3,0)"></td>';
								}else{
									$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" checked="checked" value="yes" onchange="chkAdmin('.$row['member_id'].','.$consortID.',3,1)"></td>';
								}
							}else{
								$allActMem = $allActMem.'<tr><td><input type="checkbox" id="a'.$row['member_id'].'" name="a'.$row['member_id'].'" value="yes" onclick="chkAdmin('.$row['member_id'].','.$consortID.',3,1)"></td>';
							}
							$allActMem = $allActMem.'<td>'.str_replace('\\','',$row['first_name']).' '.str_replace('\\','',$row['last_name']).'</td>';
							if ($row['active']=="yes"){
								$allActMem = $allActMem.'<td><input type="radio" id="sy'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="yes" onchange="uptMemSta('.$row['member_id'].','.$consortID.',3)" /> Yes</td><td><input type="radio" id="sn'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="no" onchange="uptMemSta('.$row['member_id'].','.$consortID.',3)" /> No</td><td></td></tr>';
							}else{
								$allActMem = $allActMem.'<td><input type="radio" id="sy'.$row['member_id'].'" name="ms'.$row['member_id'].'" value="yes" onchange="uptMemSta('.$row['member_id'].','.$consortID.',3)" /> Yes</td><td><input type="radio" id="sn'.$row['member_id'].'" name="ms'.$row['member_id'].'" checked="checked" value="no" onchange="uptMemSta('.$row['member_id'].','.$consortID.',3)" /> No</td><td></td></tr>';
							}
						}
					}
				}
			}
			
		}
		if ($_GET['cid'] == "new"){
			$statechg = "new";
			$consortID = "";
			$consortButton = "Create Consortium";
		} 
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//new consortium
			if ($_POST['state'] == "new"){
				//begin consortium
				if ($_POST['consortium'] != "" and $_POST['cemail'] != ""){
					$consortium = $_POST['consortium'];
					$consortium = str_replace('"', "", $consortium); //remove " from note
					$cstreet = $_POST['cstreet'];
					$cstreet = str_replace('"', "", $cstreet); //remove " from note
					$ccity = $_POST['ccity'];
					$ccity = str_replace('"', "", $ccity); //remove " from note
					$cstate = $_POST['cstate'];
					$cstate = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstate);
					$cstate = str_replace('"', "", $cstate); //remove " from note
					$czip = $_POST['czip'];
					$cemail = $_POST['cemail'];
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					$clicense = $_POST['clicense'];
					
					$genNum = date(h).date(i).date(s).rand(100,999);
					try{//insert new consortium
						$result = $db->prepare("INSERT INTO $db_consortium (consortium,street,city,state,zip,email,website,license_id,admin1,active) VALUES (?,?,?,?,?,?,?,?,?,?)");
						$result->execute(array($consortium,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$clicense,$genNum,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//select new consortiumID
						$result = $db->prepare("SELECT consortium_id FROM $db_consortium WHERE admin1=? ORDER BY consortium_id ASC");
						$result->execute(array($genNum)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$consortiumID = $row['consortium_id'];
					}
					
					try{//insert new organization
						$result = $db->prepare("INSERT INTO $db_organization (organization,street,city,state,zip,email,website,consortium_id,active) VALUES (?,?,?,?,?,?,?,?,?)");
						$result->execute(array($consortium,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$consortiumID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get organID
						$result = $db->prepare("SELECT organization_id FROM $db_organization WHERE consortium_id=? ORDER BY organization_id ASC");
						$result->execute(array($consortiumID)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$organID = $row['organization_id'];
					}
					try{//insert fiscal month setting - add 9/10
						$result = $db->prepare("INSERT INTO $db_setting (organization_id,begin_fiscal_mon,end_fiscal_mon,num_mon_show) VALUES (?,?,?,?)");
						$result->execute(array($organID,01,12,12));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//create one group
						$result = $db->prepare("INSERT INTO $db_group (group_name,website,organization_id,track,active) VALUES (?,?,?,?,?)");
						$result->execute(array($consortium,$cwebsite,$organID,"no",$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get groupID
						$result = $db->prepare("SELECT group_id FROM $db_group WHERE organization_id=? ORDER BY group_id ASC");
						$result->execute(array($organID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$groupID = $row['group_id'];
					}
					try{//update admin1 to empty
						$result = $db->prepare("UPDATE $db_consortium SET admin1=? WHERE consortium_id=?");
						$result->execute(array($spVal,$consortiumID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					//if upload image
					if ($_FILES['cmx_self']['name']!=""){
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/c_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/c_pix/"; //all pics save in the main root
						$target_path = $target_path.$consortiumID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $consortiumID.".". end(explode(".", $_FILES['cmx_self']['name']));
						try{//insert new pix
							$result = $db->prepare("INSERT INTO $db_consortium_pix (pix_name,consortium_id,active) VALUES (?,?,?)");
							$result->execute(array($pix_name,$consortiumID,$yes));
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						
						if (file_exists($target_path_on.$pix_name)){
							unlink($target_path_on.$pix_name); //delete file
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)) {  
							try{//update pix name
								$result = $db->prepare("UPDATE  $db_consortium_pix SET pix_name=? WHERE consortium_id=?");
								$result->execute(array($pix_name,$consortiumID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						} 						
					}
				}//end of consortium
				
				//add admin1
				if ($_POST['cd1email'] != ""){
					$cd1firstname = $_POST['cd1firstname'];
					$cd1firstname = str_replace('"', "", $cd1firstname); //remove " from note
					$cd1lastname = $_POST['cd1lastname'];
					$cd1lastname = str_replace('"', "", $cd1lastname); //remove " from note
					$cd1email = $_POST['cd1email'];
					$cd1password = "Loveland1"; 
					
					$genNum = date(h).date(i).date(s).rand(100,999);
					try{//insert admin1
						$result = $db->prepare("INSERT INTO $db_member (consortium_id,first_name,last_name,email,date_active,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($genNum,$cd1firstname,$cd1lastname,$cd1email,$todayDate,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get admin1ID
						$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=? ORDER BY member_id ASC");
						$result->execute(array($genNum)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$admin1ID = $row['member_id'];
					}
					try{//update member
						$result = $db->prepare("UPDATE $db_member SET consortium_id=? WHERE member_id=?");
						$result->execute(array($consortiumID,$admin1ID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					try{//update consortium
						$result = $db->prepare("UPDATE $db_consortium SET admin1=? WHERE consortium_id=?");
						$result->execute(array($admin1ID,$consortiumID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//update organization
						$result = $db->prepare("UPDATE $db_organization SET admin1=? WHERE organization_id=?");
						$result->execute(array($admin1ID,$organID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					try{//insert new member group rights
						$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,guest,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($groupID,$admin1ID,3,3,$no,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
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
					$acctName1 = "Spending Checking";
					$acctDesc1 = "General expenses";
					try{//update project details
						$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
						$result->execute(array($acctName1,1,$acctDesc1,$admin1ID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get new accountID
						$result = $db->prepare("SELECT account_id FROM $db_account WHERE member_id=? ORDER BY account_id ASC");
						$result->execute(array($admin1ID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$accountID1 = $row['account_id'];
					}
					try{//update project details
						$result = $db->prepare("INSERT INTO $db_account_rights (member_id,access_rights,account_id,active) VALUES (?,?,?,?)");
						$result->execute(array($admin1ID,3,$accountID1,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					//1 budget/////////////////////////////////////////
					$budName1 = "Monthly Budget";
					$budDesc1 = "General budget";
					try{
						$result = $db->prepare("INSERT INTO $db_budget (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
						$result->execute(array($budName1,1,$budDesc1,$admin1ID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get new budgetID
						$result = $db->prepare("SELECT budget_id FROM $db_budget WHERE member_id=? ORDER BY budget_id ASC");
						$result->execute(array($admin1ID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$budgetID1 = $row['budget_id'];
					}
					try{//update budget details
						$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
						$result->execute(array($admin1ID,3,$budgetID1,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$catNameArr = array("Electric & heat","Water","Cell phone","Food","Dinning","Misc expenses","Income","Emergency saving","Credit card");
					$catNameArrCt = count($catNameArr);
					for($i=0; $i<$catNameArrCt; $i++){
						try{
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
					
					try{
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
				
				//add admin2
				if ($_POST['cd2email'] != ""){
					$cd2firstname = $_POST['cd2firstname'];
					$cd2firstname = str_replace('"', "", $cd2firstname); //remove " from note
					$cd2lastname = $_POST['cd2lastname'];
					$cd2lastname = str_replace('"', "", $cd2lastname); //remove " from note
					$cd2email = $_POST['cd2email'];
					$cd2password = "Loveland1"; 

					$genNum = date(h).date(i).date(s).rand(100,999);
					try{//insert admin2
						$result = $db->prepare("INSERT INTO $db_member (consortium_id,first_name,last_name,email,date_active,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($genNum,$cd2firstname,$cd2lastname,$cd2email,$todayDate,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//get admin2ID
						$result = $db->prepare("SELECT member_id FROM $db_member WHERE consortium_id=? ORDER BY member_id ASC");
						$result->execute(array($genNum)); 
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$admin2ID = $row['member_id'];
					}
					try{//update member
						$result = $db->prepare("UPDATE $db_member SET consortium_id=? WHERE member_id=?");
						$result->execute(array($consortiumID,$admin2ID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					try{//update consortium
						$result = $db->prepare("UPDATE $db_consortium SET admin2=? WHERE consortium_id=?");
						$result->execute(array($admin2ID,$consortiumID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					try{//update organization
						$result = $db->prepare("UPDATE $db_organization SET admin2=? WHERE organization_id=?");
						$result->execute(array($admin2ID,$organID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					try{//insert new member group rights
						$result = $db->prepare("INSERT INTO $db_group_rights (group_id,member_id,org_rights,group_rights,guest,active) VALUES (?,?,?,?,?,?)");
						$result->execute(array($groupID,$admin2ID,3,3,$no,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					
					$hash_cost_log2 = 8;
					$hash_portable = FALSE;
					$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
					$hash = $hasher->HashPassword($cd1password);
					try{//update profile
						$result = $db->prepare("UPDATE $db_member SET password=? WHERE member_id=?");
						$result->execute(array($hash,$admin2ID));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					unset($hasher);
					
					//insert 1 account/////////////////////////////////////////
					$acctName2 = "Spending Checking";
					$acctDesc2 = "General expenses";
					try{//insert new account
						$result = $db->prepare("INSERT INTO $db_account (name,list_order,description,member_id,active) VALUES (?,?,?,?,?)");
						$result->execute(array($acctName2,1,$acctDesc2,$admin2ID,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
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
					try{//update project details
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
						$result->execute(array($admin2ID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$budgetID2 = $row['budget_id'];
					}
					try{//update budget details
						$result = $db->prepare("INSERT INTO $db_budget_rights (member_id,access_rights,budget_id,active) VALUES (?,?,?,?)");
						$result->execute(array($admin2ID,3,$budgetID2,$yes));
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					$catNameArr = array("Electric & heat","Water","Cell phone","Food","Dinning","Misc expenses","Income","Emergency saving","Credit card");
					$catNameArrCt = count($catNameArr);
					for($i=0;$i<$catNameArrCt;$i++){
						try{
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
					
					try{
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
			}//end of new process
			
			//edit consortium
			if ($_POST['state'] == "edit"){
				if ($_POST['consortium'] != "" and $_POST['cemail'] != ""){
					$consortiumID = $_POST['conID'];
					$consortium = $_POST['consortium'];
					$consortium = str_replace('"', "", $consortium); //remove " from note
					$cstreet = $_POST['cstreet'];
					$cstreet = str_replace('"', "", $cstreet); //remove " from note
					$ccity = $_POST['ccity'];
					$ccity = str_replace('"', "", $ccity); //remove " from note
					$cstate = $_POST['cstate'];
					$cstate = preg_replace("/[^A-Za-z0-9\-()<>= '\/]/", "", $cstate);
					$cstate = str_replace('"', "", $cstate); //remove " from note
					$czip = $_POST['czip'];
					$cemail = $_POST['cemail'];
					$cwebsite = $_POST['cwebsite'];
					$cwebsite = str_replace('"', "", $cwebsite); //remove " from note
					$clicense = $_POST['clicense'];
					
					try{//update profile
						if ($memberID == "100100100"){
							$result = $db->prepare("UPDATE $db_consortium SET consortium=?,street=?,city=?,state=?,zip=?,email=?,website=?,license_id=? WHERE consortium_id=?");
							$result->execute(array($consortium,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$clicense,$consortiumID));
						}else{
							$result = $db->prepare("UPDATE $db_consortium SET consortium=?,street=?,city=?,state=?,zip=?,email=?,website=? WHERE consortium_id=?");
							$result->execute(array($consortium,$cstreet,$ccity,$cstate,$czip,$cemail,$cwebsite,$consortiumID));
						}
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
	
					//if upload image
					if ($_FILES['cmx_self']['name']!=""){
						//rename and upload file
						$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/c_pix/"; //all pics save in the main root
						$target_path = $_SERVER['DOCUMENT_ROOT']."/images/c_pix/"; //all pics save in the main root
						$target_path = $target_path.$consortiumID.".". end(explode(".", $_FILES['cmx_self']['name']));
						$pix_name = $consortiumID.".". end(explode(".", $_FILES['cmx_self']['name']));
					
						try{//get pix name
							$result = $db->prepare("SELECT pix_name FROM $db_consortium_pix WHERE consortium_id=?");
							$result->execute(array($consortiumID)); 
						} catch(PDOException $e) {
							print "<script> self.location='".$index_url."?err=d1000'; </script>";
						}
						foreach ($result as $row){
							$cur_pix = $row['pix_name'];
						}
						
						if (file_exists($target_path_on.$cur_pix)){
							unlink($target_path_on.$cur_pix); //delete old files
						}
						if(move_uploaded_file($_FILES['cmx_self']['tmp_name'], $target_path)) {  
							try{//update profile
								$result = $db->prepare("UPDATE  $db_consortium_pix SET pix_name=? WHERE consortium_id=?");
								$result->execute(array($pix_name,$consortiumID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						} 						
					}
				}
			}//end of edit consortium
			print "<script> self.location='".$mx005gs."'; </script>";
		}//end of form post
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>