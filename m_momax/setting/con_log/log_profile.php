<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();		
		$profileSetting = "Profile Setting";		
		$yes = "yes";
		
		$firstName = "";
		$lastName = "";
		$email = "";
		$password = "";
		try{//create account list				
			$result = $db->prepare("SELECT first_name,last_name,email, password FROM $db_member WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row){
				$firstName = str_replace('\\','',$row['first_name']);
				$lastName = str_replace('\\','',$row['last_name']);
				$email = $row['email'];
				$password = "**********";
			}
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$fName = $_POST['firstname'];
			$fName = str_replace('"', "", $fName); //remove "
			$lName = $_POST['lastname'];
			$lName = str_replace('"', "", $lName); //remove "
			$email = $_POST['memail'];
			$password = $_POST['password'];
			
			if($email != "" and $password != ""){
				//if upload image
				if ($_FILES['mx_self']['name']!=""){
					//rename and upload file
					$target_path_on = $_SERVER['DOCUMENT_ROOT']."/images/m_pix/"; //all pics save in the main root
					$target_path = $_SERVER['DOCUMENT_ROOT']."/images/m_pix/"; //all pics save in the main root
					$target_path = $target_path.$memberID.".". end(explode(".", $_FILES['mx_self']['name']));
					$pix_name = $memberID.".". end(explode(".", $_FILES['mx_self']['name']));
					try{//get pix name
						$result = $db->prepare("SELECT pix_name FROM $db_member_pix WHERE member_id=?");
						$result->execute(array($memberID)); //get recurring info
					} catch(PDOException $e) {
						print "<script> self.location='".$index_url."?err=d1000'; </script>";
					}
					foreach ($result as $row){
						$cur_pix = $row['pix_name'];
					}
					
					if (file_exists($target_path_on.$cur_pix)){
						unlink($target_path_on.$cur_pix); //rename($target_path,$target_path_old);
					}
					if(move_uploaded_file($_FILES['mx_self']['tmp_name'], $target_path)) {  //chmod($target_path, 777); 
						if ($cur_pix == ""){
							try{//if first time, insert pix 
								$result = $db->prepare("INSERT INTO $db_member_pix (pix_name,member_id,active) VALUES (?,?,?)");
								$result->execute(array($pix_name,$memberID,$yes));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						}else{						
							try{//update profile
								$result = $db->prepare("UPDATE $db_member_pix SET pix_name=? WHERE member_id=?");
								$result->execute(array($pix_name,$memberID));
							} catch(PDOException $e) {
								print "<script> self.location='".$index_url."?err=d1000'; </script>";
							}
						}
					} 
				}
				if ($password == "**********"){
					try{//update profile
						$result = $db->prepare("UPDATE $db_member SET first_name=?,last_name=?,email=? WHERE member_id=?");
						$result->execute(array($fName,$lName,$email,$memberID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
				}else{
					$hash_cost_log2 = 8;
					$hash_portable = FALSE;
					$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
					$hash = $hasher->HashPassword($password);
					try{//update profile
						$result = $db->prepare("UPDATE $db_member SET first_name=?,last_name=?,email=?,password=? WHERE member_id=?");
						$result->execute(array($fName,$lName,$email,$hash,$memberID));
					} catch(PDOException $e) {
						echo "message001 - Sorry, system is experincing problem. Please check back.";
					}
					unset($hasher);
				}
			}
					
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>