function delSubCatF(num){
	curTrkCatID = $("#current_cat").val();
	curTrkCatTyp = $("#current_cat_type").val();	
	
	curSubSelectCat = $("#subpurposect").val();
	curSubSelectNum = $("#subpurposenum").val();
	
	if (curTrkCatTyp == "numb" || curTrkCatTyp == "mone"){
		tempTot = $("#ct"+curTrkCatID).val();
		if (tempTot.substr(0,1)=="$"){
			tempTot = tempTot.substr(1);
		}
		tempTot = tempTot.replace(",","");
		
		tempVal = $("#va"+num).val();
		if (tempVal.substr(0,1)=="$"){
			tempVal = tempVal.substr(1);
		}
		tempVal = tempVal.replace(",","");
		
		if (parseFloat(tempTot) > parseFloat(tempVal)){
			tempTot = parseFloat(tempTot) - parseFloat(tempVal);	
		}else{
			tempTot = 0;
		}
		if (curTrkCatTyp == "numb"){
			$("#ct"+curTrkCatID).val(tempTot);
		}
		if (curTrkCatTyp == "mone"){
			if (tempTot == 0){
				$("#ct"+curTrkCatID).val("$0.00");
			}else{
				temptot = parseFloat(temptot);
				temptot = temptot.toFixed(2);
				temptot = temptot.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
				$("#ct"+curTrkCatID).val("$"+temptot);
			}
		}
	}
	
	$("#subtrkcat" + num).remove();
	
	subNum = $("#subpurposenum").val();
	subNumArr = subNum.split(',');
	subNumArrCt = subNumArr.length;
	
	for (i=0; i<subNumArrCt; i++){
		if (subNumArr[i]==num){
			subNumArr.splice(i, 1);
		}
	}

	$("#subpurposenum").val(subNumArr);
	if (subNumArr == ""){
		location.reload();
	}
	return false;
}

function addSubCatF(num){
	curTrkCatID = $("#current_cat").val();
	curSelectCat = $("#trkcategorylist").val();

	if ($("#subpurposenum").val() == ""){
		curSubSelectCat = 0;
		curSubSelectNum = 0;
		$("#subpurposect").val(curSubSelectCat);
		$('#subpurposenum').val(curSubSelectCat);
		if (num == 1){
			$("#ct"+curTrkCatID).val(0); //set total to 0
		}
		if (num == 2){
			$("#ct"+curTrkCatID).val("$0.00"); //set total to 0
		}
		if (num == 3){
			$("#ct"+curTrkCatID).val(""); //set total to 0
		}
		subNumArrCt = 0 //no subcat array yet
	}else{
			subNum = $("#subpurposenum").val();
			subNumArr = subNum.split(',');
			subNumArrCt = subNumArr.length;
			
			if (subNumArrCt < 5){//only perform this up to 4 but not 5
				curSubSelectCat = parseInt($("#subpurposect").val())+1;
				$("#subpurposect").val(curSubSelectCat);
				curSubSelectNum = $('#subpurposenum').val();
				$('#subpurposenum').val(curSubSelectNum+","+curSubSelectCat);	
			}
	}
	
	if (subNumArrCt <= 4){
		trackSubmit = "yes";
		if (trackSubmit == "yes"){
			$.post("m_momax/nonfin/m_inc/chktrkcategory.php",{
			  trkcatid: curSelectCat, 
			  mid: $("#mid").val(),
			  actState: "catlist" //processing state
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					if (num == 1 || num == 2){
						$("#mainct").text("Total:");
						$("#ct"+curTrkCatID).attr("disabled", "disabled");
					}
					if (num == 3){
						$("#mainct").text("Main:");
						$("#ct"+curTrkCatID).attr("disabled", false);
					}
					newSubCatBoxDiv = $(document.createElement('div'))
						.attr("id", 'subtrkcat' + curSubSelectCat);
					
					selectSubCatData = '<div class="form-group" id="subtrkcat'+curSubSelectCat+'"><label class="col-lg-3 control-label">';
					if (num == 1){
						selectSubCatData = selectSubCatData+'Number:</label><div class="col-lg-2"><input type="text" name="va'+curSubSelectCat+'" id="va'+curSubSelectCat+'" class="form-control" placeholder="0" onchange="isNumberOnlyTot(this.id,this.value)" /></div>';
					}
					if (num == 2){
						selectSubCatData = selectSubCatData+'Amount:</label><div class="col-lg-2"><input type="text" name="va'+curSubSelectCat+'" id="va'+curSubSelectCat+'" class="form-control" placeholder="$0.00" onchange="isNumber_chkTot(this.id,this.value)" /></div>';
					}
					if (num == 3){
						selectSubCatData = selectSubCatData+'Text:</label><div class="col-lg-2"><input type="text" name="va'+curSubSelectCat+'" id="va'+curSubSelectCat+'" class="form-control" /></div>';
					}
					selectSubCatData = selectSubCatData+'<div class="col-lg-1 control-label">Purpose:</div><div class="col-lg-3"><select class="form-control" id="pu'+curSubSelectCat+'" name="pu'+curSubSelectCat+'">'+data+'</select></div><div class="col-lg-1 control-label"><a href="#" onclick="delSubCatF('+curSubSelectCat+')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></a></div></div>';
					newSubCatBoxDiv.after().html(selectSubCatData);
					newSubCatBoxDiv.appendTo("#SubCatBoxesGroup");
					
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	}else{
		alert("Only 5 subcategory breakdowns allow.");	
	}
	return false;
}

//turn on off style 
function onoff_trackingF(num){
	if (num == 1){
		document.getElementById("link_generaltrk").style.display ='';
		document.getElementById("link_grouptrk").style.display ='none';
		document.getElementById("link_membertrk").style.display ='none';
	}
	if (num == 2){
		document.getElementById("link_generaltrk").style.display ='none';
		document.getElementById("link_grouptrk").style.display ='';
		document.getElementById("link_membertrk").style.display ='none';
	}
	if (num == 3){
		document.getElementById("link_generaltrk").style.display ='none';
		document.getElementById("link_grouptrk").style.display ='none';
		document.getElementById("link_membertrk").style.display ='';
	}
	
}

//update tracking info
function savgeneraltrkF(action,grpattendID,typeNum,subcatYN,numSubCat){
	//action: 1-save and 2-delete; subcatYN: 1-yes and 0-no; numSubCat: number of subcategory count; 
	//typeNum: 1-money, 2-number, and 3-text
	trackSubmit = "no";
	if (action == 1){
		if (typeNum == 1){//money value
			value = $("#val"+grpattendID).val();
			value = value.replace('$', '');
			value = value.replace(',', '');
		}
		if (typeNum == 2){//number value
			value = $("#num"+grpattendID).val();
			value = value.replace('$', '');
			value = value.replace(',', '');
		}
		if (typeNum == 3){//text value
			value = $("#txt"+grpattendID).val();
			value = value.replace('"', '');
			if (value == ""){
				alert("Text cannot be empty.");
				setTimeout(function() { document.getElementById("txt"+grpattendID).focus(); }, 10);
				trackSubmit = "no";
				return false;
			}
		}
		note = $("#nte"+grpattendID).val(); //note field
		
		if (subcatYN == 1 && numSubCat > 0){
			valSubcatVaArr = "";
			valSubcatIdArr = "";
			valSubcatVaArrCt = 0;
			for (i=1; i<=numSubCat; i++){
				tempVal = $("#sub"+grpattendID+"n"+i).val();
				tempId = $("#sub"+grpattendID+"n"+i).prop('name');
				if (typeNum == 1 || typeNum == 2){
					tempVal = tempVal.replace('$', '');
					tempVal = tempVal.replace(',', '');
					if (tempVal == 0){
						tempVal = 0;
					}
				}
				if (typeNum == 3){
					tempVal = tempVal.replace(',', '*');
					tempVal = tempVal.replace('"', '');
					if (tempVal == ""){
						tempVal = "";
					}
				}
				//if (tempVal != ""){
					if (i == 1){
						valSubcatVaArr = tempVal;
						valSubcatIdArr = tempId;
					}else{
						valSubcatVaArr = valSubcatVaArr +","+ tempVal;
						valSubcatIdArr = valSubcatIdArr +","+ tempId;
					}
					valSubcatVaArrCt++;
				//}
			}//end for loop
		}else{
			valSubcatVaArr = "";
			valSubcatIdArr = "";
			valSubcatVaArrCt = 0;
		}
		if (valSubcatVaArrCt == 0){//in case subcatYN is true but no value
			valSubcatVaArr = "";
			valSubcatIdArr = "";
		}
		
		state = "save";
		trackSubmit = "yes";
	}
	if (action == 2){
		if (confirm("Please confirm tracking deletion.") == true){
			state = "delete";
			value = "";
			note = "";
			valSubcatVaArr = "";
			valSubcatIdArr = "";
			valSubcatVaArrCt = 0;
			trackSubmit = "yes";
		}//end confirm deletion
	}
	
	if (trackSubmit == "yes"){
		$.post("m_momax/nonfin/m_inc/dtegeneraltrk.php",{
		  grpattendID: grpattendID,
		  typeNum: typeNum,
		  value: value,
		  note: note,
		  subcatYN: subcatYN,
		  valSubcatVaArrCt: valSubcatVaArrCt,
		  valSubcatVaArr: valSubcatVaArr,
		  valSubcatIdArr: valSubcatIdArr,
		  actState: state //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}//end if processing ajax
}//end 



$(document).ready(function(){
	 $('#data_generaltrk12').dataTable({
	   "sPaginationType": "full_numbers",
	   "aaSorting": [], //not initial sort
	   //"scrollX": true,
	   "aoColumnDefs": [{'bSortable': false,'aTargets': [ 2 ]},
						{'bSortable': false,'aTargets': [ 4 ]},
						{'bSortable': false,'aTargets': [ 5 ]}]
	});
	 //mileage tracking
	 $("#addmileage").click(function () {
		$("#trackType").text("Save");
		$("#trackdelall").text("");
		$("#milid").val("");
		$("#state").val("add");
		$("#name").val("");
		$("#rate").val("");
		$("#note").val("");
		$("#myModalAdd").modal("show");
	 });
	 $("#addTrackDet").click(function () {
		trackSubmit = "yes"; //control flag
		if ($("#name").val() == ""){
			alert("Please enter purpose");
			setTimeout(function() { document.getElementById("name").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if ($("#rate").val() == ""){
			alert("Please enter rate ($/mile)");
			setTimeout(function() { document.getElementById("rate").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if (trackSubmit == "yes"){
			$("#myModalAdd").modal("hide");
			$("#addtracking_frm").submit();
		}
	 });
	 
	 //mileage tracking details
	 $("#addnewmildet").click(function () {
		var currentTime = new Date()
		var month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
		var day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
		var year = currentTime.getFullYear()
		
		$("#addSubDet").text("Save");
		$("#mildetid").val("");
		$("#state").val("add");
		$("#mile_date").val(month+"-"+day+"-"+year);
		$("#purpose").val("");
		$("#starto").val("");
		$("#endo").val("");
		$("#note").val("");
		$("#myModalAdd").modal("show");
	 });
	 $("#addSubDet").click(function () {//add mileage details
		trackSubmit = "yes"; //control flag
		if ($("#purpose").val() == ""){
			alert("Please enter purpose");
			setTimeout(function() { document.getElementById("purpose").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if ($("#starto").val() == "" || $("#starto").val() == 0){
			alert("Please enter start odometer");
			setTimeout(function() { document.getElementById("starto").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if ($("#endo").val() == "" || $("#endo").val() == 0){
			alert("Please enter end odometer");
			setTimeout(function() { document.getElementById("endo").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if ($("#endo").val() <= $("#starto").val()){
			alert("End odometer needs to be higher than start odometer.");
			$("#starto").val("");
			$("#endo").val("");
			setTimeout(function() { document.getElementById("starto").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if (trackSubmit == "yes"){
			$("#myModalAdd").modal("hide");
			$("#detmil_frm").submit();
		}
	 });
	 
	 //general tracking
	 $("#addgentrk").click(function () {
		$("#trackGen").text("Save");
		$("#trkid").val("");
		$("#trkstate").val("add");
		$("#trkname").val("");
		$("#trknote").val("");
		$("#myModalTrack").modal("show");
	 });
	 $("#addTrkGen").click(function () {
		trackSubmit = "yes"; //control flag
		if ($("#trkname").val() == ""){
			alert("Please enter purpose");
			setTimeout(function() { document.getElementById("trkname").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if (trackSubmit == "yes"){
			$("#myModalTrack").modal("hide");
			$("#addgentrk_frm").submit();
		}
	 });
	 
	 //general tracking details
	 $("#addnewgendet").click(function () {
		var currentTime = new Date()
		var month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
		var day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
		var year = currentTime.getFullYear()
		
		$("#addSubDet").text("Save");
		$("#trkdetid").val("");
		$("#state").val("add");
		$("#trk_date").val(month+"-"+day+"-"+year);
		$("#purpose").val("");
		$("#value").val("");
		$("#category").val("");
		$("#note").val("");
		$("#myModalAdd").modal("show");
	 });
	 $("#addGenDet").click(function () {//add general tracking details
		trackSubmit = "yes"; //control flag
		if ($("#purpose").val() == ""){
			alert("Please enter purpose");
			setTimeout(function() { document.getElementById("purpose").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if (trackSubmit == "yes"){
			$("#myModalAdd").modal("hide");
			$("#detgen_frm").submit();
		}
	 });
	 
	 // member tracking
	 $("#addchurchtrk").click(function () {
		$("#addmembut").text("Save");
		$("#trkmemid").val("");
		$("#trkmemstate").val("add");
		$("#mempurpose").val("");
		$("#memnote").val("");
		$("#trkmemberGrp option:first").attr('selected','selected'); //preselect to the first item in the list
		$("#trkmemberCategory option:first").attr('selected','selected'); //preselect to the first item in the list
		t_now = new Date();
		t_day = ("0" + t_now.getDate()).slice(-2);
		t_month = ("0" + (t_now.getMonth() + 1)).slice(-2);
		t_today = (t_month)+"-"+(t_day)+"-"+t_now.getFullYear();
		$("#mem_date").val(t_today);
		$("#myChurchTrack").modal("show");
	 });
	 $("#addchrMem").click(function () {
		trackSubmit = "yes"; //control flag
		if ($("#mempurpose").val() == ""){
			alert("Please enter purpose");
			setTimeout(function() { document.getElementById("mempurpose").focus(); }, 10);
			trackSubmit = "no"; //control flag
			return false;
		}
		if ($("#mem_date").val() == ""){
			t_now = new Date();
			t_day = ("0" + t_now.getDate()).slice(-2);
			t_month = ("0" + (t_now.getMonth() + 1)).slice(-2);
			t_today = (t_month)+"-"+(t_day)+"-"+t_now.getFullYear();
			$("#mem_date").val(t_today);
		}
		if (trackSubmit == "yes"){
			$("#myChurchTrack").modal("hide");
			$("#addchurch_frm").submit();
		}
	 });
	 
	 //member tracking details
	 $("#trkmenDetModal").click(function () {
		var currentTime = new Date();
		var month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
		var day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
		var year = currentTime.getFullYear();
		
		$("#addSubDet").text("Save");
		$("#myModalAdd").modal("show");
	 });
	 $("#addTrkmemDet").click(function () {
		trackSubmit = "yes"; //control flag
		if (trackSubmit == "yes"){
			$("#myModalAdd").modal("hide");
			$.post("m_momax/nonfin/m_inc/addmemtracking.php",{
			  mid: $("#delmemberGrp").val(), //member id
			  trkmemid: $("#trkmemberid").val(), //trkmemberID
			  actState: "add" //processing state
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					location.reload();
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	 });
	 	 
	 //autocomplete - select member from group
	 $("#selectmemberlist").on("keydown", function() {
			mid = $("#mid").val();
			groupid = $("#trkmemgrplist").val();
			$.get( "m_momax/nonfin/m_inc/searchmem.php", { mid:mid,groupid:groupid } )
			.done(function( data ) {	
				memberIdInfoObj = JSON.parse(data);
			});
			var memID = memberIdInfoObj; 
			$("#selectmemberlist").autocomplete({
				minLength: 0,
				source: memID,
				focus: function(event, ui) {
					event.preventDefault();// prevent autocomplete from updating the textbox
					$(this).val(ui.item.label);// manually update the textbox
				},
				select: function(event, ui) {
					event.preventDefault();// prevent autocomplete from updating the textbox
					$(this).val(ui.item.label);// manually update the textbox and hidden field
					$("#selectmember").val(ui.item.label);
					$("#selectmemberid").val(ui.item.value); 
				}
			});
	 });
	 
	 $("#trkmemgrplist").change(function () { //reset member textbox
			$("#selectmemberlist").val(""); //name is not needed
			$("#selectmember").val(""); //name is not needed
			$("#selectmemberid").val(""); //memberID is not needed
	 });
	 
	 $("#trkcategorylist").change(function () {
			currCat = $("#current_cat").val();
			$("#trkcat" + currCat).remove(); //remove main category
			
			subNum = $("#subpurposenum").val();
			subNumArr = subNum.split(',');
			subNumArrCt = subNumArr.length;
			for (i=0; i<subNumArrCt; i++){
				$("#subtrkcat"+i).remove(); //remove all sub categories
			}
			
			curSelectCat = $("#trkcategorylist").val(); //currect selected trkcategoryID
			trackSubmit = "yes";
			if (trackSubmit == "yes"){
				$.post("m_momax/nonfin/m_inc/chktrkcategory.php",{
				  trkcatid: curSelectCat, 
				  mid: $("#mid").val(),
				  actState: "new" //processing state
				},
				function(data,status){
					if (status == "success"){
						//alert("Data: " + data + "\nStatus: " + status);
						
						selectSubCat = data.substr(0,2);
						selectCatTyp = data.substr(2,4);
						selectCatData = data.substr(6);;
						$("#current_cat").val(curSelectCat);
						$("#current_cat_type").val(selectCatTyp);
						$("#current_subcat").val(selectSubCat);
						
						newCatBoxDiv = $(document.createElement('div'))
							.attr("id", 'trkcat' + curSelectCat);
						newCatBoxDiv.after().html(selectCatData);
						newCatBoxDiv.appendTo("#CatBoxesGroup");
							
						$("#subpurposect").val(""); //set array count to 0
						$("#subpurposenum").val(""); //set array to empty
					}else{
						alert("System is ecountering issue and cannot save data at this point.");
					}
				});
			}
			
	 });
	 
	 //$("#addSubCat").click(function () { //not being used - use function instead...
	//	curSubSelectCat = parseInt($("#subpurposect").val())+1;
	//	if (curSubSelectCat > 1){	
	//		curSubSelectNum = $('#subpurposenum').val();
	///		$('#subpurposenum').val(curSubSelectNum+","+curSubSelectCat);
	//	}else{
	//		$('#subpurposenum').val(curSubSelectCat);
	//	}
	//	
	//	if (curSubSelectCat <= 10){
	//		curTrkCatID = $("#current_cat").val();
	//		
	//		newSubCatBoxDiv = $(document.createElement('div'))
	//			.attr("id", 'subtrkcat' + curSubSelectCat);
	//		
	//		selectSubCatData = '<div class="form-group" id="subtrkcat'+curSubSelectCat+'"><label class="col-lg-3 control-label">Amount:</label><div class="col-lg-2"><input type="text" name="va'+curSubSelectCat+'" id="va'+curSubSelectCat+'" class="form-control" placeholder="$0.00" onchange="isNumber_chk(this.id,this.value)" /></div>';
	//		selectSubCatData = selectSubCatData+'<div class="col-lg-1 control-label">Purpose:</div><div class="col-lg-2"><input type="text" name="pu'+curSubSelectCat+'" id="pu'+curSubSelectCat+'" class="form-control" /></div><div class="col-lg-1 control-label"><a href="#" onclick="delSubCatF('+curSubSelectCat+')"><button class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></a></div></div>';
	//		newSubCatBoxDiv.after().html(selectSubCatData);
	//		newSubCatBoxDiv.appendTo("#SubCatBoxesGroup");
	//		
	//		$("#subpurposect").val(curSubSelectCat);
	//		
	//	}else{
	//		alert("Only 10 subcategory breakdowns allow.");	
	//	}
	//	return false;
	 //});
	 	 
	 $("#saveindtracking").click(function () {		
		var currentTime = new Date()
		var month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
		var day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
		var year = currentTime.getFullYear()
		trackSubmit = "yes"; //control flag
		
		if ($("#trkmemgrplist").val() == "nogroup"){
			if ($("#selectmemberlist").val() == ""){
				selectname = "";
				$("#selectmember").val(""); //name is same as selectname
			}else{
				selectname = $("#selectmemberlist").val();
			}
			selectnameid = "";//memberID is not needed
			selectgrpid = ""; //groupID for nongroup has to be empty
		}else{		
			if ($("#selectmemberlist").val() == ""){
				alert("Please enter name.");
				setTimeout(function() { document.getElementById("selectmemberlist").focus(); }, 10);
				$("#selectmemberlist").val("");
				$("#selectmember").val("");
				$("#selectmemberid").val("");
				trackSubmit = "no";
				return false;
			}
			if ($("#selectmember").val() == "" || $("#selectmemberid").val() == ""){
				if ($("#trkmemgrplist").val() != "nogroup"){
					alert("Name is not in the selected group.");
					setTimeout(function() { document.getElementById("selectmemberlist").focus(); }, 10);
					$("#selectmemberlist").val("");
					$("#selectmember").val("");
					$("#selectmemberid").val("");
					trackSubmit = "no";
					return false;
				}
			}
			selectgrpid = $("#trkmemgrplist").val();
			selectname = $("#selectmemberlist").val();
			selectnameid = $("#selectmemberid").val();
		}
		
		if ($("#onemem_date").val() == ""){
			$("#onemem_date").val(month+"-"+day+"-"+year)	
		}
		
		valCatId = $("#trkcategorylist").val();
		if ($("#current_cat_type").val()=="mone"){
			valCatCt = $("#ct"+valCatId).val();
			valCatCt = valCatCt.replace('$', '');
			valCatCt = valCatCt.replace(',', '');
			if (valCatCt <= 0){
				alert("Amount should be greater than $0.00.");
				setTimeout(function() { document.getElementById("ct"+valCatId).focus(); }, 10);
				trackSubmit = "no";
				return false;
			}
		}
		
		if ($("#current_cat_type").val()=="numb"){
			valCatCt = $("#ct"+valCatId).val();
			if (valCatCt <= 0){
				alert("Number should be greater than 0.");
				setTimeout(function() { document.getElementById("ct"+valCatId).focus(); }, 10);
				trackSubmit = "no";
				return false;
			}
		}
		
		if ($("#current_cat_type").val()=="text"){
			valCatCt = $("#ct"+valCatId).val();
			if (valCatCt == ""){
				alert("Text info is required.");
				setTimeout(function() { document.getElementById("ct"+valCatId).focus(); }, 10);
				trackSubmit = "no";
				return false;
			}
		}
		
		if ($("#current_subcat").val() == "ye" && $("#subpurposenum").val() != ""){
			valCatType = $("#current_cat_type").val();
			valCatCt = $("#ct"+valCatId).val();
			subNum = $("#subpurposenum").val();
			subNumArr = subNum.split(',');
			subNumArrCt = subNumArr.length;
			valCatArr = "";
			valCatArrCt = 0;
			for (i=0; i<subNumArrCt; i++){
				tempVal = $("#va"+subNumArr[i]).val();
				tempVal = tempVal.replace('$', '');
				tempVal = tempVal.replace(',', '');
				if (tempVal != ""){
					if ((($("#current_cat_type").val()=="numb" || $("#current_cat_type").val()=="mone")&& tempVal > 0)||($("#current_cat_type").val()=="text" && tempVal != "")){
						if (i == 0){
							valCatArr = tempVal;
						}else{
							valCatArr = valCatArr +","+ tempVal;
						}
						tempPur = $("#pu"+subNumArr[i]).val();
						if (i == 0){
							purCatArr = tempPur;
						}else{
							purCatArr = purCatArr +","+ tempPur;
						}
						valCatArrCt++;
					}
				}
			}//end for loop
			subNumArrCt = valCatArrCt;
		}else{
			valCatArr = "";
			purCatArr = "";
			subNumArrCt = "0";//subNumArr.length;
		}
		
		if (subNumArrCt == 0){
			valCatArr = "";
			purCatArr = "";
			subNumArrCt = "0";//subNumArr.length;
		}
		
		valCatType = $("#current_cat_type").val();
		valCatCt = $("#ct"+valCatId).val();
		if ($("#mem_note").val() == ""){
			note = "";
		}else{
			note = $("#mem_note").val();
		}
			
		if (trackSubmit == "yes"){
			$.post("m_momax/nonfin/m_inc/uptmemattend.php",{
			  mid: $("#mid").val(),
			  selectgrpid: selectgrpid,
			  selectname: selectname, 
			  selectnameid: selectnameid, 
			  valCatId: valCatId,
			  valCatType: valCatType,
			  valCatCt: valCatCt,
			  valCatArr: valCatArr,
			  purCatArr: purCatArr,
			  subNumArrCt: subNumArrCt,
			  date: $("#onemem_date").val(),
			  note: note,
			  actState: "add" //processing state
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					alert("Information is saved");
					location.reload();
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
		return false; //stop form from submitting
	 });
	
}); //end of ready(function)

////get mileage tracking
function editTrack(id,memberID,num){
	if (num == 1){//edit
		$("#trackType").text("Update");
		$("#trackdelall").text("");
		$("#state").val("update");
		$("#milid").val(id);
	}
	if (num == 2){//delete
		$("#trackType").text("Delete");
		$("#trackdelall").text("* All mileages will be deleted.");
		$("#state").val("delete");
		$("#milid").val(id);
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/gettracking.php",{
		  mileageID: id, //mileageID
		  mid: memberID, //member id
		  actState: num //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				objTrackInfo = JSON.parse(data);
				$("#name").val(objTrackInfo.name);
				$("#rate").val(isNumber_dollar(objTrackInfo.rate));
				$("#note").val(objTrackInfo.note);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	$("#myModalAdd").modal("show");
}

////get mileage tracking details
function editMilDet(id,memberID,num){
	if (num == 1){//edit
		$("#addSubDet").text("Update");
		$("#state").val("update");
		$("#mildetid").val(id);
	}
	if (num == 2){//delete
		$("#addSubDet").text("Delete");
		$("#state").val("delete");
		$("#mildetid").val(id);
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/getdettracking.php",{
		  mileageID: id, //mileageID
		  mid: memberID, //member id
		  actState: num //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				objTrackInfo = JSON.parse(data);
				$("#mile_date").val(objTrackInfo.date);
				$("#purpose").val(objTrackInfo.purpose);
				$("#starto").val(objTrackInfo.starto);
				$("#endo").val(objTrackInfo.endo);
				$("#note").val(objTrackInfo.note);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	$("#myModalAdd").modal("show");
}

////get general tracking
function editGenTrack(id,memberID,num){
	if (num == 1){//edit
		$("#trackGen").text("Update");
		$("#trkstate").val("update");
		$("#trkid").val(id);
	}
	if (num == 2){//delete
		$("#trackGen").text("Delete");
		$("#trkstate").val("delete");
		$("#trkid").val(id);
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/gentracking.php",{
		  genID: id, //mileageID
		  mid: memberID, //member id
		  actState: num //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				objTrackInfo = JSON.parse(data);
				$("#trkname").val(objTrackInfo.name);
				$("#trknote").val(objTrackInfo.note);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	$("#myModalTrack").modal("show");
}

////get general tracking details
function editGenDet(id,memberID,num){
	if (num == 1){//edit
		$("#addGenDet").text("Update");
		$("#state").val("update");
		$("#trkdetid").val(id);
	}
	if (num == 2){//delete
		$("#addGenDet").text("Delete");
		$("#state").val("delete");
		$("#trkdetid").val(id);
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/gendettracking.php",{
		  genDetID: id, //mileageID
		  mid: memberID, //member id
		  actState: num //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				objTrackInfo = JSON.parse(data);
				$("#trk_date").val(objTrackInfo.date);
				$("#purpose").val(objTrackInfo.purpose);
				$("#value").val(isNumber_dollar(objTrackInfo.value));
				$("#category").val(objTrackInfo.category);
				$("#note").val(objTrackInfo.note);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	$("#myModalAdd").modal("show");
}

////get member tracking info
function editTrkMember(id,memberID,num){
	if (num == 1){//edit
		$("#addmembut").text("Update");
		$("#trkmemstate").val("update");
		$("#trkmemid").val(id);
	}
	if (num == 2){//delete
		$("#addmembut").text("Delete");
		$("#trkmemstate").val("delete");
		$("#trkmemid").val(id);
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/memtracking.php",{
		  trkmemID: id, //mileageID
		  mid: memberID, //member id
		  actState: num //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				objTrkmemInfo = JSON.parse(data);
				$("#mempurpose").val(objTrkmemInfo.name);
				$("#trkmemberGrp").val(objTrkmemInfo.groupid);
				$("#trkmemberCategory").val(objTrkmemInfo.cat);
				if (objTrkmemInfo.act == "NULL" || objTrkmemInfo.act == "" || objTrkmemInfo.act == 0){
					$("#trkmemberAccount").val("none");
				}else{
					$("#trkmemberAccount").val(objTrkmemInfo.act);
				}
				$("#mem_date").val(objTrkmemInfo.date);
				$("#memnote").val(objTrkmemInfo.note);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	$("#myChurchTrack").modal("show");
}

////get member tracking details
function saveTrkmemberDet(id,memberID,num){
	value = document.getElementById("v"+id).value;
	if (document.getElementById("p"+id).checked == true){
		present = "yes";
	}else{
		present = "no";
	}
	note = document.getElementById("nt"+id).value;
	if (num == 1){//save specific member details
		state = "save";
	}
	if (num == 2){//delete specific member details
		state = "delete";
	}
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/nonfin/m_inc/addmemtracking.php",{
		  mid: memberID, //member id
		  trkmemid: id, //trkmemberID
		  accountid: $("#accountid").val(),
		  amount: value,
		  attend: present,
		  note: note,
		  actState: state //processing state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "save"){
					alert(document.getElementById("mem"+id).value+" is updated");
				}
				if (data == "delete"){
					alert(document.getElementById("mem"+id).value+" is deleted");
				}
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
//check for number value
function isNumberOnlyTot(id, value) {  
	curTrkCatID = $("#current_cat").val();
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Value is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
	}
	
	total = 0;	
	if ($("#subpurposect").val() > 0){
		subNum = $("#subpurposenum").val();
		subNumArr = subNum.split(',');
		subNumArrCt = subNumArr.length;
		for (i=0; i<subNumArrCt; i++){
			tempNum = $("#va"+i).val();
			if (tempNum == "" || tempNum < 0){
				tempNum = 0;
			}
			total = total + parseFloat(tempNum);
		}
	}else{
		total = total + parseFloat(value);
	}
	$("#ct"+curTrkCatID).val(total);
}
//check numeric value  
function isNumber_chkTot(id, value) {  
	curTrkCatID = $("#current_cat").val();
	if (value.substr(0,1)=="$"){
		value = value.substr(1);
	}
	value = value.replace(",","");
	
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Amount is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
	}
	
	total = 0;
	subNum = $("#subpurposenum").val();
	subNumArr = subNum.split(',');
	subNumArrCt = subNumArr.length;
	if (subNumArrCt > 0){
		for (i=0; i<subNumArrCt; i++){
			tempVal = $("#va"+i).val();
			if (tempVal.substr(0,1)=="$"){
				tempVal = tempVal.substr(1);
			}
			tempVal = tempVal.replace(",","");
			if (tempVal != "" || tempVal > 0){
				total = total + parseFloat(tempVal);
			}
		}
	}else{
		total = total + parseFloat(value);
	}

	temptot = parseFloat(total);
	temptot = temptot.toFixed(2);
	temptot = temptot.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	$("#ct"+curTrkCatID).val("$"+temptot);

	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./))     
	{ val += '.00'; }
	if (!val.match(/\.\d\d/)) 
	{ val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	document.getElementById(id).value = '$'+ val;
}
//check numeric value  
function isNumber_chk(id, value) {  
	if (value.substr(0,1)=="$"){
		value = value.substr(1);
	}
	value = value.replace(",","");
	
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Amount is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
  		return false;
	}
	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./))     
	{ val += '.00'; }
	if (!val.match(/\.\d\d/)) 
	{ val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	document.getElementById(id).value = '$'+ val;
}
//check numeric value  
function isNumber_chksub(id,value,num) {  
	if (value.substr(0,1)=="$"){
		value = value.substr(1);
	}
	value = value.replace(",","");
	
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Amount is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
  		return false;
	}
	
	endnum = id.indexOf("n");
	strNum = id.substring(3,endnum);
	
	total = 0;
	if (num > 0){
		for (i=1; i<=num; i++){
			tempVal = $("#sub"+strNum+"n"+i).val();
			if (tempVal.substr(0,1)=="$"){
				tempVal = tempVal.substr(1);
			}
			tempVal = tempVal.replace(",","");
			if (tempVal != "" && tempVal > 0){
				total = total + parseFloat(tempVal);
			}
		}
	}

	temptot = parseFloat(total);
	temptot = temptot.toFixed(2);
	temptot = temptot.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	$("#val"+strNum).val("$"+temptot);
	
	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./))     
	{ val += '.00'; }
	if (!val.match(/\.\d\d/)) 
	{ val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	document.getElementById(id).value = '$'+ val;
}

//check for number value
function isNumberOnly_sub(id,value,num) {  
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Value is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
  		return false;
	}
	
	endnum = id.indexOf("n");
	strNum = id.substring(3,endnum);
	
	total = 0;
	if (num > 0){
		for (i=1; i<=num; i++){
			tempVal = $("#sub"+strNum+"n"+i).val();
			if (tempVal.substr(0,1)=="$"){
				tempVal = tempVal.substr(1);
			}
			tempVal = tempVal.replace(",","");
			if (tempVal != "" && tempVal > 0){
				total = total + parseFloat(tempVal);
			}
		}
	}
	$("#num"+strNum).val(total);
}