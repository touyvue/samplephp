function validateForm_eb(n,t){var r;if(r=0,document.budget_edit.DPC_trans_date.value==null||document.budget_edit.DPC_trans_date.value=="")return alert("Transaction date is needed."),document.budget_edit.DPC_trans_date.focus(),!1;if(document.budget_edit.amount_ina){if(document.budget_edit.amount_ina.value=="")return alert("An amount is needed."),document.budget_edit.amount_ina.focus(),!1;if(document.budget_edit.amount_ina.value==0)return alert("Amount needs to be greater than 0."),document.budget_edit.amount_ina.value="",document.budget_edit.amount_ina.focus(),!1}if(document.budget_edit.addacct[1].checked==!0)for(r=0,str_val=0,end_val=6,i=0;i<n;i++)temp_id=t.toString().substring(str_val,end_val),document.getElementById("at"+temp_id).checked==!0&&(r+=1),str_val=str_val+6,end_val=end_val+6;if(r==0&&document.budget_edit.addacct[1].checked==!0)return alert("Select an account."),temp_id=t.toString().substring(0,6),setTimeout(function(){document.getElementById("at"+temp_id).focus()},10),!1}function budget_turnon_trans(){document.getElementById("transaction_type").disabled=!1}function add_recurring_ed(n,t,r,u){if(n=="yes"){for(document.budget_edit.DPC_recurring_date.disabled=!1,document.budget_edit.recurring_no.disabled=!1,document.budget_edit.addacct[0].disabled=!1,document.budget_edit.addacct[1].disabled=!1,i=0;i<t;i++)document.budget_edit.recurring_ty[i].disabled=!1;if(r>0)if(str_val=0,end_val=6,document.budget_edit.addacct[0].checked=!1,document.budget_edit.addacct[1].checked==!0)for(i=0;i<r;i++)temp_id=u.toString().substring(str_val,end_val),document.getElementById("at"+temp_id).disabled=!1,document.getElementById("at"+temp_id).checked==!0?(document.getElementById("c"+temp_id).disabled=!1,document.getElementById("d"+temp_id).disabled=!1):(document.getElementById("c"+temp_id).disabled=!0,document.getElementById("d"+temp_id).disabled=!0),str_val=str_val+6,end_val=end_val+6;else for(i=0;i<r;i++)temp_id=u.toString().substring(str_val,end_val),document.getElementById("at"+temp_id).disabled=!0,document.getElementById("at"+temp_id).checked==!0?(document.getElementById("c"+temp_id).disabled=!1,document.getElementById("d"+temp_id).disabled=!1):(document.getElementById("c"+temp_id).disabled=!0,document.getElementById("d"+temp_id).disabled=!0),str_val=str_val+6,end_val=end_val+6}if(n=="no"){for(document.budget_edit.recurring_no.disabled=!0,document.budget_edit.DPC_recurring_date.disabled=!0,document.budget_edit.addacct[0].disabled=!0,document.budget_edit.addacct[1].disabled=!0,document.budget_edit.addacct[0].checked=!1,document.budget_edit.addacct[1].checked=!1,i=0;i<t;i++)document.budget_edit.recurring_ty[i].disabled=!0;if(r>0)for(str_val=0,end_val=6,i=0;i<r;i++)temp_id=u.toString().substring(str_val,end_val),document.getElementById("at"+temp_id).disabled=!0,document.getElementById("c"+temp_id).disabled=!0,document.getElementById("d"+temp_id).disabled=!0,str_val=str_val+6,end_val=end_val+6;document.getElementById("on_acct_insert1").style.display="none";document.getElementById("on_acct_insert2").style.display="none"}}function turnon_no_ed(){document.budget_edit.recurring_no.disabled=!1}function valid_rec_num(n){if(isNaN(parseFloat(n))||!isFinite(n))return alert("Recurring number is not numeric."),document.getElementById("recurring_no").value="",setTimeout(function(){document.getElementById("recurring_no").focus()},10),!1}function turn_credit_debit(n,t,r){for(document.getElementById("at"+n).checked==!0?(document.getElementById("c"+n).disabled=!1,document.getElementById("d"+n).disabled=!1,document.getElementById("d"+n).checked=!0):(document.getElementById("c"+n).checked=!1,document.getElementById("d"+n).checked=!1,document.getElementById("c"+n).disabled=!0,document.getElementById("d"+n).disabled=!0),acct_flag=0,str_val=0,end_val=6,i=0;i<t;i++)temp_id=r.toString().substring(str_val,end_val),document.getElementById("at"+temp_id).checked==!0&&(acct_flag+=1),str_val=str_val+6,end_val=end_val+6;acct_flag==0?(document.budget_edit.addacct[0].checked=!1,document.budget_edit.addacct[1].checked=!1):document.budget_edit.addacct[1].checked=!0}function insertbudget(n,t,i){var e,r,u,f;if(xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP"),n=="no"){for(u=0,f=6,e=0;e<t;e++)r=i.toString().substring(u,f),document.getElementById("at"+r).disabled=!0,document.getElementById("c"+r).disabled=!0,document.getElementById("d"+r).disabled=!0,document.getElementById("at"+r).checked=!1,document.getElementById("c"+r).checked=!1,document.getElementById("d"+r).checked=!1,u=u+6,f=f+6;document.getElementById("on_acct_insert1").style.display="none";document.getElementById("on_acct_insert2").style.display="none"}if(n=="yes"){for(u=0,f=6,e=0;e<t;e++)r=i.toString().substring(u,f),document.getElementById("at"+r).disabled=!1,u=u+6,f=f+6;document.getElementById("on_acct_insert1")&&(document.getElementById("on_acct_insert1").style.display="");document.getElementById("on_acct_insert2").style.display=""}}