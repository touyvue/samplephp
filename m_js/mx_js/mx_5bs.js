
$(document).ready(function(){
	//add new budget
	$("#addNewBudget").click(function(){
		$("#myModalAdd").modal("show");	
		$("#eBudType").text("Add Budget");
		$("#state").val("add");
		$("#budName").val("");
		$("#description").val("");
		$("#edtBudID").val("");
	});
	
	$("#insertNewBudget").click(function(){
		if ($("#state").val() != "delete"){
			if ($("#budName").val() == ""){//date validation
				alert("Enter budget name");
				$("#budName").focus();
				return false;
			}
		}
		mid = $("#mid").val();
		budName = $("#budName").val();
		budName = budName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		budDesc = $("#description").val();
		budDesc = budDesc.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		state = $("#state").val();;
		
		budID = $("#edtBudID").val();
			
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newbudget.php",{
			  mid: mid,
			  budName: budName,
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
		$.post("m_momax/setting/m_inc/newbudget.php",{
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
				$("#budName").val(objBudInfo.name);
				$("#description").val(objBudInfo.desc);				
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
		$.post("m_momax/setting/m_inc/newbudget.php",{
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
				$("#budName").val(objBudInfo.name);
				$("#description").val(objBudInfo.desc);				
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
			$.post("m_momax/setting/m_inc/newbudget_order.php",{
			  mid: mid,
			  budID: budgetID,
			  listOrd: newListOrder,
			  state: "editlist"
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

//update active status
function uptActiveStatus(budgetID,mid,status){
	incomeSubmit = "yes"
	if (status == 0){
		chgStatus = "yes";
	}else{
		chgStatus = "no";
	}
	if (incomeSubmit == "yes"){
		$.post("m_momax/setting/m_inc/newbudget_order.php",{
		  mid: mid,
		  budID: budgetID,
		  chgStatus: chgStatus,
		  state: "updatesta" 
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				location.reload();
				alert("Budget status has been updated.");
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//set budget to display on Dashboard
function setAnnualBudget(budgetID,setFlag){	
	mid = $("#mid").val();
	chartYN = "no";
	if($("#chy"+budgetID).is(":checked")){
		chartYN = "yes";	
	}
	state = "setchart";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newbudgetlist_order.php",{
		  mid: mid,
		  budgetID: budgetID,
		  chartYN: chartYN,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "over"){
					alert("Only 3 Budget charts are allowed");
				}else{
					alert("Budget has been updated.");	
				}
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	
}