	
$(document).ready(function(){
	 $("#addProjItem").click(function () {
		if ($("#itemState").val() == "new"){
			incomeSubmit = "yes"; //control flag
			state = "new";
			projectID = $("#projectID").val();
			pdetailID = "";
			itVal = $("#projItem").val();
			itAmt = $("#projAmount").val(); 
			itNote = $("#note").val();
			budlistit = $("#budgerannset").val();
			if (document.getElementById("pdone").checked == true){
				pdone = "yes";
			}else{
				pdone = "no";
			}
			if (itVal == ""){
				alert("Please enter item name");
				setTimeout(function() { document.getElementById("projItem").focus(); }, 10);
				incomeSubmit = "no"; //control flag
				return false;
			}
			if (itAmt == ""){
				alert("Please enter amount");
				setTimeout(function() { document.getElementById("projAmount").focus(); }, 10);
				incomeSubmit = "no"; //control flag
				return false;
			}
		}
		if ($("#itemState").val() == "update"){
			incomeSubmit = "yes"; //control flag
			state = "update"; 
			projectID = $("#projectID").val();
			pdetailID = $("#itemID").val();
			itVal = $("#projItem").val();
			itAmt = $("#projAmount").val(); 
			itNote = $("#note").val();
			budlistit = $("#budgerannset").val();
			if (document.getElementById("pdone").checked == true){
				pdone = "yes";
			}else{
				pdone = "no";
			}
			
			if (itVal == ""){
				alert("Please enter item name");
				setTimeout(function() { document.getElementById("projItem").focus(); }, 10);
				incomeSubmit = "no"; //control flag
				return false;
			}
			if (itAmt == ""){
				alert("Please enter amount");
				setTimeout(function() { document.getElementById("projAmount").focus(); }, 10);
				incomeSubmit = "no"; //control flag
				return false;
			}
		}
		if ($("#itemState").val() == "delete"){
			incomeSubmit = "yes"; //control flag
			state = "delete"; 
			projectID = $("#projectID").val();
			pdetailID = $("#itemID").val();
			itVal = $("#projItem").val();
			itAmt = $("#projAmount").val(); 
			itNote = $("#note").val();
			budlistit = $("#budgerannset").val();
			pdone = "";
		}

		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/project/m_inc/uptprojectdetail.php",{
			  projID: projectID,
			  mid: $("#mid").val(), //member id
			  pdid: pdetailID,
			  itemName: itVal,
			  itemAmt: itAmt,
			  itemNote: itNote,
			  budlistit: budlistit,
			  pdone: pdone,
			  actState: state
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					$("#myModalAdd").modal("hide");
					location.reload();
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
     });
	 
	 $("#addProject").click(function(){
			incomeSubmit = "yes";
			
			if ($("#trans_date").val() == ""){//date validation
				alert("Enter date");
				$("#trans_date").focus();
				incomeSubmit = "no";
				return false;
			}
			if ($("#projectName").val() == ""){//date validation
				alert("Enter event name");
				$("#projectName").focus();
				incomeSubmit = "no";
				return false;
			}
			if ($("#budgetAmount").val() == "" && incomeSubmit == "yes"){//amount validation
				alert("Enter at least one item.");
				$("#budgetAmount").focus();
				incomeSubmit = "no";
				return false;
			}
	
			// check for account info
			if ($("#addacct1").is(":checked") == true){
				accountInsertYes = $("#addacct1").val();
				counter = $("#allActivdAcctCt").val(); 
				acct_ids = $("#allActiveAcct").val(); 
			
				addAccountsTxt = "[";
				amountTot = 0;
				acctSelectCt = 0;
				strVal = 0;
				endVal = 9;
				commaChk = 1;
				editChangeAcct = [];
				for (i=1;i<=counter;i++){
					tempID = (acct_ids.toString()).substring(strVal,endVal);	//single out each account
					if ($("#at"+tempID).is(":checked") == true){ //check to make sure the account is checked
						accountOne = $("#da"+tempID).val(); //adding total inserted account dollars
						chooseOneAccount = "#da"+tempID; //choose one account to display when amountTot is not equal to enter amount
						if (accountOne.substr(0,1)=="$"){ //remove $ sign
							accountOne = accountOne.substr(1); //keep just the amount
						}
						accountOne = accountOne.replace(",",""); //remove an comma
						amountTot = amountTot + Number(accountOne); //add subtotl					

						if (commaChk>1){ //set flag for single inserted account record
							addAccountsTxt = addAccountsTxt+','; //add common for the next dataset
						}
						commaChk += 1; //increase flag for more inserted account record
						
						acctSelectCt += 1; //count all selected account
						addAccountsTxt = addAccountsTxt+'{"acctID":"'+$("#at"+tempID).val()+'",'; //create accountID json data
						if ($("#c"+tempID).is(":checked") == true){
							addAccountsTxt = addAccountsTxt+'"acctType":"'+$("#c"+tempID).val()+'",'; //create credit json data
						}
						if ($("#d"+tempID).is(":checked") == true){
							addAccountsTxt = addAccountsTxt+'"acctType":"'+$("#d"+tempID).val()+'",'; //create debit json data
						}
						addAccountsTxt = addAccountsTxt+'"acctAmt":"'+accountOne+'"}';				
					}//end of inserted account is checked 
					strVal = strVal + 9;
					endVal = endVal + 9;
				}
				
				addAccountsTxt = addAccountsTxt+"]"; //close JSON data for all inserted accounts

				amountEnter= $("#budgetAmount").val();
				if (amountEnter.substr(0,1)=="$"){
					amountEnter = amountEnter.substr(1);
				}
				amountEnter = Number(amountEnter.replace(",","")).toFixed(2);
				amountTot = amountTot.toFixed(2);
				if (amountEnter != amountTot){
					alert("Total tracking amount is not the same as total amount!");
				}
			}else{ //no inserted account found
				accountInsertYes = ""; //no account inserted
				counter = "";
				acct_ids = "";
				addAccountsTxt = ""; //no inserted account json data
				acctSelectCt = 0; //no number of inserted account count
			} //end of else - inserted accounts
			
			amountEnter= $("#budgetAmount").val();
			if (amountEnter.substr(0,1)=="$"){//remove $ and common
				amountEnter = amountEnter.substr(1);
			}
			amountEnter = Number(amountEnter.replace(",","")).toFixed(2);
			
			$selectBudgetID = "0";
			$defaultBudgetType = "0";
			if ($("#addacct2").is(":checked") == true){
				counter = ""; 
				acct_ids = "";	
			}
			budlistit = $("#budgetlist").val();
			
			//posting budget data
			if (incomeSubmit == "yes"){
				$.post("m_momax/project/m_inc/uptproject.php",{
				  //budState: $("#budState").val(), //budget state
				  
				  mid: $("#mid").val(), //member id
				  projID: $("#projectid").val(), //project id
				  budid: $selectBudgetID, //budget id
				  budTypeid: $defaultBudgetType, //budget type id
				  transDate: $("#trans_date").val(), //transaction date
				  projName: $("#projectName").val(),
				  amount: amountEnter, //amount
				  category: 100100, //selected category
				  note: $("#description").val(), //note
				  budlistit: budlistit,
				  
				  accountYN: accountInsertYes, //yes/no for inserted accounts
				  accountCt: counter, //total number of available accounts
				  accountID: acct_ids, //all account id combined together
				  accountInfo: addAccountsTxt, //json data of selected account {acctID:acctType:chgOnAcct:acctAmt}
				  accountSelectCt: acctSelectCt //total number of selected accounts				  
				},
				function(data,status){
					if (status == "success"){
						//$msg = data.indexOf("result==>"); //get the position of data status
						//lert("Data: " + data.substring((Number($msg)+8),(Number($msg)+8)+7) + "\nStatus: " + status);
						//alert("Data: " + data + "\nStatus: " + status);
						location.reload();
					}else{
						alert("System is ecountering issue and cannot save data at this point.");
					}
				});
			}
		});
	
  });

////turn on project item textbox
function turnOnTxtBox(pdetailID, projItem, projAmt, projNote,val,doneVal,budlistid){
	incomeSubmit = "no"; //control flag
	if (val == 1){ 
		$("#projItem").val("");
		$("#projAmount").val("");
		$("#note").val("");
		if ($("#budgetlist").val() == 0){
			document.getElementById("show_budlist").style.display = "";
		}else{
			document.getElementById("show_budlist").style.display = "none";
		}
		$("#budgerannset").val("0");
		$("#myModalAdd").modal("show");
		$("#aTitle").text("Add Event Item");
		$("#aValType").text("Save");
		$("#itemState").val("new");
		$("#itemID").val("");
		$("#pdone").val("");
		itVal = "";
		itAmt = ""; 
		itNote = "";
	}
	if (val == 2){
		$("#myModalAdd").modal("show");
		$("#aTitle").text("Edite Event Item");
		$("#aValType").text("Save");
		$("#projItem").val(projItem);
		$("#projAmount").val(projAmt);
		$("#note").val(projNote);
		if ($("#budgetlist").val() == 0){
			document.getElementById("show_budlist").style.display = "";
			$("#budgerannset").val(budlistid);
		}else{
			document.getElementById("show_budlist").style.display = "none";
			$("#budgerannset").val("0");
		}
		
		$("#itemState").val("update");
		$("#itemID").val(pdetailID);
		if (doneVal == 1){
			document.getElementById("pdone").checked = true;
		}else{
			document.getElementById("pdone").checked = false;
		}
		itVal = projItem;
		itAmt = projAmt; 
		itNote = projNote;
	}
	if (val == 3){
		$("#myModalAdd").modal("show");
		$("#aTitle").text("Delete Event Item");
		$("#aValType").text("Delete");
		$("#projItem").val(projItem);
		$("#projAmount").val(projAmt);
		$("#note").val(projNote);
		$("#itemState").val("delete");
		$("#itemID").val(pdetailID);
		if (doneVal == 1){
			document.getElementById("pdone").checked = true;
		}else{
			document.getElementById("pdone").checked = false;
		}
		itVal = projItem;
		itAmt = projAmt; 
		itNote = projNote;
	}
}

/////////project form
function validateForm_proj(counter,acct_ids){
	var acct_flag;
	acct_flag = 0;
	
	if (document.project_form.name.value==null || document.project_form.name.value==""){
		alert("Event name is needed.");
		document.project_form.name.focus();
		return false;
	}
	if (document.project_form.proj_start_date.value==null || document.project_form.proj_start_date.value==""){
		alert("Start date is needed.");
		document.project_form.proj_start_date.focus();
		return false;
	}
	if (document.project_form.addacct[1].checked == true){
		for (i=0;i<counter;i++){
			if (document.project_form.account_type_id[i].checked == true){	
				acct_flag += 1;
			}
		}
	}
	if (acct_flag == 0 && document.project_form.addacct[1].checked == true){
		alert("Select an account.");
		document.project_form.account_type_id[0].focus();
		return false;
	}
	if (acct_flag > 0 && document.project_form.addacct[1].checked == true){
		count_val = 0;
		trans_flag = 0;
		for (i=0;i<counter;i++){
			acct_val = acct_ids.substr(count_val, 6);
			if (document.getElementById("c"+acct_val).checked == true){	
				trans_flag += 1;	
			}
			if (document.getElementById("d"+acct_val).checked == true){	
				trans_flag += 1;	
			}
			count_val = count_val + 6;
		}
		
		if (trans_flag == 0){
			alert("Select credit or debit on account.");
			return false;
		}
	}
	var count_no, cur_item_ct, more_info;
	cur_item_ct = parseInt(document.getElementById("item_ct").value);
	count_no = 100;
	more_info = "yes";
	for(i=0;i<=cur_item_ct;i++){
		if (document.getElementById("a"+count_no)){
			if ((document.getElementById("i"+count_no).value).length<=0){
				alert("Please enter item");
				setTimeout(function() { document.getElementById("i"+count_no).focus(); }, 10);
				more_info = "no";
				break;
			}
		}
		count_no += 1;
	}
	if (more_info == "no"){
		return false;
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

////
function editbudget(value,counter){
	var i;
	if (value == 0){
		if (counter > 1){
			for (i=0;i<counter;i++){ 
				document.project_form.account_type_id[i].disabled = true;
				document.project_form.account_type_id[i].checked = false;
			}
		}else{
			document.project_form.account_type_id.disabled = true;
			document.project_form.account_type_id.checked = false;
		}
		document.getElementById("transaction_type").disabled = true;
		document.getElementById("transaction_type").value="";
		document.getElementById("on_account_insert1").style.display = "none";
		document.getElementById("on_account_insert2").style.display = "none";
		document.getElementById("on_account_insert3").style.display = "none";
	}
	if (value == 1){
		if (counter > 1){
			for (i=0;i<counter;i++){ 
				document.project_form.account_type_id[i].disabled = false;
			}
		}else{
			document.project_form.account_type_id.disabled = false;
		}
		document.getElementById("on_account_insert1").style.display = "";
		document.getElementById("on_account_insert2").style.display = "";
		document.getElementById("on_account_insert3").style.display = "";
	}
}
function budget_turnon_trans(){
	document.getElementById("transaction_type").disabled = false;
}
function addTextbox(id, bud_sec){ 
	var newid;
	newid = id.substring(2);
	
	document.getElementById(id).setAttribute('id','it'+(parseInt(newid)+1));
	newid = (parseInt(newid)+1);
	var newdiv1 = document.createElement('div');
	var newdiv2 = document.createElement('div');
	var newdiv3 = document.createElement('div');
	newdiv1.setAttribute('id','d1'+newid);
	newdiv2.setAttribute('id','d2'+newid);
	newdiv3.setAttribute('id','d3'+newid);
	var divArea1 = document.getElementById('divToAddTextBox1');
	var divArea2 = document.getElementById('divToAddTextBox2');
	var divArea3 = document.getElementById('divToAddTextBox3');
	
	newdiv1.innerHTML = "<input type='text' size='25' name='i"+newid+"' id='i"+newid+"' /><br><br>";
	newdiv2.innerHTML = "<input type='text' value='$0.00' size='5' name='a"+newid+"' id='a"+newid+"' onchange='isNumber_item_amt(this.value, this.id)' /><br><br>";
	if (bud_sec==1){
		newdiv3.innerHTML = "<input type='text' size='20' name='n"+newid+"' id='n"+newid+"' /> <a href=javascript:del_item('"+newid+"')>Delete</a><br><br>";
	}else {
		newdiv3.innerHTML = "<input type='text' size='20' name='n"+newid+"' id='n"+newid+"' /><br><br>";
	}
	divArea1.appendChild(newdiv1);
	divArea2.appendChild(newdiv2);
	divArea3.appendChild(newdiv3);
					
	document.getElementById("idName").value = newid;
	document.getElementById("item_ct").value = parseInt(document.getElementById("item_ct").value) + 1;
}

//check numeric value for add new account transaction 
function isNumber_item_amt(value, id) {  
	var item_tot, cur_item_ct, count_no, known_cost, item_val, id_num;
	item_tot = 0;
	if (value.substr(0,1)=="$"){
		value = value.substr(1);
	}
	if (!(!isNaN(parseFloat(value)) && isFinite(value) && value>=0)){
		alert("Amount is not numeric.");
		document.getElementById(id).value = "$0.00";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
		return false;
	}else {
		cur_item_ct = parseInt(document.getElementById("item_ct").value);
		count_no = 100;
		for(i=0;i<=cur_item_ct;i++){
			if (i>0){
				count_no += 1;
			}
			if (document.getElementById("a"+count_no)){
				item_val = document.getElementById("a"+count_no).value;
				if (item_val.substr(0,1)=="$"){
					item_val = item_val.substr(1);
				}
				item_val = item_val.replace(',','');
				item_tot = Number(item_tot) + Number(item_val);
			}
		}
		document.getElementById("tot_item").value = item_tot;
		document.getElementById("tot_item_dis").innerHTML = "&nbsp;&nbsp;"+formatCurrency(item_tot);
	}
	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./)){ 
		val += '.00'; }
	if (!val.match(/\.\d\d/)){ 
		val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	document.getElementById(id).value = '$'+ val;
	id_num = id.substr(1);
	if (document.getElementById("i"+id_num).value == ""){
		alert("Please enter item.");
		setTimeout(function() { document.getElementById("i"+id_num).focus(); }, 10);
	}
}
function del_item(id){
	var count_no, cur_item_ct, item_tot, item_val;
	var cur_item, cur_amount, cur_note, no_value;
	cur_item = document.getElementById('i'+id).value;
	cur_amount = document.getElementById('a'+id).value;
	cur_note = document.getElementById('n'+id).value;
	no_value = "no"
	if (cur_item=="" && cur_note=="" && (cur_amount=="" || cur_amount=="$0.00")){
		no_value = "go";
	}else {
		if(confirm("Confirm delete budget item?")){
			no_value = "go";
		}
	}
	if(no_value == "go"){
		var divArea1 = document.getElementById('d1'+id);
		var divArea2 = document.getElementById('d2'+id);
		var divArea3 = document.getElementById('d3'+id);
		divArea1.parentNode.removeChild(divArea1);
		divArea2.parentNode.removeChild(divArea2);
		divArea3.parentNode.removeChild(divArea3);
		
		cur_item_ct = parseInt(document.getElementById("item_ct").value);
		count_no = 100;
		item_tot = 0;
		for(i=0;i<=cur_item_ct;i++){
			if (i>0){
				count_no += 1;
			}
			if (document.getElementById("a"+count_no)){
				item_val = document.getElementById("a"+count_no).value;
				if (item_val.substr(0,1)=="$"){
					item_val = item_val.substr(1);
				}
				item_val = item_val.replace(',','');
				item_tot = Number(item_tot) + Number(item_val);
			}
		}
		document.getElementById("tot_item").value = item_tot;
		document.getElementById("tot_item_dis").innerHTML = "&nbsp;&nbsp;"+formatCurrency(item_tot);
	}
}
function del_proj(value){
	var chg_name;
	if (value=="yes"){
		if(confirm("Confirm to delete the entire event?")){
			chg_name = document.getElementById("proj_submit");
			chg_name.value = "Delete Project";
		}
	}
	if (value=="no"){
		chg_name = document.getElementById("proj_submit");
		chg_name.value = "Save Project";
	}
}
function display_ct(){
	var count_no, cur_item_ct;
	cur_item_ct = parseInt(document.getElementById("item_ct").value);
	alert(document.getElementById("item_ct").value);
	count_no = 100;
	for(i=0;i<=cur_item_ct;i++){
		if (document.getElementById("a"+count_no)){
			if ((document.getElementById("i"+count_no).value).length>0){
				alert(document.getElementById("i"+count_no).value+":::"+document.getElementById("a"+count_no).value);
			}
		}
		count_no += 1;
	}
}