//add
function addSpcBud(memberID,budgetID,type){
	var today = new Date();
	today = todayDate();
	
	$("#eSpcBudType").text("Add Budget");
	$("#state").val("add");
	$("#budName").val("");
	$("#amount").val("");
	$("#budset_strdate").val(today);
	$('#datetimepicker').datetimepicker('update');
	$("#budset_enddate").val(today);
	$('#datetimepicker1').datetimepicker('update');
	$("#budgetsheetID").val(budgetID);
	$("#description").val("");
	$("#edtBudID").val("");
	
	if (type == 1){
		$("#budgetsheetTypeId").val("1002");
		$("#annmon").val("ann");
	}
	if (type == 2){
		$("#budgetsheetTypeId").val("1000");
		$("#annmon").val("mon");
	}
	if (type == 3){
		$("#budgetsheetTypeId").val("1002");
		$("#annmon").val("mon");
	}
	//alert($("#budgetsheetID").val() +"="+ $("#budgetsheetTypeId").val());
	$("#mySpcModalAdd").modal("show");	
	
}

//update budget info
function uptSpcBudgetInfo(budgetID){
	$("#edtBudID").val("");
	$("#budName").val("");
	$("#description").val("");
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/budget/m_inc/newbudgetlist.php",{
		  mid: mid,
		  budID: budgetID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#mySpcModalAdd").modal("show");
				$("#eSpcBudType").text("Update Budget");
				$("#state").val("edit");
				objBudInfo = JSON.parse(data);
				$("#edtBudID").val(objBudInfo.id);
				$("#budgetsheetID").val(objBudInfo.bid);
				$("#budName").val(objBudInfo.name);
				$("#amount").val(isNumber_dollar(objBudInfo.amount));
				$('#budset_strdate').val(objBudInfo.strdate);
				$('#datetimepicker').datetimepicker('update');
				$('#budset_enddate').val(objBudInfo.enddate);
				$('#datetimepicker1').datetimepicker('update');
				budDesc = objBudInfo.desc.replace(/<!>/g,'\n');
				$("#description").val(budDesc);	
				if (objBudInfo.act == "yes"){
					$("#budactivey").attr('checked',true);
				}else{
					$("#budactiven").attr('checked',true);
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//delete budget info
function delBudgetInfo(budgetID){
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/budget/m_inc/newbudgetlist.php",{
		  mid: mid,
		  budID: budgetID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#mySpcModalAdd").modal("show");
				$("#eSpcBudType").text("Delete Budget");
				$("#state").val("delete");
				objBudInfo = JSON.parse(data);
				$("#edtBudID").val(objBudInfo.id);
				$("#budgetsheetID").val(objBudInfo.bid);
				$("#budName").val(objBudInfo.name);
				$("#amount").val(isNumber_dollar(objBudInfo.amount));
				$('#budset_strdate').val(objBudInfo.strdate);
				$('#datetimepicker').datetimepicker('update');
				$('#budset_enddate').val(objBudInfo.enddate);
				$('#datetimepicker1').datetimepicker('update');
				$("#description").val(objBudInfo.desc);		
				if (objBudInfo.act == "yes"){
					$("#budactivey").attr('checked',true);
				}else{
					$("#budactiven").attr('checked',true);
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

////Add and edit income and expense 
//log_budget/
$(document).ready(function(){
	
	$("#insertSpcNewBudget").click(function(){
		var today = new Date();
		today = todayDate();
		
		if ($("#state").val() != "delete"){
			if ($("#budName").val() == ""){//date validation
				alert("Please enter budget name");
				document.getElementById("budName").value = "";
				setTimeout(function() { document.getElementById("budName").focus(); }, 10);
				$("#budName").focus();
				return false;
			}
			if ($("#amount").val() == ""){//date validation
				alert("Please enter amount");
				document.getElementById("amount").value = "";
				setTimeout(function() { document.getElementById("amount").focus(); }, 10);
				$("#amount").focus();
				return false;
			}
			if ($("#budset_strdate").val() == ""){//date validation
				$("#budset_strdate").val(today);
			}
			if ($("#budset_enddate").val() == ""){//date validation
				$("#budset_enddate").val(today);
			}
			
			var stDate = new Date($("#budset_strdate").val());
			var edDate = new Date($("#budset_enddate").val());
			if (stDate > edDate){
				alert("End Date cannot be before Start Date.");
				return false;
			}
		}

		mid = $("#mid").val();
		budName = $("#budName").val();
		budName = budName.replace(/"/g,'');
		//budName = budName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		setAmount = $("#amount").val();
		if (setAmount.substr(0,1)=="$"){
			setAmount = setAmount.substr(1);
		}
		setAmount = setAmount.replace(",","");
		strdate = $("#budset_strdate").val();
		enddate = $("#budset_enddate").val();
		budDesc = $("#description").val();
		budDesc = budDesc.replace(/"/g,'');
		budDesc = budDesc.replace(/(\r\n|\n|\r)/gm,"<!>"); //remove all linebreaks
		budgetSheetID = $("#budgetsheetID").val();
		budgetSheetTypeID = $("#budgetsheetTypeId").val();
		annmon = $("#annmon").val();
		state = $("#state").val();
		
		if($("#budactivey").is(":checked")){
			activeyn = "yes";		
		}else{
			activeyn = "no";
		}
		
		budID = $("#edtBudID").val();
		
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/budget/m_inc/newbudgetlist.php",{
			  mid: mid,
			  activeyn: activeyn,
			  budName: budName,
			  setAmount: setAmount,
			  strdate: strdate,
			  enddate: enddate,
			  budgetSheetID: budgetSheetID,
			  budgetSheetTypeID: budgetSheetTypeID,
			  annmon: annmon,		
			  budDesc: budDesc,
			  budID: budID,
			  state: state 
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					$("#myModalAdd").modal("hide");
					location.reload();
					$("#state").val("");
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	});
	
	//$("#closeIncome").click(function(){
		//if(confirm('Close without saving?')){
			//$("#myModalAdd").modal("hide");
			//location.reload();
		//}
	//});
	
});