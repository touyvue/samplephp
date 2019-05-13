
$(document).ready(function(){
	$('#memberlistid').multiselect({ 
    	includeSelectAllOption: true,
		nonSelectedText :'Choose Member',
        enableFiltering:true,
		enableCaseInsensitiveFiltering: true
    });
	$('#categoryid').multiselect({ 
    	includeSelectAllOption: true,
		//nonSelectedText :'Select Sponsors',
        enableFiltering:true,
		enableCaseInsensitiveFiltering: true
    });
	$('#accountid').multiselect({ 
    	includeSelectAllOption: true,
		nonSelectedText :'All Account',
        //enableFiltering:true,
		//enableCaseInsensitiveFiltering: true
    });
		
	$('#data_forecast').dataTable({
	   //"bPaginate": false,
	   "bInfo": false,
	   "sPaginationType": "full_numbers",
	   "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	   "aaSorting": [], //not initial pre-sort
	   "aoColumnDefs": [
						{"aTargets": ["ysort"], "bSortable": true },     
						{"aTargets": ["nsort"], "bSortable": false }
						]
	});
	$('#forecast_detail').dataTable({
	   //"bPaginate": false,
	   "bInfo": false,
	   "sPaginationType": "full_numbers",
	   "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	   "aaSorting": [], //not initial pre-sort
	   "aoColumnDefs": [
						{"aTargets": ["ysort"], "bSortable": true },     
						{"aTargets": ["nsort"], "bSortable": false }
						]
	});
	$('#data_track').dataTable({
	   //"bPaginate": false,
	   "bInfo": false,
	   "sPaginationType": "full_numbers",
	   "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	   "aaSorting": [], //not initial pre-sort
	   "aoColumnDefs": [
						{"aTargets": ["ysort"], "bSortable": true },     
						{"aTargets": ["nsort"], "bSortable": false }
						]
	});
			
});

//add new budget forecasting
function addForecastInfo(){
	incomeSubmit = "yes"; //control flag
	consortiumid = $("#consortiumid").val();
	mid = $("#mid").val(); //selected member id
	membudgetid = $("#membudgetid").val();
	if ($("#budgetname").val() == "" || $.trim( $('#budgetname').val()) == ""){
		alert("Please enter budget name.");
		$("#budgetname").val("");
		$("#budgetname").focus();
		incomeSubmit = "no";
		return false;
	}
	budgetname = $("#budgetname").val();
	budgetname = budgetname.replace(/"/g,'');
	start =$("#startdate").val();
	end = $("#enddate").val();
	note = $("#forecastnote").val();
	note = note.replace(/"/g,'');
	state = $("#fstate").val();
	
	if ($("#activeyes").prop("checked")){
		active = "yes";
	}else{
		active = "no";
	}
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/uptforecast.php",{
		  consortiumid: consortiumid,
		  mid: mid,
		  membudgetid: membudgetid,
		  active: active,
		  budgetname: budgetname,
		  start: start,
		  end: end,
		  note: note,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#memberForecast").modal("hide");
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//enter budget forecasting
function uptForecastInfo(processid,flag){
	incomeSubmit = "yes"; //control flag
	currentTime = new Date();
	month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
	day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
	year = currentTime.getFullYear();
	lastday = new Date(year,month,0);// get the last day of the month
	lastday = lastday.getDate();//get the last day of the month

	if (flag == 0){
		$("#membudgetid").val("");
		$("#activeyes").prop( "disabled", false );
		$("#activeno").prop( "disabled", false );
		$("#budgetname").prop( "disabled", false );
		$("#startdate").prop( "disabled", false );
		$("#enddate").prop( "disabled", false );
		$("#forecastnote").prop( "disabled", false );
		$("#activeyes").prop('checked', true);
		$("#activeno").prop('checked', false);
		$("#budgetname").val("");
		$("#startdate").val(month+"-"+day+"-"+year);
		$("#enddate").val(month+"-"+lastday+"-"+year);
		$("#forecastnote").val("");
		$("#forecastTitle").text("Budget Forecasting");
		$("#forecastState").text("Save");
		$("#fstate").val("new");
		$("#memberForecast").modal("show");
		incomeSubmit = "no";
	}
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/uptforecast.php",{
		  processid: processid,
		  flag: flag,
		  state: "getinfo"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#forecastTitle").text("Budget Forecasting");
				objMemForecast = JSON.parse(data);
				if (flag == 1){
					$("#membudgetid").val(objMemForecast.mbid);
					$("#activeyes").prop( "disabled", false );
					$("#activeno").prop( "disabled", false );
					$("#budgetname").prop( "disabled", false );
					$("#startdate").prop( "disabled", false );
					$("#enddate").prop( "disabled", false );
					$("#forecastnote").prop( "disabled", false );
					$("#budgetname").val(objMemForecast.name);
					$("#startdate").val(objMemForecast.start);
					$("#datetimepicker").datetimepicker('update');
					$("#enddate").val(objMemForecast.end);
					$("#datetimepicker1").datetimepicker('update');
					$("#forecastnote").val(objMemForecast.note);
					
					if (objMemForecast.act == "yes"){
						$("#activeyes").prop('checked', true);
						$("#activeno").prop('checked', false);
					}else{
						$("#activeyes").prop('checked', false);
						$("#activeno").prop('checked', true);
					}
					$("#forecastState").text("Update");
					$("#fstate").val("update");
					$("#memberForecast").modal("show");
				}
				if (flag == 2){
					$("#membudgetid").val(objMemForecast.mbid);
					$("#budgetname").val(objMemForecast.name);
					$("#startdate").val(objMemForecast.start);
					$("#datetimepicker").datetimepicker('update');
					$("#enddate").val(objMemForecast.end);
					$("#datetimepicker1").datetimepicker('update');
					$("#forecastnote").val(objMemForecast.note);
					if (objMemForecast.act == "yes"){
						$("#activeyes").prop('checked', true);
						$("#activeno").prop('checked', false);
					}else{
						$("#activeyes").prop('checked', false);
						$("#activeno").prop('checked', true);
					}
					$("#activeyes").prop( "disabled", true );
					$("#activeno").prop( "disabled", true );
					$("#budgetname").prop( "disabled", true );
					$("#startdate").prop( "disabled", true );
					$("#enddate").prop( "disabled", true );
					$("#forecastnote").prop( "disabled", true );
					$("#forecastState").text("Delete");
					$("#fstate").val("delete");
					$("#memberForecast").modal("show");
				}
				
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}

}

//enter family members
function enterfamily(memberid,name){
	incomeSubmit = "yes"; //control flag
	$("#viewmemid").val(memberid); //assigned current view member's family
	$("#viewmemname").val(name);
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/uptforecast.php",{
		  memberid: memberid,
		  state: "memberinfo"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#firstname").val("");
				$("#lastname").val("");
				$("#relation").val("");
				$("#mnote").val("");
				$("#familyMember").modal("show");
				$("#memTitle").text("Family Member");
				$("#memState").text("Save");
				
				tempMemplusPos = data.indexOf("<>");
				memplusCt = data.substr(1,tempMemplusPos-1);
				memplusArr = data.substr(tempMemplusPos+2);
				objMemplusArr = JSON.parse(memplusArr);
				memberTbl = '';
				for (i=0;i<memplusCt;i++){
					if (objMemplusArr[i].relation == ""){
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+"<br />";
					}else{
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+" - "+objMemplusArr[i].relation+"<br />";
					}
				}
				$("#headmember").html($("#viewmemname").val());
				if (memberTbl == ""){
					$("#memlisttbl").html("- None");
				}else{
					$("#memlisttbl").html(memberTbl);
				}
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}

}
//add family member
function addMember(){
	incomeSubmit = "yes"; //control flag
	if ($("#firstname").val() == "" || $.trim( $('#firstname').val()) == ""){
		alert("Please enter first name.");
		$("#firstname").val("");
		$("#firstname").focus();
		incomeSubmit = "no";
		return false;
	}
	if ($("#lastname").val() == "" || $.trim( $('#lastname').val()) == ""){	
		alert("Please enter last name");
		$("#lastname").val("");
		$("#lastname").focus();
		incomeSubmit = "no";
		return false;
	}
	viewmemid = $("#viewmemid").val();
	firstname = $("#firstname").val();
	lastname = $("#lastname").val();
	relation = $("#relation").val();
	note = $("#mnote").val();
	note = note.replace(/"/g,'');
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/uptforecast.php",{
		  viewmemid: viewmemid,
		  firstname: firstname,
		  lastname: lastname,
		  relation: relation,
		  note: note,
		  state: "addmember"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				
				tempMemplusPos = data.indexOf("<>");
				memplusCt = data.substr(1,tempMemplusPos-1);
				memplusArr = data.substr(tempMemplusPos+2);
				objMemplusArr = JSON.parse(memplusArr);
				memberTbl = '';
				for (i=0;i<memplusCt;i++){
					if (objMemplusArr[i].relation == ""){
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+"<br />";
					}else{
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+" - "+objMemplusArr[i].relation+"<br />";
					}
				}
				$("#memlisttbl").html("");
				$("#memlisttbl").html(memberTbl);
				$("#firstname").val("");
				$("#lastname").val("");
				$("#relation").val("");
				$("#mnote").val("");

			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
//delete family member
function delMember(memberplustID){
	incomeSubmit = "yes"; //control flag
	viewmemid = $("#viewmemid").val();
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/uptforecast.php",{
		  viewmemid: viewmemid,
		  memberplustID: memberplustID,
		  state: "delmember"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				
				tempMemplusPos = data.indexOf("<>");
				memplusCt = data.substr(1,tempMemplusPos-1);
				memplusArr = data.substr(tempMemplusPos+2);
				objMemplusArr = JSON.parse(memplusArr);
				memberTbl = '';
				for (i=0;i<memplusCt;i++){
					if (objMemplusArr[i].relation == ""){
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+"<br />";
					}else{
						memberTbl = memberTbl+" <a href='#' onclick='delMember("+objMemplusArr[i].mid+")'><span class='label label-danger'><i class='fa fa-times'></i></span></a> "+objMemplusArr[i].first+" "+objMemplusArr[i].last+" - "+objMemplusArr[i].relation+"<br />";
					}
				}
				if (memberTbl == ""){
					$("#memlisttbl").html("- None");
				}else{
					$("#memlisttbl").html("");
					$("#memlisttbl").html(memberTbl);
				}
				$("#firstname").val("");
				$("#lastname").val("");
				$("#relation").val("");
				$("#mnote").val("");

			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}
function addDollarSign(value){
	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./))     
	{ val += '.00'; }
	if (!val.match(/\.\d\d/)) 
	{ val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	return '$'+ val;	
}
function uptmemfield(id,value,memberID,membertrackID,field){
	chValue = "";	
	if (field == "memamt"){
		if (value.substr(0,1)=="$"){
			value = value.substr(1);
		}
		value = value.replace(",","");
		if (!(!isNaN(parseFloat(value)) && isFinite(value))){
			alert("Amount is not numeric.");
			$("#"+id).val(addDollarSign($("#"+id).attr('name')));
			$("#"+id).focus();
			incomeSubmit = "no";
			return false;
		}else{
			$("#"+id).prop('name', value);
			$("#"+id).val(addDollarSign(value));
		}
		chValue = value;
		incomeSubmit = "yes";
		
		if (incomeSubmit == "yes"){
			$.post("m_momax/member/m_inc/uptforecast.php",{
			  memberID: memberID,
			  memberbudgetID: membertrackID,
			  chValue: chValue,
			  state: "field"
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					$("#forecasttotal").text(data);
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	}
	if (field == "trkamt"){
		if (value.substr(0,1)=="$"){
			value = value.substr(1);
		}
		value = value.replace(",","");
		if (!(!isNaN(parseFloat(value)) && isFinite(value))){
			alert("Amount is not numeric.");
			$("#"+id).val(addDollarSign($("#"+id).attr('name')));
			$("#"+id).focus();
			incomeSubmit = "no";
			return false;
		}else{
			$("#"+id).prop('name', value);
			$("#"+id).val(addDollarSign(value));
		}
		chValue = value;
		incomeSubmit = "yes";
		
		if (incomeSubmit == "yes"){
			$.post("m_momax/member/m_inc/upttrack.php",{
			  memberID: memberID,
			  membertrackID: membertrackID,
			  chValue: chValue,
			  state: "field"
			},
			function(data,status){
				if (status == "success"){
					//alert("Data: " + data + "\nStatus: " + status);
					$("#tracktotal").text(data);
				}else{
					alert("System is ecountering issue and cannot save data at this point.");
				}
			});
		}
	}
}

//////////////////////////////////////////////////////////////////////
//add new budget forecasting
function addTrackInfo(){
	incomeSubmit = "yes"; //control flag
	if ($("#trackname").val() == "" || $.trim( $('#trackname').val()) == ""){
		alert("Please enter budget tracking name.");
		$("#trackname").val("");
		$("#trackname").focus();
		incomeSubmit = "no";
		return false;
	}
	consortiumid = $("#consortiumid").val(); //consortium id
	mid = $("#mid").val(); //selected member id
	memtrackid = $("#memtrackid").val(); //selected membertrack id
	memberlistIDs = $("#memberlistid").val(); //all selected member id
	membudgetlistid = $("#membudgetlistid").val(); //selected memverbudget id
	acttracklistid = $("#acttracklistid").val(); //selected account id, if this is a transaction
	if (acttracklistid != 0){
		if ($("#credit").prop("checked")){
			type = "1000";
		}else{
			type = "1001";
		}
	}else{
		type = 0;
	}
	
	trackname = $("#trackname").val();
	trackname = trackname.replace(/"/g,'');
	trackdate =$("#trackdate").val();
	tracknote = $("#tracknote").val();
	tracknote = tracknote.replace(/"/g,'');
	state = $("#tstate").val();
	
	if (incomeSubmit == "yes"){
		$.post("m_momax/member/m_inc/upttrack.php",{
		  consortiumid: consortiumid,
		  mid: mid,
		  memtrackid: memtrackid,
		  memberlistIDs: memberlistIDs,
		  membudgetlistid: membudgetlistid,
		  acttracklistid: acttracklistid,
		  type: type,
		  trackname: trackname,
		  trackdate: trackdate,
		  tracknote: tracknote,
		  state: state
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#memberTrack").modal("hide");
				location.reload();
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//enter budget forecasting
function uptTrackInfo(processid,flag){
	incomeSubmit = "yes"; //control flag
	currentTime = new Date();
	month = ('0' + (currentTime.getMonth()+1)).slice(-2); //currentTime.getMonth() + 1
	day = ('0' + currentTime.getDate()).slice(-2); //currentTime.getDate()
	year = currentTime.getFullYear();

	if (flag == 0){
		$("#memtrackid").val("");
		$("#trackname").prop( "disabled", false );
		$("#trackdate").prop( "disabled", false );
		$("#tracknote").prop( "disabled", false );
		$("#memberlistid").multiselect("enable");
		$("#membudgetlistid").prop( "disabled", false );
		$("#acttracklistid").prop( "disabled", false );
		$("#debit").prop( "disabled", false );
		$("#credit").prop( "disabled", false );
		$("#debit").prop('checked', false);
		$("#credit").prop('checked', false);
		document.getElementById("on_credit_debit").style.display = "none";
		$("#trackname").val("");
		$("#trackdate").val(month+"-"+day+"-"+year);
		$("#memberlistid").val("");
		$("#memberlistid").multiselect("refresh");
		$("#tracknote").val("");
		$("#trackTitle").text("Budget Tracking");
		$("#trackState").text("Save");
		$("#tstate").val("new");
		$("#memberTrack").modal("show");
		incomeSubmit = "no";
	}
	
	if (incomeSubmit == "yes"){//posting budget data
		$.post("m_momax/member/m_inc/upttrack.php",{
		  processid: processid,
		  flag: flag,
		  state: "getinfo"
		},
		function(data,status){
			if (status == "success"){
				//alert("Data: " + data + "\nStatus: " + status);
				$("#trackTitle").text("Budget Tracking");
				objMemForecast = JSON.parse(data);
				if (flag == 1){					
					$("#trackname").prop( "disabled", false );
					$("#trackdate").prop( "disabled", false );
					$("#tracknote").prop( "disabled", false );
					$("#memberlistid").multiselect("enable");
					$("#membudgetlistid").prop( "disabled", false );
					$("#acttracklistid").prop( "disabled", false );
					$("#debit").prop( "disabled", false );
					$("#credit").prop( "disabled", false );
					$("#memtrackid").val(objMemForecast.mtid);
					$("#membudgetlistid").val(objMemForecast.mbid);
					$("#acttracklistid").val(objMemForecast.acct);
					if (objMemForecast.acct == 0){
						$("#debit").prop('checked', false);
						$("#credit").prop('checked', false);
						document.getElementById("on_credit_debit").style.display = "none";
					}else{
						if (objMemForecast.type == "1000"){
							$("#credit").prop('checked', true);
							$("#debit").prop('checked', false);
						}else{
							$("#credit").prop('checked', false);
							$("#debit").prop('checked', true);
						}
						document.getElementById("on_credit_debit").style.display = "";
					}
					
					$("#trackname").val(objMemForecast.name);
					$("#trackdate").val(objMemForecast.date);
					$("#datetimepicker2").datetimepicker('update');
					memlistarr = objMemForecast.list.split(",");
					$("#memberlistid").val(memlistarr);
					$("#memberlistid").multiselect("refresh");
					$("#tracknote").val(objMemForecast.note);
					$("#trackState").text("Update");
					$("#tstate").val("update");
					$("#memberTrack").modal("show");
				}
				if (flag == 2){
					$("#memtrackid").val(objMemForecast.mtid);
					$("#membudgetlistid").val(objMemForecast.mbid);
					$("#acttracklistid").val(objMemForecast.acct);
					if (objMemForecast.acct == 0){
						$("#debit").prop('checked', false);
						$("#credit").prop('checked', false);
						document.getElementById("on_credit_debit").style.display = "none";
					}else{
						if (objMemForecast.type == "1000"){
							$("#credit").prop('checked', true);
							$("#debit").prop('checked', false);
						}else{
							$("#credit").prop('checked', false);
							$("#debit").prop('checked', true);
						}
						document.getElementById("on_credit_debit").style.display = "";
					}
					$("#trackname").val(objMemForecast.name);
					$("#trackdate").val(objMemForecast.date);
					$("#datetimepicker2").datetimepicker('update');
					memlistarr = objMemForecast.list.split(",");
					$("#memberlistid").val(memlistarr);
					$("#memberlistid").multiselect("refresh");
					$("#tracknote").val(objMemForecast.note);
					$("#trackname").prop( "disabled", true );
					$("#trackdate").prop( "disabled", true );
					$("#tracknote").prop( "disabled", true );
					$("#memberlistid").multiselect("disable");
					$("#membudgetlistid").prop( "disabled", true );
					$("#acttracklistid").prop( "disabled", true );
					$("#debit").prop( "disabled", true );
					$("#credit").prop( "disabled", true );
					$("#trackState").text("Delete");
					$("#tstate").val("delete");
					$("#memberTrack").modal("show");
				}
				
			}else{
				alert("System is ecountering issue and cannot save data at this point.");
			}
		});
	}
}

//turn on/off credit and debit
function turnonCreDeb(id,value){
	if (value != 0){
		document.getElementById("on_credit_debit").style.display = "";
		$("#credit").prop('checked', false);
		$("#debit").prop('checked', true);
	}else{
		$("#credit").prop('checked', false);
		$("#debit").prop('checked', false);
		document.getElementById("on_credit_debit").style.display = "none";
	}
}