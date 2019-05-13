
$(document).ready(function(){
	//add new account
	$("#addNewCategory").click(function(){
		$("#myModalAdd").modal("show");	
		$("#eCatType").text("Add Category");
		$("#state").val("add");
		$("#catName").val("");
		$("#catName").val("");
		$("#description").val("");
		$("#edtCatID").val("");
		document.getElementById("subcat_yn").checked = false;
	});
	
	$("#insertNewCategory").click(function(){
		if ($("#state").val() != "delete"){
			if ($("#catName").val() == ""){//date validation
				alert("Enter category name");
				$("#catName").focus();
				return false;
			}
		}
		mid = $("#mid").val();
		catName = $("#catName").val();
		catName = catName.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		catDesc = $("#description").val();
		catDesc = catDesc.replace(/(\r\n|\n|\r)/gm,""); //remove all linebreaks
		state = $("#state").val();
		catType = $("#trkcategorytype").val();
		catID = $("#edtCatID").val();
		if (document.getElementById("subcat_yn").checked == true){
			subcat = "yes";
		}else{
			subcat = "no";	
		}
			
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newtrkcategory.php",{
			  mid: mid,
			  catName: catName,
			  subcat: subcat,
			  catType: catType,
			  catDesc: catDesc,
			  catID: catID,
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
function uptCategoryInfo(categoryID){
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newtrkcategory.php",{
		  mid: mid,
		  catID: categoryID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eCatType").text("Update Category");
				$("#state").val("edit");
				objCatInfo = JSON.parse(data);
				$("#edtCatID").val(objCatInfo.id);
				$("#catName").val(objCatInfo.name);
				$("#trkcategorytype").val(objCatInfo.type);
				$("#description").val(objCatInfo.desc);	
				if (objCatInfo.subcat == "yes"){
					document.getElementById("subcat_yn").checked = true;
				}else{
					document.getElementById("subcat_yn").checked = false;
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//delete account info
function delCategoryInfo(categoryID){
	//alert(accountID);
	mid = $("#mid").val();
	$("#state").val("getdata");
	state = "getdata";
	
	incomeSubmit = "yes";
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/newtrkcategory.php",{
		  mid: mid,
		  catID: categoryID,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#myModalAdd").modal("show");
				$("#eCatType").text("Delete Category");
				$("#state").val("delete");
				objCatInfo = JSON.parse(data);
				$("#edtCatID").val(objCatInfo.id);
				$("#catName").val(objCatInfo.name);
				$("#trkcategorytype").val(objCatInfo.type);
				$("#description").val(objCatInfo.desc);				
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}