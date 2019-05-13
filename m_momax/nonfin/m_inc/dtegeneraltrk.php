<?php
	include ('../../../m_inc/m_config.php'); //mysql cridential
	include ('../../../m_class/class_lib.php');
	include ('../../../m_inc/def_value.php');
	
	$generalInfo = new generalInfoC();
	$grpattendID = $_POST['grpattendID'];
	$typeNum = $_POST['typeNum'];
	$value = $_POST['value'];
	$note = $_POST['note'];
	$subcatYN = $_POST['subcatYN'];
	$valSubcatVaArrCt = $_POST['valSubcatVaArrCt'];
	$valSubcatVaArr = $_POST['valSubcatVaArr'];
	$valSubcatIdArr = $_POST['valSubcatIdArr'];
	$state = $_POST['actState'];
	
	$trkcatItemsList = "";
	$yes = "yes";
	
	if ($state == "delete"){
		try{//delete member tracking
			$result = $db->prepare("DELETE FROM $db_grpattend WHERE grpattend_id=?");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		try{//delete member tracking
			$result = $db->prepare("DELETE FROM $db_grpattend_sub WHERE grpattend_id=?");
			$result->execute(array($grpattendID));
		} catch(PDOException $e) {
			echo "message001 - Sorry, system is experincing problem. Please check back.";
		}
		echo "delete all";
	}
	
	if ($state == "save"){
		$note = str_replace('"', "", $_POST['note']);
		
		if ($typeNum == 1 or $typeNum == 2){
			$value = str_replace('$', "", $value);
			$value = str_replace(',', "", $value);
		}
		if ($typeNum == 3){
			$value = str_replace('"', "", $value);
		}
		
		if (($value==0 and ($typeNum==1 or $typeNum==2)) or ($value=="" and $typeNum==3)){
			try{//delete member tracking
				$result = $db->prepare("DELETE FROM $db_grpattend WHERE grpattend_id=?");
				$result->execute(array($grpattendID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
			try{//delete member tracking
				$result = $db->prepare("DELETE FROM $db_grpattend_sub WHERE grpattend_id=?");
				$result->execute(array($grpattendID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
		}else{
			try{//set note to its value
				if ($typeNum == 1){
					$result = $db->prepare("UPDATE $db_grpattend SET value=?,note=? WHERE grpattend_id=?");
				}
				if ($typeNum == 2){
					$result = $db->prepare("UPDATE $db_grpattend SET count=?,note=? WHERE grpattend_id=?");
				}
				if ($typeNum == 3){
					$result = $db->prepare("UPDATE $db_grpattend SET text=?,note=? WHERE grpattend_id=?");
				}
				$result->execute(array($value,$note,$grpattendID));
			} catch(PDOException $e) {
				echo "message001 - Sorry, system is experincing problem. Please check back.";
			}
				
			if ($subcatYN == 1 and $valSubcatVaArrCt > 0){
				$valCatArr = array();
				$idCatArr = array();
				$valCatArr = explode(',', $valSubcatVaArr);
				$idCatArr = explode(',', $valSubcatIdArr);	
				for ($i = 0; $i < $valSubcatVaArrCt; $i++){
					if ($typeNum == 1 or $typeNum == 2){
						$subvalue = str_replace('$', "", $valCatArr[$i]);
						$subvalue = str_replace(',', "", $subvalue);
					}
					if ($typeNum == 3){
						$subvalue = str_replace('"', "", $valCatArr[$i]);
						$subvalue = str_replace('*', ",", $subvalue);
					}				
					
					if (($subvalue==0 and ($typeNum==1 or $typeNum==2)) or ($subvalue=="" and $typeNum==3)){
						try{//delete subtracking
							$result = $db->prepare("DELETE FROM $db_grpattend_sub WHERE grpattend_sub_id=?");
							$result->execute(array($idCatArr[$i]));
						} catch(PDOException $e) {
							echo "message001 - Sorry, system is experincing problem. Please check back.";
						}
						if ($valSubcatVaArrCt == 1){
							try{//
								$result = $db->prepare("UPDATE $db_grpattend SET subcat_yn=? WHERE grpattend_id=?");
								$result->execute(array("no",$grpattendID));
							} catch(PDOException $e) {
								echo "message001 - Sorry, system is experincing problem. Please check back.";
							}
						}
					}else{
						try{//
							if ($typeNum == 1){
								$result = $db->prepare("UPDATE $db_grpattend_sub SET value=? WHERE grpattend_sub_id=?");
							}
							if ($typeNum == 2){
								$result = $db->prepare("UPDATE $db_grpattend_sub SET count=? WHERE grpattend_sub_id=?");
							}
							if ($typeNum == 3){
								$result = $db->prepare("UPDATE $db_grpattend_sub SET text=? WHERE grpattend_sub_id=?");
							}
							$result->execute(array($subvalue,$idCatArr[$i]));
						} catch(PDOException $e) {
							echo "message001 - Sorry, system is experincing problem. Please check back.";
						}
					}
				}//end for loop
			}//end if statement
		}//end of else stateement
		echo "save all";
	}		
?>