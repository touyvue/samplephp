////open account form to enter new transaction
function addBudgetItem(aID,type){
	disableRecurring();
	disableAllAccounts();

	var today = new Date();
	today = todayDate();
	$("#actState").val("add");//assign add state to from
	$("#budCategory option:first").attr('selected','selected'); //preselect to the first item in the list
	
	//reinforce caching - after editing a record to turn on all available inserted accounts
	acct_ids = $("#allActiveAcct").val(); //get all account id
	counter = $("#allActivdAcctCt").val(); //get all account count
	str_val = 0;
	end_val = 9;
	for (i=0;i<counter;i++){
		temp_id = (acct_ids.toString()).substring(str_val,end_val);	
		document.getElementById("dis"+temp_id).style.display = "";//
		str_val = str_val + 9;
		end_val = end_val + 9;
	}//end reinforce
	
	if (type == 1){ 
		$("#myModalAdd").modal("show");
		$("#aTitle").text("Add Transaction");
		$("#aValType").text("Save");
		document.getElementById("posted").checked = false;
		$("#attImg").text("");
		document.getElementById("upt_edirecurring").style.display = "none";
	}

	$("#trans_date").val(today);
	$("#budgetAmount").val("");
	$("#note").val("");
	
	document.getElementById("on_receipt_img").style.display ='none';
	$("#attimg").attr("href", "#");
	$("#attimg").removeAttr('href');
	$("#attimg").text("No receipt");
	$("#attimg").removeAttr("class")
}

////open add budget saving form
function editBudgetItem(memberID,aID,transactionID,type){
	disableRecurring();
	disableAllAccounts();
	
	var today = new Date();
	today = todayDate();
	$("#actState").val("edit");//assign edit state to from
	
	if (type == 1){ 
		retriveBudget(memberID,aID,transactionID);
		$("#myModalAdd").modal("show");
		$("#aTitle").text("Edit Transaction");
		$("#aValType").text("Update");
		//document.getElementById("upt_edirecurring").style.display = "";
	}
	$("#trans_date").val(today);
	$("#budgetAmount").val("");
	$("#note").val("");
}

////open add budget expense form
function deleteBudgetItem(memberID,aID,transactionID,type){
	disableRecurring();
	disableAllAccounts();
	$("#actState").val("delete");//assign delete state to from
	if (type == 1){
		deleteAccountItems(memberID,aID,transactionID);
		$("#myModalDelete").modal("show");
		$("#dTitle").text("Delete Transaction");
		$("#dValType").text(" Delete");
	}
}

$(document).ready(function(){
	
	$("#addTrans").click(function(){
		incomeSubmit = "yes";
		
		if ($("#trans_date").val() == ""){//date validation
			alert("Enter budget date");
			$("#trans_date").focus();
			incomeSubmit = "no";
			return false;
		}
		if ($("#budgetAmount").val() == "" && incomeSubmit == "yes"){//amount validation
			alert("Enter amount");
			$("#budgetAmount").focus();
			incomeSubmit = "no";
			return false;
		}
		if ($("#posted").is(":checked") == true){
			posted = "yes";
		}else{
			posted = "no";	
		}
		
		document.getElementById('hidimg').value = "";
		canvas = "";
		canvas = document.getElementById('myreceipt');
		document.getElementById('hidimg').value = canvas.toDataURL('image/png');
		
		$("#myModalAdd").modal("hide");
		$("#addaccttrans").submit();
		
	});
	
	$("#deleteIncome").click(function(){
		incomeSubmit = "yes";
		transID = objBudInfo.transID; //current select budtransaction_id
		mid = $("#midDel").val(); //member id
		aid = $("#actidDel").val(); //budget id
	
		$("#myModalDelete").modal("hide");
		$("#delaccttrans").submit();
	});
		 
	 //choosetag
	 $("#choosetag").click(function(){		
		selecttagid = $("#setgrpcategory").val(); //tag_id
		$("#selecttagid").val(selecttagid); 
		$("#showCatTag").modal("hide");
	});
	
	 
});

///update budgetlist_id
function uptbudgetlistid(){
	memberID = $("#mid").val(); //member id
	if ($("#budCategory").val() == "100566" && memberID == "100100146"){
		var selectbox = document.getElementById('budgerannset');
		for(var i = 0; i < selectbox.options.length; i++){
			//if(selectbox.options[i].value == '100172')
			removeBudListId = $("#offerbudlistid").val();//offerbudlistid
			removeOfferArr = removeBudListId.split(',');
			removeOfferArrLen = removeOfferArr.length;
			for(j = 0; j < removeOfferArrLen; j++){
				if (selectbox.options[i].value == removeOfferArr[j]){
					selectbox.remove(i);	
				}
			}
		}
		
		$("#offerbudState").val(1);
	}else{
		if ($("#offerbudState").val() == 1){
			var selectbox = document.getElementById("budgerannset");
			dropdlen = selectbox.options.length;
			removeBudNameId = $("#offerbudName").val();//offerbudlistid
			removeOfferNameArr = removeBudNameId.split(',');
			removeBudListId = $("#offerbudlistid").val();//offerbudlistid
			removeOfferArr = removeBudListId.split(',');
			removeOfferArrLen = removeOfferArr.length;
			for(j = 0; j < removeOfferArrLen; j++){
				//if (selectbox.options[i].value == removeOfferArr[j]){
				//	selectbox.remove(i);	
				//}
				var option = document.createElement('option');
				option.value = removeOfferArr[j];
				option.text = removeOfferNameArr[j];
				selectbox.add(option, (dropdlen+1));
				dropdlen++;
			}
			$("#offerbudState").val(0);
		}//end of if statement
	}//end else statement
}

////disable all recurring before switching to new add
function disableRecurring(){
	document.getElementById("recurring_no").value = "";
	document.getElementById("recurring_no").disabled = true;
	for (i=1;i<=6;i++){ 
		document.getElementById("recurring_ty"+i).disabled = true;
		document.getElementById("recurring_ty"+i).checked = false;
	}
	document.getElementById("on_recurring_insert").style.display ='none';
	document.getElementById("recurring1").checked = false;
	document.getElementById("recurring2").checked = true;
	$("#chRecurringLabel").text("");
}

////disable all account
function disableAllAccounts(){
	counter = document.getElementById("allActivdAcctCt").value;
	acct_ids = document.getElementById("allActiveAcct").value;

	str_val = 0;
	end_val = 9;
	for (i=0;i<counter;i++){
		temp_id = (acct_ids.toString()).substring(str_val,end_val);	
		document.getElementById("at"+temp_id).disabled = true;
		document.getElementById("c"+temp_id).disabled = true;
		document.getElementById("d"+temp_id).disabled = true;
		document.getElementById("at"+temp_id).checked = false;
		document.getElementById("c"+temp_id).checked = false;
		document.getElementById("d"+temp_id).checked = false;
		document.getElementById("da"+temp_id).value = "";
		str_val = str_val + 9;
		end_val = end_val + 9;
	}
	document.getElementById("on_acct_insert").style.display = "none";
	document.getElementById("addacct1").checked = false;
	document.getElementById("addacct2").checked = true;
}

////mx002ab::turn on recurring feature to add new recurring////
function add_recurring(value, counter){
	if (value=="yes"){
		for (i=1;i<=counter;i++){ 
			document.getElementById("recurring_ty"+i).disabled = false;
			if (i==4){
				$("#chRecurringLabel").text("Monthly");
				document.getElementById("recurring_ty"+i).checked = true;
				document.getElementById("recurring_no").disabled = false;
				document.getElementById("recurring_no").value = "2";
			}
		}
		document.getElementById("on_recurring_insert").style.display ='';
	}//end if value=="yes"
	if (value=="no"){
		document.getElementById("recurring_no").disabled = true;
		document.getElementById("recurring_no").value = "";
		for (i=1;i<=counter;i++){ 
			document.getElementById("recurring_ty"+i).disabled = true;
			document.getElementById("recurring_ty"+i).checked = false;
		}
		document.getElementById("on_recurring_insert").style.display ='none';
	}//end if value=="no"
}//end function
	
////turn off recurring display	
function turnon_no(num, value){
	document.getElementById("recurring_no").disabled = false;
	$("#chRecurringLabel").text(value);
	document.getElementById("recurring_ty"+num).checked = true;
	//$("#recurring_ty"+num).checked = true;
	return false;
}

////turn on recurring for edit
function edt_recurring(value, id){
	if (value == "all"){
		document.getElementById("on_recurring_insert").style.display = "";
	}
	if (value == "one"){
		document.getElementById("on_recurring_insert").style.display = "none";
	}
}

////turn on availables accounts to insert budget item
function insertbudget(value,counter, acct_ids){
	var i, temp_id, str_val, end_val;

	if (window.XMLHttpRequest){	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if (value == "no"){
		str_val = 0;
		end_val = 9;
		for (i=0;i<counter;i++){
			temp_id = (acct_ids.toString()).substring(str_val,end_val);	
			document.getElementById("at"+temp_id).disabled = true;
			document.getElementById("c"+temp_id).disabled = true;
			document.getElementById("d"+temp_id).disabled = true;
			document.getElementById("at"+temp_id).checked = false;
			document.getElementById("c"+temp_id).checked = false;
			document.getElementById("d"+temp_id).checked = false;
			document.getElementById("da"+temp_id).value = "";
			str_val = str_val + 9;
			end_val = end_val + 9;
		}
		document.getElementById("on_acct_insert").style.display = "none";		
	}// if value=="no"
	
	if (value == "yes"){
		if (counter > 0){
			budAmount = document.getElementById("budgetAmount").value;
			str_val = 0;
			end_val = 9;
			for (i=0;i<counter;i++){
				temp_id = (acct_ids.toString()).substring(str_val,end_val);	
				document.getElementById("at"+temp_id).disabled = false;
				if (i==0){
					document.getElementById("at"+temp_id).checked = true;
					document.getElementById("c"+temp_id).disabled = false;
					document.getElementById("d"+temp_id).disabled = false;
					document.getElementById("d"+temp_id).checked = true;
					document.getElementById("da"+temp_id).disabled = false;
					if (budAmount.substr(0,1)=="$"){
						budAmount = budAmount.substr(1);
					}
					budAmount = budAmount.replace(",","");
					if (budAmount > 0){
						document.getElementById("da"+temp_id).value = document.getElementById("budgetAmount").value;
					}else{
						document.getElementById("budgetAmount").value = "$1.00";
						document.getElementById("da"+temp_id).value = "$1.00";
					}
				}
				str_val = str_val + 9;
				end_val = end_val + 9;
			}
			document.getElementById("on_acct_insert").style.display = "";
		}else{
			document.getElementById("on_acct_insert").style.display = "";
			document.getElementById("addacct1").checked = false;
			document.getElementById("addacct2").checked = true;
		}
	}//end of value=="yes"
}//end function

////turn on/off credit/debit radio buttons for budget inserted accounts
function turn_credit_debit(acct_num, acct_ids_ct, acct_ids){
	if (document.getElementById("at"+acct_num).checked == true){
		document.getElementById("c"+acct_num).disabled = false;
		document.getElementById("d"+acct_num).disabled = false;
		document.getElementById("d"+acct_num).checked = true;
		document.getElementById("da"+acct_num).disabled = false;
		if ($("#budgetAmount").val() != ""){
			$("#da"+acct_num).val($("#budgetAmount").val());
		}else{
			$("#da"+acct_num).val("");
		}		
		$("#da"+acct_num).focus();
	}else{
		document.getElementById("c"+acct_num).checked = false;
		document.getElementById("d"+acct_num).checked = false;
		document.getElementById("c"+acct_num).disabled = true;
		document.getElementById("d"+acct_num).disabled = true;
		$("#da"+acct_num).val("");
		document.getElementById("da"+acct_num).disabled = true;		
	}
	acct_flag = 0;
	str_val = 0;
	end_val = 9;
	for (i=0;i<acct_ids_ct;i++){
		temp_id = (acct_ids.toString()).substring(str_val,end_val);	
		if (document.getElementById("at"+temp_id).checked == true){
			acct_flag += 1;
		}
		str_val = str_val + 9;
		end_val = end_val + 9;
	}
	if (acct_flag == 0){
		document.getElementById("addacct1").checked = false;
		document.getElementById("addacct2").checked = true;
		str_val = 0;
		end_val = 9;
		for (i=0;i<acct_ids_ct;i++){
			temp_id = (acct_ids.toString()).substring(str_val,end_val);	
			document.getElementById("at"+temp_id).disabled = true;
			document.getElementById("c"+temp_id).disabled = true;
			document.getElementById("d"+temp_id).disabled = true;
			document.getElementById("at"+temp_id).checked = false;
			document.getElementById("c"+temp_id).checked = false;
			document.getElementById("d"+temp_id).checked = false;
			str_val = str_val + 9;
			end_val = end_val + 9;
		}
		document.getElementById("on_acct_insert").style.display = "none";
	}else {
		document.getElementById("addacct1").checked = true;
	}
}//end function

////validate number of recurring
function isNumberNumRecurring(id,value,maxVal) {  
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Number recurring is not numeric.");
		document.getElementById(id).value = 2;
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
		return false;
	}
	if (value < 2 || value > maxVal){
		alert("Number recurring cannot be less than 2 or greater than 30.");
		document.getElementById(id).value = 2;
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
		return false;	
	}
	
	if (value > 1){
		$("#noRecurringLabel").text("times");
	}else{
		$("#noRecurringLabel").text("time");
	}
}

//retrive budget infor
function retriveBudget(memberID,aID,transactionID){
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/account/m_inc/retaccount.php",{
		  mid: memberID,
		  aid: aID,
		  transID: transactionID,
		  actState: "edit"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				tempDiv = data.indexOf("<=>");
				tempAcctCt = data.indexOf("<#>");
				
				transInfo = data.substr(0,tempDiv);
				transAcctCt = data.substr(tempDiv+3,(tempAcctCt-(tempDiv+3)));
				transInsertedAcct = data.substr(tempAcctCt+3);
				chkBudgetData(transInfo,transAcctCt,transInsertedAcct);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//parse json data
function chkBudgetData(budInfo,budAcctCt,budInsertedAcct){
	objBudInfo = JSON.parse(budInfo);
	//$("#tranID").val(objBudInfo.transID);
	$('#trans_date').val(objBudInfo.transDate);
	$('#datetimepicker1').datetimepicker('update');
	$("#budgetAmount").val(isNumber_dollar(objBudInfo.amount));
	
	if (objBudInfo.acctType == "1000"){
		$("#c"+objBudInfo.accountID).attr('checked', 'checked');
	}
	if (objBudInfo.acctType == "1001"){
		$("#d"+objBudInfo.accountID).attr('checked', 'checked');
	}
	if (objBudInfo.posted != "yes"){
		document.getElementById("posted").checked = false;
	}else{
		document.getElementById("posted").checked = true;
	}
	$("#budCategory").val(objBudInfo.categoryID);
	$("#note").val(objBudInfo.note);
	
	if (objBudInfo.annbudID == "" || objBudInfo.annbudID == 0){
		$("#budgerannset option:first").attr('selected','selected');
	}else{
		$("#budgerannset").val(objBudInfo.annbudID);
	}
	if (objBudInfo.tagID == "" || objBudInfo.tagID == 0){
		$("#setgrpcategory option:first").attr('selected','selected');
		
	}else{
		$("#setgrpcategory").val(objBudInfo.tagID);
	}
	
	$("#selecttagid").val(objBudInfo.tagID);
	$("#orgRecurringID").val(objBudInfo.recurringID);
	$("#orgGroupSetID").val(objBudInfo.groupSetID);
	$("#orgTransID").val(objBudInfo.transID);
	
	$("#attImg").text("");
	$("#imgTitle").text("");
	if (objBudInfo.img != "none"){
		document.getElementById("on_receipt_img").style.display ='';
		rece_img = "m_attach/"+objBudInfo.img;
		$("#attimg").attr("href", rece_img);
		$("#attimg").text("Receipt image");
	}else{
		document.getElementById("on_receipt_img").style.display ='none';
		$("#attimg").attr("href", "#");
		$("#attimg").removeAttr('href');
		$("#attimg").text("No receipt");
		$("#attimg").removeAttr("class")
	}
	reRecurringID = objBudInfo.recurringID;
	reGroupSetID = objBudInfo.groupSetID; //assign old groupSetID	
	tempOrgTransDate = objBudInfo.transDate;
	tempOrgAmount = objBudInfo.amount;
	tempOrgCategoryID = objBudInfo.categoryID;
	tempOrgNote = objBudInfo.note;
	
	if (objBudInfo.recurringYN=="yes"){
		//$("#recurring1").attr('checked', 'checked');
		document.getElementById("recurring1").checked = true;
		document.getElementById("recurring2").checked = false;
		$("#chRecurringLabel").text(objBudInfo.recurringType);
		document.getElementById("recurring_ty1").disabled = false;
		document.getElementById("recurring_ty2").disabled = false;
		document.getElementById("recurring_ty3").disabled = false;
		document.getElementById("recurring_ty4").disabled = false;
		document.getElementById("recurring_ty5").disabled = false;
		document.getElementById("recurring_ty6").disabled = false;
		document.getElementById("recurring_no").disabled = false;
		document.getElementById("recurring_no").value = objBudInfo.recurringNum;
		if (objBudInfo.recurringType == "Daily"){
			document.getElementById("recurring_ty1").checked = true;
		}
		if (objBudInfo.recurringType == "Weekly"){
			document.getElementById("recurring_ty2").checked = true;
		}
		if (objBudInfo.recurringType == "Bi-weekly"){
			document.getElementById("recurring_ty3").checked = true;
		}
		if (objBudInfo.recurringType == "Monthly"){
			document.getElementById("recurring_ty4").checked = true;
		}
		if (objBudInfo.recurringType == "Quarterly"){
			document.getElementById("recurring_ty5").checked = true;
		}
		if (objBudInfo.recurringType == "Yearly"){
			document.getElementById("recurring_ty6").checked = true;
		}
		document.getElementById("on_recurring_insert").style.display ='';
		document.getElementById("upt_edirecurring").style.display = "";
	}else{
		$("#chRecurringLabel").text("");
		document.getElementById("recurring_ty1").disabled = false;
		document.getElementById("recurring_ty2").disabled = false;
		document.getElementById("recurring_ty3").disabled = false;
		document.getElementById("recurring_ty4").disabled = false;
		document.getElementById("recurring_ty5").disabled = false;
		document.getElementById("recurring_ty6").disabled = false;
		document.getElementById("recurring_no").disabled = false;
		document.getElementById("recurring_no").value = "";
		document.getElementById("on_recurring_insert").style.display ='none';
		document.getElementById("upt_edirecurring").style.display = "none";
	}
	
	if (budAcctCt > 1){//turn on account
		acct_ids = $("#allActiveAcct").val(); //get all account id
		counter = $("#allActivdAcctCt").val(); //get all account count
		document.getElementById("addacct1").checked = true;
		str_val = 0;
		end_val = 9;
		for (i=0;i<counter;i++){
			temp_id = (acct_ids.toString()).substring(str_val,end_val);	
			document.getElementById("at"+temp_id).disabled = false;
			document.getElementById("c"+temp_id).disabled = true;
			document.getElementById("d"+temp_id).disabled = true;
			document.getElementById("da"+temp_id).disabled = true;
			document.getElementById("dis"+temp_id).style.display = "";//
			str_val = str_val + 9;
			end_val = end_val + 9;
		}
		document.getElementById("on_acct_insert").style.display = "";//turn on inserted accounts
		
		objBudAcct = JSON.parse(budInsertedAcct);
		for (i=0;i<budAcctCt-1;i++){
			document.getElementById("dis"+objBudAcct[i].acctID).style.display = "";//
			document.getElementById("at"+objBudAcct[i].acctID).checked = true;
			document.getElementById("at"+objBudAcct[i].acctID).disabled = false;
			if (objBudAcct[i].transTypeid == "1000"){
				document.getElementById("c"+objBudAcct[i].acctID).checked = true;
				document.getElementById("c"+objBudAcct[i].acctID).disabled = false;
				document.getElementById("d"+objBudAcct[i].acctID).disabled = false;
			}
			if (objBudAcct[i].transTypeid == "1001"){
				document.getElementById("d"+objBudAcct[i].acctID).checked = true;
				document.getElementById("d"+objBudAcct[i].acctID).disabled = false;
				document.getElementById("c"+objBudAcct[i].acctID).disabled = false;
			}
			document.getElementById("da"+objBudAcct[i].acctID).disabled = false;
			document.getElementById("da"+objBudAcct[i].acctID).value = isNumber_dollar(objBudAcct[i].acctAmt);
		}
	}
}

//retrive budget infor
function deleteAccountItems(memberID,aID,transactionID){
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/account/m_inc/retaccount.php",{
		  mid: memberID,
		  aid: aID,
		  transID: transactionID,
		  actState: "delete"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				
				tempDiv = data.indexOf("<=>");
				tempAcctCt = data.indexOf("<#>");
				
				budInfo = data.substr(0,tempDiv);
				budAcctCt = data.substr(tempDiv+3,(tempAcctCt-(tempDiv+3)));
				budInsertedAcct = data.substr(tempAcctCt+3);
				chkDeleteBudgetData(budInfo,budAcctCt,budInsertedAcct);
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//show image
function showImage(){
	$("#myModalAdd").modal("hide");
	$("#showImage").modal("show");
}

//show tage
function showtag(){
	$("#showCatTag").modal("show");
}

//parse json data
function chkDeleteBudgetData(budInfo,budAcctCt,budInsertedAcct){
	objBudInfo = JSON.parse(budInfo);
	$("#orgDelTransID").val(objBudInfo.transID);
	$('#delTrans_date').val(objBudInfo.transDate);
	$('#datetimepicker1').datetimepicker('update');
	
	$("#delBudgetAmount").val(isNumber_dollar(objBudInfo.amount));
	$("#delBudCategory").val(objBudInfo.categoryID);
	$("#delNote").val(objBudInfo.note);
	
	$("#recurringDelYN").val(objBudInfo.recurringYN);
	$("#recurringDelID").val(objBudInfo.recurringID);
	$("#recurringDelNum").val(objBudInfo.recurringNum);
	
	if (objBudInfo.acctCtFound > 1){
		$("#insertAcctDelYN").val("yes");
	}else{
		$("#insertAcctDelYN").val("no");
	}
	$("#delGroupSetID").val(objBudInfo.groupSetID);
	
	
	reRecurringID = objBudInfo.recurringID;
	reGroupSetID = objBudInfo.groupSetID;
	
	tempOrgTransDate = objBudInfo.transDate;
	tempOrgAmount = objBudInfo.amount;
	tempOrgCategoryID = objBudInfo.categoryID;
	tempOrgNote = objBudInfo.note;
		
	$("#budCategory").val(objBudInfo.categoryID);
	$("#note").val(objBudInfo.note);
	reRecurringID = objBudInfo.recurringID;
	reGroupSetID = objBudInfo.groupSetID;
	
	tempOrgTransDate = objBudInfo.transDate;
	tempOrgAmount = objBudInfo.amount;
	tempOrgCategoryID = objBudInfo.categoryID;
	tempOrgNote = objBudInfo.note;
	
	if (objBudInfo.recurringYN=="yes"){
		//$("#recurring1").attr('checked', 'checked');
		$("#delRecurringLabel").text(objBudInfo.recurringType);
		document.getElementById("delRecurring_no").disabled = true;
		document.getElementById("delRecurring_no").value = objBudInfo.recurringNum;
		if (objBudInfo.recurringType == "Daily"){
			document.getElementById("delRecurring_ty1").checked = true;
		}
		if (objBudInfo.recurringType == "Weekly"){
			document.getElementById("delRecurring_ty2").checked = true;
		}
		if (objBudInfo.recurringType == "Bi-weekly"){
			document.getElementById("delRecurring_ty3").checked = true;
		}
		if (objBudInfo.recurringType == "Monthly"){
			document.getElementById("delRecurring_ty4").checked = true;
		}
		if (objBudInfo.recurringType == "Quarterly"){
			document.getElementById("delRecurring_ty5").checked = true;
		}
		if (objBudInfo.recurringType == "Yearly"){
			document.getElementById("delRecurring_ty6").checked = true;
		}
		document.getElementById("del_recurring_insert").style.display ='';
	}else{
		document.getElementById("recurring_ty1").disabled = false;
		document.getElementById("recurring_ty2").disabled = false;
		document.getElementById("recurring_ty3").disabled = false;
		document.getElementById("recurring_ty4").disabled = false;
		document.getElementById("recurring_ty5").disabled = false;
		document.getElementById("recurring_ty6").disabled = false;
		document.getElementById("recurring_no").disabled = false;
		document.getElementById("recurring_no").value = "";
		document.getElementById("del_recurring_insert").style.display ='none';
	}
}