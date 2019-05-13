
$(document).ready(function(){
	//add new account
	$("#addNewTag").click(function(){
		$("#myModalAdd").modal("show");	
		$("#eActType").text("Add Tag");
		$("#eActTit").val("Tag");
		$("#state").val("add");
		$("#tagName").val("");
		$("#description").val("");
		$("#edtTagID").val("");
	});
	
	$("#insertNewTag").click(function(){
		if ($("#state").val() != "delete"){
			if ($("#tagName").val() == ""){//date validation
				alert("Enter tag name");
				$("#tagName").focus();
				return false;
			}
		}
		mid = $("#mid").val();
		tagName = $("#tagName").val();
		tagName = tagName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		tagDesc = $("#description").val();
		tagDesc = tagDesc.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		state = $("#state").val();
		if (document.getElementById("activeTag1").checked == true){
			active = "yes";
		}else{
			active = "no";
		}
		
		if ($("#edtTagID").val() == ""){
			tagID = "";
		}else{
			tagID = $("#edtTagID").val();
		}
		
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newtag.php",{
			  mid: mid,
			  tagName: tagName,
			  tagDesc: tagDesc,
			  tagID: tagID,
			  active: active,
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
function uptTagInfo(tagID){
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newtag.php",{
		  mid: mid,
		  tagID: tagID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eActType").text("Update Tag");
				$("#eActTit").val("Tag");
				$("#state").val("edit");
				objTagInfo = JSON.parse(data);
				$("#edtTagID").val(objTagInfo.id);
				$("#tagName").val(objTagInfo.name);
				$("#description").val(objTagInfo.desc);	
				if (objTagInfo.act == "yes"){
					document.getElementById("activeTag1").checked = true;
					document.getElementById("activeTag2").checked = false;
				}else{
					document.getElementById("activeTag1").checked = false;
					document.getElementById("activeTag2").checked = true;
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//delete account info
function delTagInfo(tagID){
	//alert(accountID);
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newtag.php",{
		  mid: mid,
		  tagID: tagID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eActType").text("Delete Tag");
				$("#eActTit").val("Tag");
				$("#state").val("delete");
				objTagInfo = JSON.parse(data);
				$("#edtTagID").val(objTagInfo.id);
				$("#tagName").val(objTagInfo.name);
				$("#description").val(objTagInfo.desc);	
				if (objTagInfo.act == "yes"){
					document.getElementById("activeTag1").checked = true;
					document.getElementById("activeTag2").checked = false;
				}else{
					document.getElementById("activeTag1").checked = false;
					document.getElementById("activeTag2").checked = true;
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//reorder list
function edittaglist(num,tagID,listorder,mid){
	if (num == 1){
		document.getElementById("olist_view"+tagID).style.display ='none';
		document.getElementById("olist_edit"+tagID).style.display ='';
	}
	if (num == 2){
		newListOrder = document.getElementById("od"+tagID).value;
		if (!(!isNaN(parseFloat(newListOrder)) && isFinite(newListOrder))){
			alert("List order is not numeric.");
			document.getElementById("od"+tagID).value = "";
			setTimeout(function() { document.getElementById("od"+tagID).focus(); }, 10);
			incomeSubmit = "no";
			return false;
			
		}else{
			incomeSubmit = "yes";
		}
		if (incomeSubmit == "yes"){
			$.post("m_momax/setting/m_inc/newtag_order.php",{
			  mid: mid,
			  tagID: tagID,
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