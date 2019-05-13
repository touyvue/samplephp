<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$memberID = $_SESSION['mid'];
		$projectInfo = new projectInfoC();
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
		$yes = "yes";
		
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
		
/////////////////////////////////////////////////////////////////		
		//get budget forecasting list
		$forecastList = "";	
		$forecastBudgetList = "";
		try{					
			$result = $db->prepare("SELECT memberbudget_id,name,start,end,note,active FROM $db_memberbudget WHERE consortium_id=? AND member_id=? ORDER BY active DESC,memberbudget_id DESC");
			$result->execute(array($consortiumID,$memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$forecastAmt = 0;
		$actualAmt = 0;
		if ($itemCount > 0){
			foreach ($result as $row) {
				$forecastAmt = $memberTotInst->getMemberbudgetTotF($row['memberbudget_id']);
				$actualAmt = $memberTotInst->getMembertrackActAmtF($row['memberbudget_id'],$memberID);
				$name = str_replace('\\','',$row['name']);
				$startDate = $row['start']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
				$partsArr = explode("-",$startDate);
				$startDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
				$endDate = $row['end']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
				$partsArr = explode("-",$endDate);
				$endDate = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
				$note = str_replace('\\','',$row['note']);
				
				$forecastList = $forecastList."<tr><td><a href='".$mx007fb."&mbid=".$row['memberbudget_id']."'>".$name."</a></td><td>".$budgetTotInst->convertDollarF($forecastAmt)."</td><td>".$budgetTotInst->convertDollarF($actualAmt)."</td><td>".$startDate."</td><td>".$endDate."</td><td>".$row['active']."</td>";
				$forecastList = $forecastList."<td nowrap><a href='#' onclick='uptForecastInfo(".$row['memberbudget_id'].",1)'><span class='label label-warning'><i class='fa fa-pencil'></i></span></a> ";
				$forecastList = $forecastList."<a href='#' onclick='uptForecastInfo(".$row['memberbudget_id'].",2)'><span class='label label-danger'><i class='fa fa-times'></i></span></a></td></tr>";	
				
				if ($row['active'] == "yes"){
					$forecastBudgetList = $forecastBudgetList."<option value='".$row['memberbudget_id']."'>".$name."</option>";
				}
			}
		}
		//get budget tracking list
		$trackList = "";	
		try{					
			$result = $db->prepare("SELECT membertrack_id,name,date,note FROM $db_membertrack WHERE consortium_id=? AND member_id=? ORDER BY membertrack_id DESC");
			$result->execute(array($consortiumID,$memberID));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {
				$name = str_replace('\\','',$row['name']);
				$date = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy mx002gb
				$partsArr = explode("-",$date);
				$date = $partsArr[1]."-".$partsArr[2]."-".$partsArr[0];
				$note = str_replace('\\','',$row['note']);
				$amountTot = $memberTotInst->getMembertrackTotF($row['membertrack_id']);
				$trackList = $trackList."<tr><td><a href='".$mx007tb."&mtid=".$row['membertrack_id']."'>".$name."</a></td><td>".$budgetTotInst->convertDollarF($amountTot)."</td><td>".$date."</td>";//<td>".$note."</td>";
				$trackList = $trackList."<td nowrap><a href='#' onclick='uptTrackInfo(".$row['membertrack_id'].",1)'><span class='label label-warning'><i class='fa fa-pencil'></i></span></a> ";
				$trackList = $trackList."<a href='#' onclick='uptTrackInfo(".$row['membertrack_id'].",2)'><span class='label label-danger'><i class='fa fa-times'></i></span></a></td></tr>";	
			}
		}
/////////////////////////////////////////////////////////////////
		$accountList = "";
		$accountCount = 0;
		try{//build account dropdown	
			$result = $db->prepare("SELECT account_id,name FROM $db_account WHERE member_id=? AND active=? ORDER BY name");
			$result->execute(array($memberID,$yes)); //only include self-accounts, not shared accounts
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		foreach ($result as $row){
			$accountList = $accountList."<option value='".$row['account_id']."'>".str_replace('\\','',$row['name'])."</option>";
			$accountCount++;
		}
		
		//get all members
		$memberList = "";
		$memberListCt = 0;	
		try{					
			$result = $db->prepare("SELECT member_id,first_name,last_name FROM $db_member WHERE consortium_id=? AND active=? ORDER BY first_name ASC");
			$result->execute(array($consortiumID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		if ($itemCount > 0){
			foreach ($result as $row) {				
				$memberName = str_replace('\\','',$row['first_name'])." ".str_replace('\\','',$row['last_name']);
				//$memberList = $memberList."<tr><td>".$memberName."</td><td><a href='#' onclick='enterfamily(".$row['member_id'].")'>view</a></td><td></td><td></td><td></td><td></td></tr>";		
				$memberList = $memberList."<option value='".$row['member_id']."'>".$memberName."</option>";
				$memberListCt++;
			}
		}
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>