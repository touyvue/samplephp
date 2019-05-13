<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$accountInfo = new accountInfoC();
		$budgetTotInst = new budgetInfoC();
		$memberTotInst = new memberInfoC();
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		
		$myAccountOverview = "Accounts as of ".date("m/d/Y");
		$shareAccountOverview = "Shared Accounts as of ".date("m/d/Y");
		$yes = "yes";
		$no = "no";
		
		try{ //get member's consortiumID and licenseID			
			$result = $db->prepare("SELECT $db_member.consortium_id,$db_consortium.consortium,$db_consortium.license_id,$db_consortium.admin1,$db_consortium.admin2 FROM $db_member,$db_consortium WHERE $db_member.member_id=? AND $db_member.consortium_id=$db_consortium.consortium_id AND $db_member.active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$consortiumID = $row['consortium_id'];
			$licenseID =  $row['license_id'];
		}
		
		//Get mybudget list
		$accountName = array();
		$myAccountID = array();		
		$myAccountList = "";
		$totMyAccountCount = 0;
		$todayDate = date("Y-m-d"); //date("m/d/Y");
		$processDate = date($currentYr."-".$budgetMonth."-d");
		$totForecastBal = 0;
		$totActualBal = 0;
		
		try{					
			$result = $db->prepare("SELECT account_id, name FROM $db_account WHERE member_id=? AND active=? ORDER BY list_order ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		//$processDate = date($currentYr."-".$budgetMonth."-d");
		$totMyAccountCount = $result->rowCount();
		$ctArray = 0;
		if ($totMyAccountCount > 0){
			foreach ($result as $row) {
				$accountName[$ctArray] = str_replace('\\','',$row['name']);
				$myAccountID[$ctArray] = $row['account_id'];
				$ctArray++;
			}
			$totAccountItems = array();
			for ($i = 0; $i < $totMyAccountCount; $i++){
				$totAccountItems = $accountInfo->eachAccountTotF($myAccountID[$i],$memberID,$todayDate);//$budgetMonth,$currentYr);
				$myAccountList = $myAccountList."<tr><td><a href='".$mx003ac."&aid=".$myAccountID[$i]."&smid='>".$accountName[$i]."</a></td><td>".$budgetTotInst->convertDollarF($totAccountItems[0])."</td><td>".$budgetTotInst->convertDollarF($totAccountItems[1])."</td><td><a href='".$mx003av."&aid=".$myAccountID[$i]."&smid='>View</a></td></tr>";
				$totForecastBal = $totForecastBal + $totAccountItems[0];
				$totActualBal = $totActualBal + $totAccountItems[1];
			}//end for loop
			$myAccountList = $myAccountList."<tr><th>Total</th><th>".$budgetTotInst->convertDollarF($totForecastBal)."</th><th>".$budgetTotInst->convertDollarF($totActualBal)."</th></tr>";
		} else {//end "if ($totMyAccountCount > 0)"		
			$myAccountList = "<tr><td>No accounts available</td><td colspan=2>Create <a href=".$mx005as.">New Account</a></td></tr>";
		}//end mybudget list
		
		//get shared budget list
		$shareAccountList = "";
		$idFound = "no";
		$validShareAcctTot = 0;
		$totShareForecastBal = 0;
		$totShareActualBal = 0;
		$totShareAccountItems = array();
		$accountNameMemberID = array();
		try{					
			$result = $db->prepare("SELECT account_id, access_rights FROM $db_account_rights WHERE member_id=? AND active=?");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$totShareAccountCount = $result->rowCount();
		if ($totShareAccountCount > 0){
			foreach ($result as $row){
				$idFound = "no";
				for ($i = 0; $i < $totMyAccountCount; $i++){
					if ($row['account_id'] == $myAccountID[$i]){
						$idFound = "yes";
						$i = $totMyAccountCount;
					}
				}
				if ($idFound == "no"){
					$accountNameMemberID = $accountInfo->accountNameF($row['account_id']);
					$totShareAccountItems = $accountInfo->eachAccountTotF($row['account_id'],$accountNameMemberID[1],$todayDate);//$budgetMonth,$currentYr);
					$shareAccountList = $shareAccountList."<tr><td><center>".$memberTotInst->memberNameF($accountNameMemberID[1])."</center></td><td><a href='".$mx003ac."&aid=".$row['account_id']."&smid=".$accountNameMemberID[1]."'>".$accountNameMemberID[0]."</a></td><td>".$budgetTotInst->convertDollarF($totShareAccountItems[0])."</td><td>".$budgetTotInst->convertDollarF($totShareAccountItems[1])."</td></tr>";
					$totShareForecastBal = $totShareForecastBal + $totShareAccountItems[0];
					$totShareActualBal = $totShareActualBal + $totShareAccountItems[1];
					$validShareAcctTot++;
				} 
			}//end for loop
			$shareAccountList = $shareAccountList."<tr><th></th><th>Total</th><th>".$budgetTotInst->convertDollarF($totShareForecastBal)."</th><th>".$budgetTotInst->convertDollarF($totShareActualBal)."</th></tr>";
		} 		
		if ($validShareAcctTot <= 0){
			$shareAccountList = "<tr><td colspan=4>No shared accounts available</td></tr>";
		}//end shared budget list	
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>