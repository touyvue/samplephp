<?php
	if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){
		$projectInfo = new projectInfoC();
		$budgetTotInst = new budgetInfoC();
		$accountInfo = new accountInfoC();
		$memberTotInst = new memberInfoC();
		$generalInfo = new generalInfoC();
		
		if ($_GET['cy']==""){
			$currentYr = date(Y);
			$currentMon = date(F);	
			$budgetMonth = date(m);
		}else{
			$currentYr = $_GET['cy'];
			$budgetMonth = date(m);
		}
		
		$todayDate = strtotime(date('Y-m-d'));
		$firstDay = date('m-01-Y', $todayDate);
		$lastDay  = date('m-t-Y', $todayDate); 
		
		$day = date('w');
		$startWeek = date('Y-m-d', strtotime('-'.$day.' days'));
		$endWeek = date('Y-m-d', strtotime('+'.(6-$day).' days'));
		$startMonth = date('Y-m-01', $todayDate);
		$endMOnth = date('Y-m-t', $todayDate);
		$startYear = date('Y-01-01', $todayDate);
		$endYear = date('Y-12-31', $todayDate);
		$thisYear = date('Y',$todayDate);
		
		$currTrkcategoryId = $_GET['tcid'];
		//$currTrkcategoryTy = $_GET['tcty'];
		$viewGroupID = $_GET['trkg'];
		//$detNum = substr($_GET['det'],0,4);
		if ($_GET['det'] != ""){
			$staDateQ = substr($_GET['det'],0,8);
			$grpfilterQ = substr($_GET['det'],8,1);
			$endDateQ = substr($_GET['det'],9,8);
		
		}		
		
		if ($staDateQ == ""){ //start with this
			$defaultStart = date('01-01-Y', $todayDate);
			$staDateQ = date('0101Y', $todayDate);
			$defaultStartQ = $startYear;
		}else{
			$staDateTempQ = substr($staDateQ,0,2)."-".substr($staDateQ,2,2)."-".substr($staDateQ,4,4);
			$defaultStartQ = substr($staDateQ,4,4)."-".substr($staDateQ,0,2)."-".substr($staDateQ,2,2);
			$defaultStart = $staDateTempQ;
		}
		if ($endDateQ == ""){
			$defaultEnd = date('12-31-Y', $todayDate);
			$endDateQ = date('1231Y', $todayDate);
			$defaultEndQ = $endYear;
		}else{
			$endDateTempQ = substr($endDateQ,0,2)."-".substr($endDateQ,2,2)."-".substr($endDateQ,4,4);
			$defaultEndQ = substr($endDateQ,4,4)."-".substr($endDateQ,0,2)."-".substr($endDateQ,2,2);
			$defaultEnd = $endDateTempQ;
		}
				
		$myGenReports = "Group Member Report";
		$yes = "yes";
		$showReport = "no";
		$speGroupName = $generalInfo->returnGroupNameF($viewGroupID);
		
		//get trkcategory list
		$trkcatItemsList = "";//$generalInfo->getTrkcategoryDropdownListF($memberID);
		try{//get the first trkcategory on the list based on ASC sort order
			$result = $db->prepare("SELECT trkcategory_id,category,type FROM $db_trkcategory WHERE member_id=? AND active=? ORDER BY category ASC");
			$result->execute(array($memberID,$yes));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$trkCatArr = array();
		$trkCatArrCt = 0;
		$firstTrkCatId = "";
		$firstTrkCatTyp = "";
		$firstTrkCatName = "";
		$currTrkcategoryName = "";
		foreach ($result as $row) {
			if ($trkCatArrCt == 0){
				$firstTrkCatId = "";//$row['trkcategory_id'];
				$firstTrkCatTyp = "";//$row['type'];
				$firstTrkCatName = "";//str_replace('\\','',$row['category']);
			}
			if ($currTrkcategoryId == $row['trkcategory_id']){//after filtered dates are submited, get type
				$currTrkcategoryTy = $row['type'];
				$trkcatItemsList = $trkcatItemsList."<option value='".$row['trkcategory_id']."' selected='selected'>".$row['category']."</option>";
				$currTrkcategoryName = str_replace('\\','',$row['category']);
			}else{
				$trkcatItemsList = $trkcatItemsList."<option value='".$row['trkcategory_id']."'>".str_replace('\\','',$row['category'])."</option>";
			}
			$trkCatArr[$trkCatArrCt] = $row['trkcategory_id'];
			$trkCatArrCt++;
		}
		
		If ($currTrkcategoryId != ""){
			$firstTrkCatId = $currTrkcategoryId;
			$firstTrkCatTyp = $currTrkcategoryTy;
			$firstTrkCatName = $currTrkcategoryName;
		}

		//get admin group list
		$trkGroupArr = array();
		$trkGroupArr[0] = 0;
		$trkGroupArrCt = 1;
		try{//get all groups with admin rights
			$result = $db->prepare("SELECT DISTINCT group_id FROM $db_group_rights WHERE member_id=? AND group_rights=?");
			$result->execute(array($memberID,3)); 
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemCount = $result->rowCount();
		$grpAdminList = "";
		if ($itemCount > 0){
			foreach ($result as $row){
				if ($generalInfo->getGrpDropdownListF($memberID,$row['group_id']) == "yes"){
					if ($viewGroupID == $row['group_id']){
						$grpAdminList = $grpAdminList."<option value='".$row['group_id']."' selected>".$generalInfo->returnGroupNameF($row['group_id'])."</option>";
					}else{
						$grpAdminList = $grpAdminList."<option value='".$row['group_id']."'>".$generalInfo->returnGroupNameF($row['group_id'])."</option>";
					}
					$trkGroupArr[$trkGroupArrCt] = $row['group_id'];
					$trkGroupArrCt++;
				}
			}
		}

/////////////////////////////////////////////////////////////////////////////////////////////////		
		$filterLink = $mx004tk;
		$dateStartEnd = $staDateQ."N".$endDateQ;			
		$grpTrackTotWeek = 0;
		$grpTrackTotMonth = 0;
		$grpTrackTotYear = 0;
		
		$grpTrackPreWeek = 0;
		$grpTrackPreMonth = 0;
		$grpTrackPreYear = 0;
		
		$memberAmtTot = 0;
		$memberPreTot = 0;
		
		try{					
			if ($viewGroupID == "" && $firstTrkCatId == ""){
				$result = $db->prepare("SELECT grpattend_id,owner_id,trkcategory_id,subcat_yn,name,value,count,date,text,note FROM $db_grpattend WHERE owner_id=? AND date>=? AND date<=? ORDER BY trkcategory_id ASC");
				$result->execute(array($memberID,$defaultStartQ,$defaultEndQ));
			}else{
				if ($viewGroupID == ""){
					$result = $db->prepare("SELECT grpattend_id,owner_id,trkcategory_id,subcat_yn,name,value,count,date,text,note FROM $db_grpattend WHERE owner_id=? AND trkcategory_id=? AND date>=? AND date<=? ORDER BY trkcategory_id ASC");
					$result->execute(array($memberID,$firstTrkCatId,$defaultStartQ,$defaultEndQ));
				}else{
					$result = $db->prepare("SELECT grpattend_id,owner_id,trkcategory_id,subcat_yn,name,value,count,date,text,note FROM $db_grpattend WHERE owner_id=? AND group_id=? AND trkcategory_id=? AND date>=? AND date<=? ORDER BY trkcategory_id ASC");
					$result->execute(array($memberID,$viewGroupID,$firstTrkCatId,$defaultStartQ,$defaultEndQ));
				}
			}
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; </script>";
		}
		$itemsFound = $result->rowCount();
		if ($itemsFound > 0){
			foreach ($result as $row){
				if ($row['owner_id'] == $memberID){//only show tracking below to $memberID,
					$orgDateStart = $row['date']; //alter yyyy-mm-dd to mm-dd-yyyy
					$partsArr = explode("-",$orgDateStart);
					$date = $partsArr[1]."/".$partsArr[2]."/".$partsArr[0];
					$catType = $generalInfo->getTrkcategoryTypeF($row['trkcategory_id']);
					if ($row['name'] == ""){
						$memberSpcTrack = $memberSpcTrack."<tr><td>General</td><td>".$generalInfo->getTrkcategoryNameF($row['trkcategory_id'])."</td>";
					}else{
						$memberSpcTrack = $memberSpcTrack."<tr><td>".str_replace('\\','',$row['name'])."</td><td>".$generalInfo->getTrkcategoryNameF($row['trkcategory_id'])."</td>";
					}
					if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "mone"){
						$memberSpcTrack = $memberSpcTrack."<td>".$budgetTotInst->convertDollarF($row['value'])."</td>";
					}
					if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "numb"){
						$memberSpcTrack = $memberSpcTrack."<td>".$row['count']."</td>";
					}
					if ($generalInfo->getTrkcategoryTypeF($row['trkcategory_id']) == "text"){
						$memberSpcTrack = $memberSpcTrack."<td>".str_replace('\\','',$row['text'])."</td>";
					}
					$memberSpcTrack = $memberSpcTrack."<td>".$date."</td><td>".str_replace('\\','',$row['note'])."</td></tr>";
					
					if ($row['subcat_yn'] == "yes"){//add in subcategories
						$subCategoryVal = $generalInfo->getSubCatF($row['grpattend_id'],$row['trkcategory_id']);	
						$memberSpcTrack = $memberSpcTrack.$subCategoryVal;
					}
					$memberAmtTot = $memberAmtTot + $row['value'];
					
					//not being used
					if ($row['count'] != "" and $row['count'] != "NULL" and $row['count'] > 0){
						$memberPreTot = $memberPreTot + $row['count'];
					}
					
					if ($row['value'] != "" and $row['value'] != "NULL" and $row['value'] > 0){
						if ($startWeek <= $row['date'] and $row['date'] <= $endWeek){
							$grpTrackTotWeek = $grpTrackTotWeek + $row['value'];
						}
						if ($startMonth <= $row['date'] and $row['date'] <= $endMOnth){
							$grpTrackTotMonth = $grpTrackTotMonth + $row['value'];
						}
						if ($startYear <= $row['date'] and $row['date'] <= $endYear){
							$grpTrackTotYear = $grpTrackTotYear + $row['value'];
						}
					}
					if ($row['count'] != "" and $row['count'] != "NULL" and $row['count'] > 0){
						if ($startWeek <= $row['date'] and $row['date'] <= $endWeek){
							$grpTrackPreWeek = $grpTrackPreWeek + $row['count'];
						}
						if ($startMonth <= $row['date'] and $row['date'] <= $endMOnth){
							$grpTrackPreMonth = $grpTrackPreMonth + $row['count'];
						}
						if ($startYear <= $row['date'] and $row['date'] <= $endYear){
							$grpTrackPreYear = $grpTrackPreYear + $row['count'];
						}
					}
					
				}
			}//end of for each
			//$memberSpcTrack = $memberSpcTrack."<tr><th>Total</th><th></th><th>".$budgetTotInst->convertDollarF($memberAmtTot)."</th><th>".$memberPreTot."</th><th></th><th></th></tr>";
		}
				
		$datarpt = "<?php 
			header('Content-type: application/excel');
			header('Content-Disposition: attachment; filename=mx_report_file.xls');";
		$datarpt = $datarpt.'echo "';
		$datarpt = $datarpt."<html xmlns:x='urn:schemas-microsoft-com:office:excel'>;
			<head>
				<!--[if gte mso 9]>
				<xml>
					<x:ExcelWorkbook>
						<x:ExcelWorksheets>
							<x:ExcelWorksheet>
								<x:Name>Sheet 1</x:Name>
								<x:WorksheetOptions>
									<x:Print>
										<x:ValidPrinterInfo/>
									</x:Print>
								</x:WorksheetOptions>
							</x:ExcelWorksheet>
						</x:ExcelWorksheets>
					</x:ExcelWorkbook>
				</xml>
				<![endif]-->
			</head>
			<body>";
		$datarpt = $datarpt."<table class='table table-striped table-bordered table-hover'>
								<tr><td colspan='6'>Report Total</td></tr>
									<tr>
										<th>Category</th>
										<th>Subcategory</th>
										<th>Amount</th>
										<th>Number</th>
										<th>Date</th>
										<th>Note</th>
									</tr>
									".$memberSpcTrack."
							  </table>
		</body></html>";
		$datarpt = $datarpt.'"; ?>';
		
		$myFile = 'm_reports/rpt001trk_m'.$memberID.'.php';
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, "");
		fwrite($fh, $datarpt);
		fclose($fh);
		
	}else{  //if statement - not login
		print "<script> self.location='".$index_url."'; </script>";
	}
?>