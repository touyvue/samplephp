<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$generalInfo = new generalInfoC();
	$trkcatid = $_POST['trkcatid'];
	$memberID = $_POST['mid'];
	$state = $_POST['actState'];
	
	//$trkcatItemsList = $generalInfo->getTrkcategoryDropdownListF($memberID);
	$trkcatItemsList = "";
	$yes = "yes";
	try{
		$result = $db->prepare("SELECT trkcategory_id,category FROM $db_trkcategory WHERE member_id=? AND active=? ORDER BY category ASC");
		$result->execute(array($memberID,$yes));
	} catch(PDOException $e) {
		print "<script> self.location='".$index_url."?err=d1000'; script>";
	} 
	foreach ($result as $row) {
		$trkcatItemsList = $trkcatItemsList."<option value='".$row['trkcategory_id']."'>".$row['category']."</option>";
	}
	if ($state == "catlist"){
		$firstTrkCatVal = $trkcatItemsList;
	}
	if ($state == "new"){
		try{
			$result = $db->prepare("SELECT trkcategory_id,sub_yn,type FROM $db_trkcategory WHERE trkcategory_id=?");
			$result->execute(array($trkcatid));
		} catch(PDOException $e) {
			print "<script> self.location='".$index_url."?err=d1000'; script>";
		} 
		$firstTrkCatVal = "";
		foreach ($result as $row) {
			if ($row['sub_yn']=="yes"){
				$subcat = "ye";
			}else{
				$subcat = "no";
			}
			if ($row['type'] == "numb"){
				$firstTrkCatVal = $subcat.$row['type'].'<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Number:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" placeholder="0" onchange="isNumberOnly(this.id,this.value)" ></div>';
				if ($row['sub_yn']=="yes"){
					$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(1)"><button class="btn btn-xs btn-warning">Add breakdown</button></a></div></div>';
				}else{
					$firstTrkCatVal = $firstTrkCatVal.'</div>';
				}
			}
			if ($row['type'] == "mone"){
				$firstTrkCatVal = $subcat.$row['type'].'<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Amount:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" placeholder="$0.00" onchange="isNumber_chk(this.id,this.value)" ></div>';
				if ($row['sub_yn']=="yes"){
					$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(2)"><button class="btn btn-xs btn-warning">Add breakdown</button></a></div></div>';
				}else{
					$firstTrkCatVal = $firstTrkCatVal.'</div>';
				}
			}
			if ($row['type'] == "text"){
				$firstTrkCatVal = $subcat.$row['type'].'<div id="trkcat'.$row['trkcategory_id'].'"><label class="col-lg-3 control-label" id="mainct">Text:</label><div class="col-lg-3"><input type="text" name="ct'.$row['trkcategory_id'].'" id="ct'.$row['trkcategory_id'].'" class="form-control" ></div>';
				if ($row['sub_yn']=="yes"){
					$firstTrkCatVal = $firstTrkCatVal.'<div class="col-lg-1 control-label"><a href="#" onclick="addSubCatF(3)"><button class="btn btn-xs btn-warning">Add breakdown</button></a></div></div>';
				}else{
					$firstTrkCatVal = $firstTrkCatVal.'</div>';
				}
			}
		}
	}
		
	echo $firstTrkCatVal;

?>