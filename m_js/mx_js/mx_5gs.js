$(document).ready(function(){
	$('#grouptree').find('SPAN').click(function(e){
		$(this).parent().children('UL').toggle();
	});
	//log_groupset*php/
	$("#addNewMember").click(function(){
		$("#myModalAdd").modal("show");	
		$("#state").val("add");
		$("#acctName").val("");
		$("#description").val("");
		$("#groupID").val("");
		
		$("#rstreet").val("");
		$("#rcity").val("");
		$("#rstate").val("");
		$("#rzip").val("");
		$("#rphone").val("");
	});
	
	$("#insertNewMember").click(function(){
		
		if ($("#newfirst").val() == ""){//date validation
			alert("Enter first name");
			$("#newfirst").focus();
			return false;
		}
		if ($("#newlast").val() == ""){//date validation
			alert("Enter last name");
			$("#newlast").focus();
			return false;
		}
		if ($("#cd1email").val() == ""){//date validation
			alert("Enter email");
			$("#cd1email").focus();
			return false;
		}
		if ($("#newpassword").val() == ""){//date validation
			alert("Enter password");
			$("#newpassword").focus();
			return false;
		}
		if($('#gadminNo').is(':checked')){ 
			admin = "no"; 
		}
		if($('#gadminYes').is(':checked')){ 
			admin = "yes";
		}
		
		rstreet = "";
		rcity = "";
		rstate = "";
		rzip = "";
		rphone = "";
		if($('#addressYes').is(':checked')){ 
			rstreet = $("#rstreet").val();
			rcity = $("#rcity").val();
			rstate = $("#rstate").val();
			rzip = $("#rzip").val();
			rphone = $("#rphone").val();
		}
		
		fName = $("#newfirst").val();
		lName = $("#newlast").val();
		nemail = $("#cd1email").val();
		npassword = $("#newpassword").val();
		state = $("#newState").val();
		
		consortID = $("#newConID").val();
		groupID = $("#newGrpID").val();
		exMem = $("#exMember").val();
		exMid = $("#exMid").val();
			
		incomeSubmit = "yes";
		if (incomeSubmit == "yes"){//posting budget data
			$.post("m_momax/setting/m_inc/newmem.php",{
			  fName: fName,
			  lName: lName,
			  nemail: nemail,
			  npassword: npassword,
			  rstreet: rstreet,
			  rcity: rcity,
			  rstate: rstate,
			  rzip: rzip,
			  rphone: rphone,
			  admin: admin,
			  consortID: consortID,
			  groupID: groupID,
			  exMem: exMem,
			  exMid: exMid,
			  state: state 
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					if (exMem == "no"){
						alert("A password setup email has been sent to the new user.")
					}else{
						alert("Member is added to the group.");	
					}
					$("#myModalAdd").modal("hide");
					location.reload();
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	});
	
	$("#existMember").change(function(){
		mid = $('#existMember option:selected').val();
		incomeSubmit = "yes";
		if (incomeSubmit == "yes" && mid != "none"){//posting budget data
			$.post("m_momax/setting/m_inc/getmeminfo.php",{
			  mid: mid,
			  state: "info"
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					objMemInfo = JSON.parse(data);
					$("#newfirst").val(objMemInfo.first);
					$("#newlast").val(objMemInfo.last);
					$("#cd1email").val(objMemInfo.email);
					$("#newpassword").val("******");
					$("#exMember").val("yes");
					$("#exMid").val(mid);
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}else{
			$("#newfirst").val("");
			$("#newlast").val("");
			$("#cd1email").val("");
			$("#newpassword").val("");
			$("#exMember").val("no");
			$("#exMid").val("");
		}
	});
	
	//on_address no
	$("#addressNo").click(function(){
		document.getElementById("on_address").style.display = "none";
	});
	//on_address yes
	$("#addressYes").click(function(){
		document.getElementById("on_address").style.display = "";
	});
	
}); //end of ready(fundtion)
//////////////////////////////////////
//groupmain*php:: update member status
function uptMemSta(memID,id,lic){
	if (document.getElementById("sy"+memID).checked  == true){
		memStatus = "yes";
	}
	if (document.getElementById("sn"+memID).checked  == true){
		memStatus = "no";
	}
	adm = "no";
	if (document.getElementById("a"+memID).checked  == true){
		adm = "yes";	
	}
	flagSubmit = "yes";
	if (flagSubmit == "yes" && memID != ""){//validation
		$.post("m_momax/setting/m_inc/uptmemsta.php",{
		  mid: memID,
		  sta: memStatus,
		  level: lic,
		  admin: adm,
		  id: id,
		  state: "update"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "no admin"){
					document.getElementById("a"+memID).checked  = false;
				}
				alert("Member status has been changed.");
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
/////////////////////////////////////
//log_consortset*php
function chkAdmin(memID,id,lic,num){	
	if (document.getElementById("a"+memID).checked  == true){
		check = "yes";
		flagSubmit = "yes";
	}else{
		check = "no";
		if (num == 0){
			if (confirm("Removing admin rights for yourself?") == true){
				flagSubmit = "yes";
			}else{
				document.getElementById("a"+memID).checked  = true;
				flagSubmit = "none";
				return false;
			}
		}else{
			flagSubmit = "yes";	
		}
	}
	if (flagSubmit == "yes" && memID != ""){//validation
		$.post("m_momax/setting/m_inc/chkmemadm.php",{
		  mid: memID,
		  id: id,
		  level: lic,
		  state: check
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "over"){
					alert("Only 2 admins allow");
					document.getElementById("a"+memID).checked  = false;
				}
				if (data == "update"){
					alert("Admin is set");	
				}
				if (data == "remove"){
					alert("Admin is updated");	
					if (num == 0){
						location.reload();	
					}
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
/////////////////////////////////////
//organset*php:: validate email
function checkMemEmail(id,email){
	mid = $("#mid").val();
	incomeSubmit = "no";
	if (id == "cemail"){
		if (email == ""){
			alert("Please enter email address");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		if (echeck(email)==false){
			$("#cemail").val("");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		incomeSubmit = "no";
	}
	if (id == "cd1email"){
		if (email == ""){
			alert("Please enter email address");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		if (echeck(email)==false){
			$("#cd1email").val("");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		incomeSubmit = "yes";
	}
	if (id == "cd2email"){
		if (email == ""){
			alert("Please enter email address");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		if (echeck(email)==false){
			$("#cd2email").val("");
			setTimeout(function() { document.getElementById(id).focus(); }, 10);
			return false;
		}
		incomeSubmit = "yes";
	}
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/setting/m_inc/checkemail.php",{
		  mid: mid,
		  email: email,
		  check: "consortium"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "match"){
					alert("Email is used by other member");
					if (id == "cd1email"){
						$("#cd1email").val("");
						$("#cd1email").focus();
						return false;
					}
					if (id == "cd2email"){
						$("#cd2email").val("");
						$("#cd2email").focus();
						return false;
					}
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
//////////////////////////////////
//Consortium:: validate form info
function validateConsortFrm(){
	//consortium 
	if (document.formConsort.consortium.value==null || document.formConsort.consortium.value==""){
		alert("Please enter consortium name.");
		document.formConsort.consortium.focus();
		return false;
	}
	if (document.formConsort.cemail.value==null || document.formConsort.cemail.value==""){
		alert("Please enter consortium email.");
		document.formConsort.cemail.focus();
		return false;
	}
	if (echeck(document.formConsort.cemail.value)==false){
		document.formConsort.cemail.value="";
		document.formConsort.cemail.focus();
		return false;
	}
	
	if (document.formConsort.state.value=="new"){
		//admin1
		if (document.formConsort.cd1firstname.value==null || document.formConsort.cd1firstname.value==""){
			alert("Please enter Admin 1 first name.");
			document.formConsort.cd1firstname.focus();
			return false;
		}
		if (document.formConsort.cd1lastname.value==null || document.formConsort.cd1lastname.value==""){
			alert("Please enter Admin 1 last name.");
			document.formConsort.cd1lastname.focus();
			return false;
		}
		if (document.formConsort.cd1email.value==null || document.formConsort.cd1email.value==""){
			alert("Please enter Admin 1 e-mail.");
			document.formConsort.cd1email.focus();
			return false;
		}
		if (echeck(document.formConsort.cd1email.value)==false){
			document.formConsort.cd1email.value="";
			document.formConsort.cd1email.focus();
			return false;
		}
		//admin2
		if (document.formConsort.cd2firstname.value!="" && document.formConsort.cd2lastname.value!="" && document.formConsort.cd2email.value!="" && document.formConsort.cd2password.value!=""){
			if (document.formConsort.cd2firstname.value==null || document.formConsort.cd2firstname.value==""){
				alert("Please enter Admin 2 first name.");
				document.formConsort.cd2firstname.focus();
				return false;
			}
			if (document.formConsort.cd2lastname.value==null || document.formConsort.cd2lastname.value==""){
				alert("Please enter Admin 2 last name.");
				document.formConsort.cd2lastname.focus();
				return false;
			}
			if (document.formConsort.cd2email.value==null || document.formConsort.cd2email.value==""){
				alert("Please enter Admin 2 e-mail.");
				document.formConsort.cd2email.focus();
				return false;
			}
			if (echeck(document.formConsort.cd2email.value)==false){
				document.formConsort.cd2email.value="";
				document.formConsort.cd2email.focus();
				return false;
			}
		}
	}//end of new process
}
//////////////////////////////////
//Organization:: validate form fields
//organset*php/
function validateOrganFrm(){
	if (document.formOrgan.organization.value==null || document.formOrgan.organization.value==""){
		alert("Please enter org name.");
		document.formOrgan.organization.focus();
		return false;
	}
	if (document.formOrgan.cemail.value==null || document.formOrgan.cemail.value==""){
		alert("Please enter org email.");
		document.formOrgan.cemail.focus();
		return false;
	}
	if (echeck(document.formOrgan.cemail.value)==false){
		document.formOrgan.cemail.value="";
		document.formOrgan.cemail.focus();
		return false;
	}
	if (document.formOrgan.chgstate.value=="new"){
		if (document.getElementById("meadmin1").checked  == false){//Admin1: if admin not default, validate all fields
			if (document.formOrgan.cd1firstname.value==null || document.formOrgan.cd1firstname.value==""){
				alert("Please enter Admin 1 first name.");
				document.formOrgan.cd1firstname.focus();
				return false;
			}
			if (document.formOrgan.cd1lastname.value==null || document.formOrgan.cd1lastname.value==""){
				alert("Please enter Admin 1 last name.");
				document.formOrgan.cd1lastname.focus();
				return false;
			}
			if (document.formOrgan.cd1email.value==null || document.formOrgan.cd1email.value==""){
				alert("Please enter Admin 1 e-mail.");
				document.formOrgan.cd1email.focus();
				return false;
			}
			//=>function*js/
			if (echeck(document.formOrgan.cd1email.value)==false){
				document.formOrgan.cd1email.value="";
				document.formOrgan.cd1email.focus();
				return false;
			}
		}
		
		if (document.getElementById("meadmin2").checked  == false){//Admin1: if admin not default, validate all fields
			if (document.formOrgan.cd2firstname.value!="" && document.formOrgan.cd2lastname.value!="" && document.formOrgan.cd2email.value!=""){
				if (document.formOrgan.cd2firstname.value==null || document.formOrgan.cd2firstname.value==""){
					alert("Please enter Admin 2 first name.");
					document.formOrgan.cd2firstname.focus();
					return false;
				}
				if (document.formOrgan.cd2lastname.value==null || document.formOrgan.cd2lastname.value==""){
					alert("Please enter Admin 2 last name.");
					document.formOrgan.cd2lastname.focus();
					return false;
				}
				if (document.formOrgan.cd2email.value==null || document.formOrgan.cd2email.value==""){
					alert("Please enter Admin 2 e-mail.");
					document.formOrgan.cd2email.focus();
					return false;
				}
				//=>function*js/
				if (echeck(document.formOrgan.cd2email.value)==false){
					document.formOrgan.cd2email.value="";
					document.formOrgan.cd2email.focus();
					return false;
				}
			}//end admin2
		}
	}//end of new process
}
//////////////////////////////
//disable adding admin
function diableAdmin(id){
	if (document.getElementById("meadmin1").checked  == true){
		document.getElementById("cd1firstname").value = "";
		document.getElementById("cd1firstname").disabled = true;
		document.getElementById("cd1lastname").value = "";
		document.getElementById("cd1lastname").disabled = true;
		document.getElementById("cd1email").value = "";
		document.getElementById("cd1email").disabled = true;
		document.getElementById("cd1password").value = "";
		document.getElementById("cd1password").disabled = true;
		document.getElementById("cd1mx_self").disabled = true;
		//turn on admin2
		document.getElementById("cd2firstname").value = "";
		document.getElementById("cd2firstname").disabled = false;
		document.getElementById("cd2lastname").value = "";
		document.getElementById("cd2lastname").disabled = false;
		document.getElementById("cd2email").value = "";
		document.getElementById("cd2email").disabled = false;
		document.getElementById("cd2password").value = "";
		document.getElementById("cd2password").disabled = false;
		document.getElementById("cd2mx_self").disabled = false;
	}
	if (document.getElementById("meadmin2").checked  == true){
		document.getElementById("cd2firstname").value = "";
		document.getElementById("cd2firstname").disabled = true;
		document.getElementById("cd2lastname").value = "";
		document.getElementById("cd2lastname").disabled = true;
		document.getElementById("cd2email").value = "";
		document.getElementById("cd2email").disabled = true;
		document.getElementById("cd2password").value = "";
		document.getElementById("cd2password").disabled = true;
		document.getElementById("cd2mx_self").disabled = true;
		//turn on admin1
		document.getElementById("cd1firstname").value = "";
		document.getElementById("cd1firstname").disabled = false;
		document.getElementById("cd1lastname").value = "";
		document.getElementById("cd1lastname").disabled = false;
		document.getElementById("cd1email").value = "";
		document.getElementById("cd1email").disabled = false;
		document.getElementById("cd1password").value = "";
		document.getElementById("cd1password").disabled = false;
		document.getElementById("cd1mx_self").disabled = false;
	}
}
//delete gruoup
//log_groupmain*php
function delGrpOut(groupID){
	if (confirm("When group is deleted, it's gone. Do you want to proceed?") == true){
		flagSubmit = "yes";
	}else{
		flagSubmit = "none";
		return false;
	}
	if (flagSubmit == "yes"){//validation
		$.post("m_momax/setting/m_inc/delgroup.php",{
		  groupID: groupID,
		  state: "delete"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "good"){
					alert("Group has been deleted.");
					location.reload();
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
//delete member
//log_groupset*php
function delMemOut(memID,groupID){
	if (confirm("Remove member from this group. Do you want to proceed?") == true){
		flagSubmit = "yes";
	}else{
		flagSubmit = "none";
		return false;
	}
	if (flagSubmit == "yes"){//validation
		$.post("m_momax/setting/m_inc/delmem.php",{
		  memID: memID,
		  groupID: groupID,
		  state: "delete"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				if (data == "yes"){
					alert("Member has been deleted.");
					location.reload();
				}else{
					alert("Group must have at least 1 member.");	
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}