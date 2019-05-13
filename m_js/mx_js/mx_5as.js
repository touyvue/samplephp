
$(document).ready(function(){
	//add new account
	$("#addNewAccount").click(function(){
		$("#myModalAdd").modal("show");	
		$("#eActType").text("Add Account");
		$("#eActTit").val("Account");
		$("#state").val("add");
		$("#acctName").val("");
		$("#description").val("");
		$("#edtAcctID").val("");
	});
	
	$("#insertNewAccount").click(function(){
		if ($("#state").val() != "delete"){
			if ($("#acctName").val() == ""){//date validation
				alert("Enter account name");
				$("#acctName").focus();
				return false;
			}
		}
		mid = $("#mid").val();
		acctName = $("#acctName").val();
		acctName = acctName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		acctDesc = $("#description").val();
		acctDesc = acctDesc.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		state = $("#state").val();;
		
		acctID = $("#edtAcctID").val();
			
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newaccount.php",{
			  mid: mid,
			  acctName: acctName,
			  acctDesc: acctDesc,
			  acctID: acctID,
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

//update account info
function uptAccountInfo(accountID){
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newaccount.php",{
		  mid: mid,
		  acctID: accountID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eActType").text("Update Account");
				$("#eActTit").val("Account");
				$("#state").val("edit");
				objAcctInfo = JSON.parse(data);
				$("#edtAcctID").val(objAcctInfo.id);
				$("#acctName").val(objAcctInfo.name);
				$("#description").val(objAcctInfo.desc);				
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//delete account info
function delAccountInfo(accountID){
	//alert(accountID);
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newaccount.php",{
		  mid: mid,
		  acctID: accountID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eActType").text("Delete Account");
				$("#eActTit").val("Account");
				$("#state").val("delete");
				objAcctInfo = JSON.parse(data);
				$("#edtAcctID").val(objAcctInfo.id);
				$("#acctName").val(objAcctInfo.name);
				$("#description").val(objAcctInfo.desc);				
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//reorder list
function editacctlist(num,accountID,listorder,mid){
	if (num == 1){
		document.getElementById("olist_view"+accountID).style.display ='none';
		document.getElementById("olist_edit"+accountID).style.display ='';
	}
	if (num == 2){
		newListOrder = document.getElementById("od"+accountID).value;
		if (!(!isNaN(parseFloat(newListOrder)) && isFinite(newListOrder))){
			alert("List order is not numeric.");
			document.getElementById("od"+accountID).value = "";
			setTimeout(function() { document.getElementById("od"+accountID).focus(); }, 10);
			incomeSubmit = "no";
			return false;
			
		}else{
			incomeSubmit = "yes";
		}
		if (incomeSubmit == "yes"){
			$.post("m_momax/setting/m_inc/newaccount_order.php",{
			  mid: mid,
			  acctID: accountID,
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
	budgetYN = "no";
	chartYN = "no";
	if($("#y"+accountID).is(":checked")){
		budgetYN = "yes";	
	}
	if($("#chy"+accountID).is(":checked")){
		chartYN = "yes";	
	}
	state = "setbudget";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newbudgetlist_order.php",{
		  mid: mid,
		  accountID: accountID,
		  budgetYN: budgetYN,
		  chartYN: chartYN,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				alert("Account has been updated.");	
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
	
}