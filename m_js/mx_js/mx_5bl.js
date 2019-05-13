
$(document).ready(function(){
	//add new budget
	$("#addNewBudget").click(function(){
		var today = new Date();
		today = todayDate();
		$("#myModalAdd").modal("show");	
		$("#eBudType").text("Add Budget");
		$("#state").val("add");
		$("#budName").val("");
		$("#amount").val("");
		$("#budset_date").val(today);
		//$("#budgetsheet").val();
		$("#description").val("");
		$("#edtBudID").val("");
	});
	
	$("#insertNewBudget").click(function(){
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
			if ($("#budset_date").val() == ""){//date validation
				$("#budset_date").val(today);
			}
		}
		mid = $("#mid").val();
		budName = $("#budName").val();
		budName = budName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		setAmount = $("#amount").val();
		if (setAmount.substr(0,1)=="$"){
			setAmount = setAmount.substr(1);
		}
		setAmount = setAmount.replace(",","");
		setdate = $("#budset_date").val();
		budDesc = $("#description").val();
		budDesc = budDesc.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		budgetSheetID = $("#budgetsheet").val();
		state = $("#state").val();
		
		if($("#budactivey").is(":checked")){
			activeyn = "yes";		
		}else{
			activeyn = "no";
		}
		
		budID = $("#edtBudID").val();
			
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newbudgetlist.php",{
			  mid: mid,
			  activeyn: activeyn,
			  budName: budName,
			  setAmount: setAmount,
			  setdate: setdate,
			  budgetSheetID: budgetSheetID,
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
});

//update budget info
function uptBudgetInfo(budgetID){
	$("#edtBudID").val("");
	$("#budName").val("");
	$("#description").val("");
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newbudgetlist.php",{
		  mid: mid,
		  budID: budgetID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eBudType").text("Update Budget");
				$("#state").val("edit");
				objBudInfo = JSON.parse(data);
				$("#edtBudID").val(objBudInfo.id);
				$("#budgetsheet").val(objBudInfo.bid);
				$("#budName").val(objBudInfo.name);
				$("#amount").val(isNumber_dollar(objBudInfo.amount));
				$('#budset_date').val(objBudInfo.date);
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

//delete budget info
function delBudgetInfo(budgetID){
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newbudgetlist.php",{
		  mid: mid,
		  budID: budgetID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eBudType").text("Delete Budget");
				$("#state").val("delete");
				objBudInfo = JSON.parse(data);
				$("#edtBudID").val(objBudInfo.id);
				$("#budgetsheet").val(objBudInfo.bid);
				$("#budName").val(objBudInfo.name);
				$("#amount").val(isNumber_dollar(objBudInfo.amount));
				$('#budset_date').val(objBudInfo.date);
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

//update fiscal month
function uptFiscalMon(orgID,memberID){
	incomeSubmit = "yes"
	if (incomeSubmit == "yes"){
		$.post("m_momax/setting/m_inc/uptfiscal.php",{
		  memberID: memberID,
		  orgID: orgID,
		  startMon: $("#fiscalmon").val(),
		  state: "update" 
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				location.reload();
				alert("Fiscal start month has been updated.");
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//reorder list
function editacctlist(num,budgetID,listorder,mid){
	if (num == 1){
		document.getElementById("olist_view"+budgetID).style.display ='none';
		document.getElementById("olist_edit"+budgetID).style.display ='';
	}
	if (num == 2){
		newListOrder = document.getElementById("od"+budgetID).value;
		if (!(!isNaN(parseFloat(newListOrder)) && isFinite(newListOrder))){
			alert("List order is not numeric.");
			document.getElementById("od"+budgetID).value = "";
			setTimeout(function() { document.getElementById("od"+budgetID).focus(); }, 10);
			incomeSubmit = "no";
			return false;
			
		}else{
			incomeSubmit = "yes";
		}
		if (incomeSubmit == "yes"){
			$.post("m_momax/setting/m_inc/newbudgetlist_order.php",{
			  mid: mid,
			  budID: budgetID,
			  listOrd: newListOrder,
			  state: "edit"
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
	}
}	

//set accounts for annual budget
function setAnnualBudget(accountID,setFlag){
	
	mid = $("#mid").val();	
	if($("#y"+accountID).is(":checked")){
		budgetYN = "yes";	
	}
	if($("#n"+accountID).is(":checked")){
		budgetYN = "no";	
	}
	state = "setbudget";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newbudgetlist_order.php",{
		  mid: mid,
		  accountID: accountID,
		  budgetYN: budgetYN,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if($("#y"+accountID).is(":checked")){
					alert("Account has been added to the annual budget.");	
				}else{
					alert("Account has been removed from the annual budget.");
				}
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	
}