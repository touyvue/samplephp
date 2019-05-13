////change rotate words
function changetxt(value){   
	if (value == 3){
    	document.getElementById("simple_dis").innerHTML= "<img src='images/rotate_words1.gif'>";}
    if (value == 2){
      	document.getElementById("simple_dis").innerHTML= "<img src='images/rotate_words2.gif'>";}
    if (value == 1){
      	document.getElementById("simple_dis").innerHTML= "<img src='images/rotate_words3.gif'>";}
    if (value == 0){
    	document.getElementById("simple_dis").innerHTML= "<img src='images/rotate_words.gif'>";}
}
////clear contents
function clr_cont(valname){
	valname.value = "";
}
////set focus on login page
function my_load_login(){
	document.loginform.loginid.focus();
}
////set focus on login page
function my_load_request(){
	document.requestfrm.keyword.focus();
}
////set focus on login page
function my_main_login(){
	document.loginform.inputEmail.focus();
}

////remove post
function removePost(id){
	del = confirm("Delete this post?");
	if (del == true) {
		memID = $("#postMemID").val();
		postid = id;
		add = "delete";
		
		postSubmit = "yes"; //control flag
		if (postSubmit == "yes"){//posting budget data
			$.post("m_content/m_inc/delpost.php",{
			  mid: memID, //member id
			  pid: postid, //post
			  postState: add
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					//$("#myModalAdd").modal("hide");
					location.reload();
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	} 
}

//edit request and resend
function changeMsgStatus(reqID,num){
	addmessage = $("#mainmsg").val();
	memID = $("#memberID").val();
	sendType = num;

	postSubmit = "yes";
	if (postSubmit == "yes"){//posting budget data
		$.post("m_momax/message/m_inc/retmessage.php",{
		  mid: memID, //member id
		  reqID: reqID,
		  sendType: num
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				//$("#myModalAdd").modal("hide");
				//location.reload();
				objMessage = JSON.parse(data);
				document.getElementById("on_status").style.display = "";
				document.getElementById("del_msg").style.display = "";
				$("#orgReqID").val(objMessage.reqid);
				$("#keyword").val(objMessage.from);
				$("#selectID").val(objMessage.senderid);
				$("#receiver").val(objMessage.from);
				$("#spacePre").html("<br>");
				if (objMessage.to == "yes"){
					$("#fromPre").text("To: "+objMessage.from+" ("+objMessage.mdate+")");	
					$("#reqType").val("to");
				}else{
					$("#fromPre").text("From: "+objMessage.from+" ("+objMessage.mdate+")");
					$("#reqType").val("form");
				}
				$("#subject").val(objMessage.subject);
				$("#mstatus").val(objMessage.status);
				if (objMessage.status == "Completed"){
					document.getElementById("reqDone").checked = true;
				}else{
					document.getElementById("reqDone").checked = false;
				}
				msg = objMessage.message;
				msg = msg.replace(/<br>/g, '\r\n');
				msg = objMessage.from+" ("+objMessage.mdate+"):"+"\r\n"+msg+"\r\n------------------------------\r\n";
				$('#mainmsg').val(msg);
				$("#addRequest").text("Resend");
				$("#resend").val("yes");
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

////add post
$(document).ready(function(){
		$("#addPost").click(function(){
			
			memID = $("#postMemID").val();
			note = $("#postName").val();
			add = "add";
			
			postSubmit = "yes";
			if (postSubmit == "yes"){//posting budget data
				$.post("m_content/m_inc/addpost.php",{
				  mid: memID, //member id
				  npost: note, //post
				  postState: add
				},
				function(data,status){
					if (status == "success"){
						//alert("Data: " + data + "\nStatus: " + status);
						//$("#myModalAdd").modal("hide");
						location.reload();
					}else{
						alert("System is ecountering issue and cannot save data at this point.");
					}
				});
			}
			
		});
				
		//autocomplete
		$("#keyword").on("keydown", function() {
			keyword = $("#keyword").val();
			memID = $("#memberID").val();
			
			$.get( "m_momax/message/m_inc/search.php", { keyword:keyword,memID:memID } )
			.done(function( data ) {	
				tempDiv = data.indexOf("<=>");
				memberNameInfo = data.substr(0,tempDiv);
				memberIdInfo = data.substr(tempDiv+3);
				memberNameInfoObj = JSON.parse(memberNameInfo);
				memberIdInfoObj = JSON.parse(memberIdInfo);
			});
			
			var data = memberIdInfoObj;
			$("#keyword").autocomplete({
				minLength: 0,
				source: data,
				focus: function(event, ui) {
					event.preventDefault();// prevent autocomplete from updating the textbox
					$(this).val(ui.item.label);// manually update the textbox
				},
				select: function(event, ui) {
					event.preventDefault();// prevent autocomplete from updating the textbox
					$(this).val(ui.item.label);// manually update the textbox and hidden field
					$("#selectID").val(ui.item.value);
				}
			});
		});
		
		//add request
		$("#addRequest").click(function() {
			if ($("#keyword").val() == ""){//date validation
				alert("Enter group user");
				$("#keyword").focus();
				return false;
				incomeSubmit = "no";
			}
			if ($("#subject").val() == ""){//date validation
				alert("Enter subject");
				$("#subject").focus();
				return false;
				incomeSubmit = "no";
			}
			if ($("#mainmsg").val() == ""){//date validation
				alert("Enter message");
				$("#mainmsg").focus();
				return false;
				incomeSubmit = "no";
			}
			orgReqID = $("#orgReqID").val();
			memID = $("#memberID").val();
			keyword = $("#keyword").val();
			selectID = $("#selectID").val();
			if (document.getElementById("reqDone").checked == true){
				ustatus = "Completed"; //$("#mstatus").val();
			}else{
				ustatus = "Received";
			}
			subject = $("#subject").val();
			addmessage = $("#mainmsg").val();
			
			postSubmit = "yes";
			if ($("#resend").val() == "yes"){
				
				if (postSubmit == "yes"){//posting budget data
					$.post("m_momax/message/m_inc/uptmessage.php",{
					  mid: memID, //member id
					  orgReqID:orgReqID,
					  receiver: keyword,
					  receiverID: selectID,
					  ustatus: ustatus,
					  subject: subject,
					  addmessage: addmessage //post
					},
					function(data,status){
						if (status == "success"){
							//alert("Data: " + data + "\nStatus: " + status);
							//$("#myModalAdd").modal("hide");
							location.reload();
						}else{
							alert("System is ecountering issue and cannot save data at this point.");
						}
					});
				}
			}else{
				if (postSubmit == "yes"){//posting budget data
					$.post("m_momax/message/m_inc/addmessage.php",{
					  mid: memID, //member id
					  receiver: keyword,
					  receiverID: selectID,
					  subject: subject,
					  addmessage: addmessage //post
					},
					function(data,status){
						if (status == "success"){
							//alert("Data: " + data + "\nStatus: " + status);
							//$("#myModalAdd").modal("hide");
							if (data == "not"){
								alert($("#keyword").val()+" is not a group member.");
								$("#keyword").val("");
								$("#keyword").focus();
							}else{
								location.reload();
							}
						}else{
							alert("System is ecountering issue and cannot save data at this point.");
						}
					});
				}
			}//end of else
		});		
		
		//delete message
		$("#delRequest").click(function() {
			orgReqID = $("#orgReqID").val();
			memID = $("#memberID").val();
			reqType = $("#reqType").val();
			
			del = confirm("Delete this message?");
			if (del == true) {
				$.post("m_momax/message/m_inc/delmessage.php",{
				  mid: memID, //member id
				  orgReqID: orgReqID,
				  reqType: reqType
				},
				function(data,status){
					if (status == "success"){
						//alert("Data: " + data + "\nStatus: " + status);
						//$("#myModalAdd").modal("hide");
						location.reload();
					}else{
						alert("System is ecountering issue and cannot save data at this point.");
					}
				});
			}
		
		});
		
		$("#reqBudget").click(function(){
			if (document.getElementById("reqBudget").checked == true){
				document.getElementById("on_budgetlist").style.display = "";
				document.getElementById("on_req_budget").style.display = "";
				document.getElementById("on_accountlist").style.display = "none";
				document.getElementById("reqAccount").checked = false;
			}else{
				document.getElementById("on_budgetlist").style.display = "none";
				document.getElementById("on_req_budget").style.display = "none";
				document.getElementById("on_accountlist").style.display = "none";
			}
		
		});		
		$("#reqAccount").click(function(){
			if (document.getElementById("reqAccount").checked == true){
				document.getElementById("on_accountlist").style.display = "";
				document.getElementById("on_req_budget").style.display = "";
				document.getElementById("on_budgetlist").style.display = "none";
				document.getElementById("reqBudget").checked = false;
			}else{
				document.getElementById("on_accountlist").style.display = "none";
				document.getElementById("on_req_budget").style.display = "none";
				document.getElementById("on_budgetlist").style.display = "none";
			}
		});	
		
		$("#selfGrp").click(function(){
			if (document.getElementById("selfGrp").checked == true){
				document.getElementById("on_groupList").style.display = "none";
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					document.getElementById(groupIDsArr[i]).checked = false;
				}
			}			
		});
		$("#specificGrp").click(function(){
			if (document.getElementById("specificGrp").checked == true){
				document.getElementById("on_groupList").style.display = "";
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					document.getElementById(groupIDsArr[i]).checked = false;
				}
				document.getElementById(groupIDsArr[0]).checked = true;
			}
		});
		$("#allGrp").click(function(){
			if (document.getElementById("allGrp").checked == true){
				document.getElementById("on_groupList").style.display = "none";
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					document.getElementById(groupIDsArr[i]).checked = false;
				}
			}
		});
		$("#updatePost").click(function(){
			mid = $("#mid").val();
			if (document.getElementById("selfGrp").checked == true){
				rights = 1;
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					document.getElementById(groupIDsArr[i]).checked = false;
				}
				rightsArr = "";
				rightsCt = 0;
			}
			if (document.getElementById("specificGrp").checked == true){
				rights = 2;
				rightsArr = "";
				rightsCt = 0;
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					if (document.getElementById(groupIDsArr[i]).checked == true){
						grpRightID = groupIDsArr[i].slice(1);
						rightsArr = rightsArr+grpRightID+",";
						rightsCt++;
					}
				}
				rightsArr = rightsArr.slice(0,-1);
			}	
			if (document.getElementById("allGrp").checked == true){
				rights = 3;
				groupIDsArr = [];
				groupIDs = $("#grpIDs").val();
				groupIDsArr = groupIDs.split(",");
				groupIDsArrLength = groupIDsArr.length;
				for (i=0;i<groupIDsArrLength;i++){
					document.getElementById(groupIDsArr[i]).checked = false;
				}
				rightsArr = "";
				rightsCt = 0;
			}
			
			postSubmit = "yes";
			if (postSubmit == "yes"){//posting budget data
					$.post("m_momax/setting/m_inc/uptpost.php",{
					  mid: mid, //member id
					  rights: rights,
					  rightsCt: rightsCt,
					  rightsArr: rightsArr,
					  state: "update" 
					},
					function(data,status){
						if (status == "success"){
							//alert("Data: " + data + "\nStatus: " + status);
							location.reload();
							alert("Post rights have been updated");
						}else{
							alert("System is ecountering issue and cannot save data at this point.");
						}
					});
				}
		});
});