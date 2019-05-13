
$(document).ready(function(){
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
	
	$("#addCatReports").click(function(){
		categoryid = $("#categoryid").val();
		accountid = $("#accountid").val();
		if(categoryid === null || categoryid == "" ){
			alert("Please choose a category.");
			return false;
		}else{
			$("#subcategoryid").val(categoryid);
		}
		if(accountid === null || accountid == "" ){
			accountid = "";
		}
		$("#subaccountid").val(accountid);
		
	});
	
	$('#categoryaccttbl').dataTable({
	   //"sPaginationType": "full_numbers",
	   //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	   "bPaginate": false,
	   "bInfo": false,
	   "bFilter": false,
	   "aaSorting": [], //not initial pre-sort
	   "aoColumnDefs": [
						{"aTargets": ["ysort"], "bSortable": true },     
						{"aTargets": ["nsort"], "bSortable": false }
						]
	});
	$('#categorytbl').dataTable({
	   //"sPaginationType": "full_numbers",
	   //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	   "bPaginate": false,
	   "bInfo": false,
	   "bFilter": false,
	   "aaSorting": [], //not initial pre-sort
	   "aoColumnDefs": [
						{"aTargets": ["ysort"], "bSortable": true },     
						{"aTargets": ["nsort"], "bSortable": false }
						]
	});
	
	$("#getfilter").click(function(){
		ftgroup = $("#orggrpfilter").val();
		ftstatus = $("#activstatus").val();
		window.location.href = $("#curpage").val()+'&ftg='+ftgroup+'&fts='+ftstatus;
	});
	
	$("#getdatefilter").click(function(){
		staDate = $("#startfilter").val();
		staDate = staDate.replace(/-/g,'');
		endDate = $("#endfilter").val();
		endDate = endDate.replace(/-/g,'');
		window.location.href = $("#curpage").val()+'&pg=efforts&sta='+staDate+'&end='+endDate;
	});
		
});