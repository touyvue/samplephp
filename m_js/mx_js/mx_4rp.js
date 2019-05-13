	
$(document).ready(function(){
	 $("#filterReport").click(function () {
		t_now = new Date();
		t_day = ("0" + t_now.getDate()).slice(-2);
		t_month = ("0" + (t_now.getMonth() + 1)).slice(-2);
		t_today = (t_month)+"-"+(t_day)+"-"+t_now.getFullYear();
		t_yearstart = "01-01-"+t_now.getFullYear();
		t_yearend = "12-31-"+t_now.getFullYear();
		
		if($("#start_date").val()=="" || $("#end_date").val()==""){
			alert("Start/end dates are needed.");
			$("#start_date").val(t_yearstart); 
			$("#end_date").val(t_yearend);
			return false;
		}
		
		staDate = new Date($("#start_date").val());
		endDate = new Date($("#end_date").val());
		
		if(staDate > endDate){
			alert("Start date cannot start later than end date");
			$("#start_date").val(t_yearstart); 
			$("#end_date").val(t_yearend);
			return false;
		}
		
		if($("#start_date").val()=="" || $("#end_date").val()=="" || (staDate > endDate)){
			startdate = "0101"+t_now.getFullYear();
			enddate = "1231"+t_now.getFullYear();
		}else{
			startdate = $("#start_date").val();
			startdate = startdate.replace(/\-/g, '');
			enddate = $("#end_date").val();
			enddate = enddate.replace(/\-/g, '');
		}
		grpfilter = "N";
		hlink = $("#currentp").val()+"&trkg="+$("#trkgrouplist").val()+"&tcid="+$("#trkcategorylist").val();
		//reportp = $("#reportp").val();
		window.location.href = hlink+"&det="+startdate+grpfilter+enddate;
		return false;
	 });
	 
	 $('#data_rpt001track').dataTable({
	   "sPaginationType": "full_numbers",
	   "aaSorting": [], //not initial sort
	   //"scrollX": true,
	   //"aoColumnDefs": [{'bSortable': false,'aTargets': [ 2 ]}]
	});
	
}); //end of ready(function)

//check numeric value  
function isNumber_chk(id, value) {  
	if (value.substr(0,1)=="$"){
		value = value.substr(1);
	}
	value = value.replace(",","");
	
	if (!(!isNaN(parseFloat(value)) && isFinite(value))){
		alert("Amount is not numeric.");
		document.getElementById(id).value = "";
		setTimeout(function() { document.getElementById(id).focus(); }, 10);
  		return false;
	}
	var val = parseFloat(value.replace(/\$/g,''))+'';
	if (!val.match(/\./))     
	{ val += '.00'; }
	if (!val.match(/\.\d\d/)) 
	{ val += '0'; }
	
	val = parseFloat(val);
	val = val.toFixed(2);
	val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	document.getElementById(id).value = '$'+ val;
}