function isNumber_chk(n,t){if(t.substr(0,1)=="$"&&(t=t.substr(1)),!(!isNaN(parseFloat(t))&&isFinite(t)))return alert("Amount is not numeric."),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10),!1;var i=parseFloat(t.replace(/\$/g,""))+"";return i.match(/\./)||(i+=".00"),i.match(/\.\d\d/)||(i+="0"),i=parseFloat(i),i=i.toFixed(2),document.getElementById(n).value="$"+i,t<=0?(alert("Amount needs to be higher than 0."),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10),!1):void 0}function echeck(n){var i="@",t=".",r=n.indexOf(i),u=n.length,f=n.indexOf(t);return n.indexOf(i)==-1?(alert("Invalid E-mail ID"),!1):n.indexOf(i)==-1||n.indexOf(i)==0||n.indexOf(i)==u?(alert("Invalid E-mail ID"),!1):n.indexOf(t)==-1||n.indexOf(t)==0||n.indexOf(t)==u?(alert("Invalid E-mail ID"),!1):n.indexOf(i,r+1)!=-1?(alert("Invalid E-mail ID"),!1):n.substring(r-1,r)==t||n.substring(r+1,r+2)==t?(alert("Invalid E-mail ID"),!1):n.indexOf(t,r+2)==-1?(alert("Invalid E-mail ID"),!1):n.indexOf(" ")!=-1?(alert("Invalid E-mail ID"),!1):!0}function isNumber_amtina(n,t){if(t.substr(0,1)=="$"&&(t=t.substr(1)),t=t.replace(",",""),!(!isNaN(parseFloat(t))&&isFinite(t)))return alert("Amount is not numeric."),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10),!1;if(t<=0)return alert("Amount must be bigger than $0.00."),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10),!1;var i=parseFloat(t.replace(/\$/g,""))+"";i.match(/\./)||(i+=".00");i.match(/\.\d\d/)||(i+="0");i=parseFloat(i);i=i.toFixed(2);i=i.replace(/(\d)(?=(\d{3})+(?!\d))/g,"$1,");document.getElementById(n).value="$"+i}function isNumberOnly(n,t){if(isNaN(parseFloat(t))||!isFinite(t))return alert("Value is not numeric."),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10),!1}function isNumber_dollar(n){n.substr(0,1)=="$"&&(n=n.substr(1));n=n.replace(",","");var t=parseFloat(n.replace(/\$/g,""))+"";return t.match(/\./)||(t+=".00"),t.match(/\.\d\d/)||(t+="0"),t=parseFloat(t),t=t.toFixed(2),t=t.replace(/(\d)(?=(\d{3})+(?!\d))/g,"$1,"),"$"+t}function todayDate(){var i=new Date,n=i.getDate(),t=i.getMonth()+1,r=i.getFullYear();return n<10&&(n="0"+n),t<10&&(t="0"+t),t+"-"+n+"-"+r}function isNumber_amtouta(n){if(n.substr(0,1)=="$"&&(n=n.substr(1)),!(!isNaN(parseFloat(n))&&isFinite(n)))return alert("Amount is not numeric."),document.getElementById("amount_outa").value="",setTimeout(function(){document.getElementById("amount_outa").focus()},10),!1;var t=parseFloat(n.replace(/\$/g,""))+"";t.match(/\./)||(t+=".00");t.match(/\.\d\d/)||(t+="0");t=parseFloat(t);t=t.toFixed(2);document.getElementById("amount_outa").value="$"+t}function validateForm_nu(){return document.nuser_form.first_name.value==null||document.nuser_form.first_name.value==""?(alert("Please enter name."),document.nuser_form.first_name.focus(),!1):document.nuser_form.email.value==null||document.nuser_form.email.value==""?(alert("Please enter e-mail."),document.nuser_form.email.focus(),!1):echeck(document.nuser_form.email.value)==!1?(document.nuser_form.email.value="",document.nuser_form.email.focus(),!1):document.nuser_form.loginid_new.value==null||document.nuser_form.loginid_new.value==""?(alert("Please enter login ID."),document.nuser_form.loginid_new.focus(),!1):document.nuser_form.loginid_new.value.length<4?(alert("Login ID needs to be 4 charaters or more."),document.nuser_form.loginid_new.value="",document.nuser_form.loginid_new.focus(),!1):document.nuser_form.password_new.value==null||document.nuser_form.password_new.value==""?(alert("Please enter password."),document.nuser_form.password_new.focus(),!1):document.nuser_form.password_new.value.length<8?(alert("Password needs to be 8 characters or more."),document.nuser_form.password_new.value="",document.nuser_form.password_new.focus(),!1):void 0}function chk_exist_email(n,t){var i="com_momax/functions/checkemail.php";i+="?em="+n;i+="&id="+t;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");echeck(document.getElementById("email").value)==!1?(document.getElementById("email").value="",setTimeout(function(){document.getElementById("email").focus()},10)):(xmlhttp.open("GET",i,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1&&(alert("There is already an account associated with this e-mail address."),document.getElementById("email").value="",setTimeout(function(){document.getElementById("email").focus()},10)))}function checklogin(n){xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");n==""?document.getElementById("loginid_new").innerHTML="":n.indexOf(" ")>-1?(alert("login ID cannot contain space"),document.getElementById("loginid_new").value="",setTimeout(function(){document.getElementById("loginid_new").focus()},10)):(xmlhttp.onreadystatechange=function(){xmlhttp.readyState==4&&xmlhttp.status==200&&(returnval=xmlhttp.responseText,returnval==1&&(alert(n+" is taken."),document.getElementById("loginid_new").value="",setTimeout(function(){document.getElementById("loginid_new").focus()},10)))},xmlhttp.open("GET","com_momax/functions/checkloginid.php?eid="+n,!0),xmlhttp.send())}function validate_pass_reset(){return document.reset_pass_frm.new_pass.value==""?(alert("Please enter password."),document.reset_pass_frm.new_pass.focus(),!1):document.reset_pass_frm.new_pass.value.length<8?(alert("Password needs to be 8 characters or more."),document.reset_pass_frm.new_pass.value="",document.reset_pass_frm.new_pass.focus(),!1):void 0}function add_recurring_acct_ed(n,t){if(n=="yes"){for(i=0;i<t;i++)document.etrans_form.recurring_ty[i].disabled=!1;document.getElementById("on_recurring_insert").style.display=""}if(n=="no"){for(document.etrans_form.recurring_no.disabled=!0,document.etrans_form.recurring_no.value="",i=0;i<t;i++)document.etrans_form.recurring_ty[i].disabled=!0,document.etrans_form.recurring_ty[i].checked=!1;document.getElementById("on_recurring_insert").style.display="none"}}function turnon_activity_ed(){document.etrans_form.transaction_type[0].checked==!0&&(document.getElementById("rde_act_type_inc").checked=!0,document.getElementById("dde_act_type_inc").style.display="",document.getElementById("dde_act_type_sav").style.display="none",document.getElementById("dde_act_type_exp").style.display="none");document.etrans_form.transaction_type[1].checked==!0&&(document.getElementById("rde_act_type_exp").checked=!0,document.getElementById("dde_act_type_exp").style.display="",document.getElementById("dde_act_type_inc").style.display="none",document.getElementById("dde_act_type_sav").style.display="none")}function on_budget(n){n=="yes"&&(document.getElementById("on_budget").style.display="",document.getElementById("rd_act_type_inc").checked=!0);n=="no"&&(document.getElementById("on_budget").style.display="none",document.getElementById("rd_act_type_inc").checked=!1,document.getElementById("rd_act_type_sav").checked=!1,document.getElementById("rd_act_type_exp").checked=!1)}function one_budget(n){n=="yes"&&(document.getElementById("one_budget").style.display="",document.getElementById("rde_act_type_inc").checked=!0);n=="no"&&(document.getElementById("one_budget").style.display="none",document.getElementById("rde_act_type_inc").checked=!1,document.getElementById("rde_act_type_sav").checked=!1,document.getElementById("rde_act_type_exp").checked=!1)}function turnon_inc_act_ed(){document.etrans_form.rd_act_type[0].checked==!0&&(document.getElementById("dde_act_type_inc").style.display="",document.getElementById("dde_act_type_sav").style.display="none",document.getElementById("dde_act_type_exp").style.display="none");document.etrans_form.rd_act_type[1].checked==!0&&(document.getElementById("dde_act_type_inc").style.display="none",document.getElementById("dde_act_type_sav").style.display="",document.getElementById("dde_act_type_exp").style.display="none");document.etrans_form.rd_act_type[2].checked==!0&&(document.getElementById("dde_act_type_inc").style.display="none",document.getElementById("dde_act_type_sav").style.display="none",document.getElementById("dde_act_type_exp").style.display="")}function isNumber_start_amt(n){if(n.substr(0,1)=="$"&&(n=n.substr(1)),!(!isNaN(parseFloat(n))&&isFinite(n)))return alert("Amount is not numeric."),document.getElementById("start_amt").value="",setTimeout(function(){document.getElementById("start_amt").focus()},10),!1;var t=parseFloat(n.replace(/\$/g,""))+"";t.match(/\./)||(t+=".00");t.match(/\.\d\d/)||(t+="0");t=parseFloat(t);t=t.toFixed(2);document.getElementById("start_amt").value="$"+t}function validateForm_au(){return document.auser_form.first_name.value==null||document.auser_form.first_name.value==""?(alert("Please enter a name."),document.auser_form.first_name.focus(),!1):document.auser_form.loginid_new.value==null||document.auser_form.loginid_new.value==""?(alert("Please enter login ID."),document.auser_form.loginid_new.focus(),!1):document.auser_form.loginid_new.value.length<4?(alert("Login ID needs to be 4 characters or more."),document.auser_form.loginid_new.value="",document.auser_form.loginid_new.focus(),!1):document.auser_form.password_new.value==null||document.auser_form.password_new.value==""?(alert("Please enter password."),document.auser_form.password_new.focus(),!1):document.auser_form.password_new.value.length<8?(alert("Password needs to be 8 characters or more."),document.auser_form.password_new.value="",document.auser_form.password_new.focus(),!1):document.auser_form.email.value==null||document.auser_form.email.value==""?(alert("Please enter e-mail."),document.auser_form.email.focus(),!1):echeck(document.auser_form.email.value)==!1?(document.auser_form.email.value="",document.auser_form.email.focus(),!1):void 0}function validateForm_uu(){if(document.uuser_form.first_name.value&&(document.uuser_form.first_name.value==null||document.uuser_form.first_name.value==""))return alert("Please enter first name."),document.uuser_form.first_name.focus(),!1;if(document.uuser_form.email.value){if(document.uuser_form.email.value==null||document.uuser_form.email.value=="")return alert("Please enter e-mail."),document.uuser_form.email.focus(),!1;if(echeck(document.uuser_form.email.value)==!1)return document.uuser_form.email.value="",document.uuser_form.email.focus(),!1}}function con_acct_sec(n){document.getElementById("t"+n).checked==!0&&(document.getElementById("l2"+n).disabled=!1,document.getElementById("l2"+n).checked=!0,document.getElementById("l3"+n).disabled=!1,document.getElementById("l3"+n).checked=!1,document.getElementById("l4"+n).disabled=!1,document.getElementById("l4"+n).checked=!1);document.getElementById("t"+n).checked==!1&&(document.getElementById("l2"+n).checked=!1,document.getElementById("l2"+n).disabled=!0,document.getElementById("l3"+n).checked=!1,document.getElementById("l3"+n).disabled=!0,document.getElementById("l4"+n).checked=!1,document.getElementById("l4"+n).disabled=!0)}function chk_budget_sec(){document.uuser_form.grant_budget.checked==!0&&(document.uuser_form.budget_sl[0].disabled=!1,document.uuser_form.budget_sl[0].checked=!0,document.uuser_form.budget_sl[1].disabled=!1,document.uuser_form.budget_sl[1].checked=!1,document.uuser_form.budget_sl[2].disabled=!1,document.uuser_form.budget_sl[2].checked=!1);document.uuser_form.grant_budget.checked==!1&&(document.uuser_form.budget_sl[0].checked=!1,document.uuser_form.budget_sl[0].disabled=!0,document.uuser_form.budget_sl[1].checked=!1,document.uuser_form.budget_sl[1].disabled=!0,document.uuser_form.budget_sl[2].checked=!1,document.uuser_form.budget_sl[2].disabled=!0)}function chk_budget_inv(){document.crinvite_form.grant_budget.checked==!0&&(document.crinvite_form.budget_sl[0].disabled=!1,document.crinvite_form.budget_sl[0].checked=!0,document.crinvite_form.budget_sl[1].disabled=!1,document.crinvite_form.budget_sl[2].disabled=!1);document.crinvite_form.grant_budget.checked==!1&&(document.crinvite_form.budget_sl[0].checked=!1,document.crinvite_form.budget_sl[0].disabled=!0,document.crinvite_form.budget_sl[1].checked=!1,document.crinvite_form.budget_sl[1].disabled=!0,document.crinvite_form.budget_sl[2].checked=!1,document.crinvite_form.budget_sl[2].disabled=!0)}function con_acct_sec_chk(n){(document.getElementById("l2"+n).checked==!0||document.getElementById("l3"+n).checked==!0||document.getElementById("l4"+n).checked==!0)&&(document.getElementById("t"+n).checked=!0)}function validateForm_uuSearch(){return document.uuserSearch_form.loginid.value==null||document.uuserSearch_form.loginid.value==""?(alert("Please enter login ID."),document.uuserSearch_form.loginid.focus(),!1):document.uuserSearch_form.first_name.value==null||document.uuserSearch_form.first_name.value==""?(alert("Please enter first name."),document.uuserSearch_form.first_name.focus(),!1):document.uuserSearch_form.last_name.value==null||document.uuserSearch_form.last_name.value==""?(alert("Please enter last name."),document.uuserSearch_form.last_name.focus(),!1):void 0}function validateForm_ma(){return document.myacct_form.first_name.value==null||document.myacct_form.first_name.value==""?(alert("Please enter name."),document.myacct_form.first_name.focus(),!1):document.myacct_form.email.value==null||document.myacct_form.email.value==""?(alert("Please enter e-mail."),document.myacct_form.email.focus(),!1):echeck(document.myacct_form.email.value)==!1?(document.myacct_form.email.value="",document.myacct_form.email.focus(),!1):document.myacct_form.loginid_new.value.length<4?(alert("Login ID needs to be 4 characters or more."),document.myacct_form.loginid_new.value="",document.myacct_form.loginid_new.focus(),!1):document.myacct_form.password.value==null||document.myacct_form.password.value==""?(alert("Please enter password."),document.myacct_form.password.focus(),!1):document.myacct_form.password.value.length<8?(alert("Password needs to be 8 characters or more."),document.myacct_form.password.value="",document.myacct_form.password.focus(),!1):void 0}function beta_user_reg(n,t){var i="com_momax/functions/checkemail.php";i+="?em="+n;i+="&id="+t;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");echeck(document.getElementById("email").value)==!1?(document.getElementById("email").value="",setTimeout(function(){document.getElementById("email").focus()},10)):(xmlhttp.open("GET",i,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1&&(alert("There is already an account associated with this e-mail address."),document.getElementById("email").value="",setTimeout(function(){document.getElementById("email").focus()},10)))}function check_password(n,t){t.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/)||(alert("Password must be 6-10 characters, at least one number, one lowercase and one uppercase letter"),document.getElementById(n).value="",setTimeout(function(){document.getElementById(n).focus()},10))}function val_new_grp(){return document.grp_form.grp_nam.value==null||document.grp_form.grp_nam.value==""?(alert("Please enter group name."),document.grp_form.grp_nam.focus(),!1):document.grp_form.email.value==null||document.grp_form.email.value==""?(alert("Please enter e-mail."),document.grp_form.email.focus(),!1):echeck(document.grp_form.email.value)==!1?(document.grp_form.email.value="",document.grp_form.email.focus(),!1):void 0}function val_new_user(){return document.grp_user_form.email.value==null||document.grp_user_form.email.value==""?(alert("Please enter e-mail."),document.grp_user_form.email.focus(),!1):echeck(document.grp_user_form.email.value)==!1?(document.grp_user_form.email.value="",document.grp_user_form.email.focus(),!1):void 0}function chk_acct_name(n,t){var i="com_momax/functions/chk_acct_name.php";i+="?em="+n+"&id="+t;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");document.getElementById("acct_type").value!=""&&(xmlhttp.open("GET",i,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1&&(alert("There is already an account with this name."),document.getElementById("acct_type").value="",setTimeout(function(){document.getElementById("acct_type").focus()},10)))}function chk_act_name(n,t){var i="com_momax/functions/chk_act_name.php";i+="?em="+n+"&id="+t;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");document.getElementById("act_type").value!=""&&(xmlhttp.open("GET",i,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1&&(alert("There is already an activity with this name."),document.getElementById("act_type").value="",setTimeout(function(){document.getElementById("act_type").focus()},10)))}function chk_grp_code(n,t,i){var r="com_momax/functions/chk_grpcode.php";r+="?v_new="+n+"&v_org="+t+"&id="+i;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");document.getElementById("grp_cod").value!=""&&(xmlhttp.open("GET",r,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1&&(alert("Group subscription is not available."),document.getElementById("grp_cod").value=t,setTimeout(function(){document.getElementById("grp_cod").focus()},10)),returnval==2&&(alert("Group Code is not available."),document.getElementById("grp_cod").value=t,setTimeout(function(){document.getElementById("grp_cod").focus()},10)))}function submitacct(n){if(document.acctupdate_form.acct_type.value!="")if(document.acctupdate_form.acct_desc.value!="")if(document.acctupdate_form.acct_desc.value.length<500&&document.acctupdate_form.acct_desc.value.length>0)if(n==0)if(document.acctupdate_form.start_amt.value!="")if(n>=0)document.acctupdate_form.start_amt.value.substr(0,1)=="$"&&(document.acctupdate_form.start_amt.value=document.acctupdate_form.start_amt.value.substr(1)),document.forms.acctupdate_form.submit();else{alert("Please enter a positive amount.");document.acctupdate_form.start_amt.value="";document.acctupdate_form.start_amt.focus();return}else{alert("Please enter a starting amount.");document.acctupdate_form.start_amt.value="";document.acctupdate_form.start_amt.focus();return}else document.forms.acctupdate_form.submit();else{alert("Description needs to be less than 500 charaters.");document.acctupdate_form.acct_desc.focus();return}else{alert("Please enter account description.");document.acctupdate_form.acct_desc.focus();return}else{alert("Please enter account name.");document.acctupdate_form.acct_type.focus();return}}function del_acct_warnming(){var n=confirm("Are you sure you want to delete this account?");return n==!0?!0:!1}function submittrans(){if(document.transupdate_form.trans_type.value!="")if(document.transupdate_form.trans_desc.value!="")if(document.transupdate_form.trans_desc.value.length<500||document.transupdate_form.trans_desc.value.length>0)document.forms.transupdate_form.submit();else{alert("Description needs to be less than 500 charaters.");document.transupdate_form.trans_desc.focus();return}else{alert("Please enter account description.");document.transupdate_form.trans_desc.focus();return}else{alert("Please enter account type.");document.transupdate_form.trans_type.focus();return}}function submitact(){if(document.actupdate_form.act_type.value!="")if(document.actupdate_form.act_desc.value!="")if(document.actupdate_form.act_desc.value.length<500||document.actupdate_form.act_desc.value.length>0)document.forms.actupdate_form.submit();else{alert("Description needs to be less than 500 charaters.");document.actupdate_form.act_desc.focus();return}else{alert("Please enter account description.");document.actupdate_form.act_desc.focus();return}else{alert("Please enter account type.");document.actupdate_form.act_type.focus();return}}function submitsav(){if(document.savupdate_form.sav_type.value!="")if(document.savupdate_form.sav_desc.value!="")if(document.savupdate_form.sav_desc.value.length<500||document.savupdate_form.sav_desc.value.length>0)document.forms.savupdate_form.submit();else{alert("Description needs to be less than 500 charaters.");document.savupdate_form.sav_desc.focus();return}else{alert("Please enter saving description.");document.savupdate_form.sav_desc.focus();return}else{alert("Please enter saving type.");document.savupdate_form.sav_type.focus();return}}function check_updatelogin(n,t){var i="com_momax/functions/check_updateloginid.php";i+="?eid="+n;i+="&uid="+t;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");n==""?(document.getElementById("loginid").innerHTML="",document.getElementById("loginchk").innerHTML="Please enter login ID"):(xmlhttp.onreadystatechange=function(){xmlhttp.readyState==4&&xmlhttp.status==200&&(returnval=xmlhttp.responseText,returnval==1?(document.getElementById("loginchk").innerHTML=" <b>"+n+"<\/b> is not available!",document.getElementById("loginid").value="",setTimeout(function(){document.getElementById("loginid").focus()},10)):document.getElementById("loginchk").innerHTML="")},xmlhttp.open("GET",i,!0),xmlhttp.send())}function deactive_user(n){document.getElementById("chk"+n).checked==!0?call_user(1,n):call_user(0,n)}function call_user(n,t){xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");xmlhttp.onreadystatechange=function(){xmlhttp.readyState==4&&xmlhttp.status==200&&(returnval=xmlhttp.responseText,change=returnval.substr(0,1),value=returnval.substr(1,7),change==2&&(document.getElementById("u"+value).innerHTML="Active"),change==1&&(document.getElementById("u"+value).innerHTML="Inactive"))};xmlhttp.open("GET","com_momax/functions/activeuser.php?act="+n+"&uid="+t,!0);xmlhttp.send()}function editbalance(n,t,r){var e,o,f,u;if(t==0){var f=document.getElementById("n"+n).lastChild.nodeValue,u=document.getElementById("m"+n).lastChild.nodeValue,s=document.getElementById("a"+n).lastChild.nodeValue;document.getElementById("n"+n).innerHTML="";document.getElementById("m"+n).innerHTML="";e=document.createElement("input");o=document.createElement("input");e.setAttribute("value",f);e.setAttribute("id","in"+n);o.setAttribute("value",u.substr(1).replace(/,/g,""));o.setAttribute("id","im"+n);document.getElementById("n"+n).appendChild(e);document.getElementById("m"+n).appendChild(o);document.getElementById("a"+n).innerHTML="<a href='javascript: editbalance("+n+",2,2)'>update<\/a> / <a href='javascript: editbalance("+n+",1,2)'>delete<\/a>"}if(t==1&&(r==1?(f=document.getElementById("n"+n).lastChild.nodeValue,u=document.getElementById("m"+n).lastChild.nodeValue):(f=document.getElementById("in"+n).value,u=document.getElementById("im"+n).value),update_bal(n,t,f,u)),t==2&&(f=document.getElementById("in"+n).value,u=document.getElementById("im"+n).value,!isNaN(parseFloat(u))&&isFinite(u)?(update_bal(n,t,f,u),u=formatCurrency(u),document.getElementById("n"+n).innerHTML=f,document.getElementById("m"+n).innerHTML=u,document.getElementById("a"+n).innerHTML="<a href='javascript: editbalance("+n+",0,0)'>edit<\/a> / <a href='javascript: editbalance("+n+",1,1)'>delete<\/a>"):(alert("Amount is not numeric."),document.getElementById("im"+n).value="",setTimeout(function(){document.getElementById("im"+n).focus()},10))),n==0)if(f=document.getElementById("bal_name_ca").value,u=document.getElementById("bal_amount_ca").value,!isNaN(parseFloat(u))&&isFinite(u))for(i=5;i<10;i++)t==i&&update_bal(r,i,f,u);else alert("Amount is not numeric."),document.getElementById("bal_amount_ca").value="",setTimeout(function(){document.getElementById("bal_amount_ca").focus()},10)}function update_bal(n,t,i,r){xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");xmlhttp.onreadystatechange=function(){xmlhttp.readyState==4&&xmlhttp.status==200&&(returnval=xmlhttp.responseText,returnval==1&&(alert("Record has been deactivated!"),window.location.reload(!0)),returnval==2&&(alert("Record has been updated!"),window.location.reload(!0)),returnval==5&&(self.location="http://localhost/fpcompass/index.php?pa=fp007&act=0"))};xmlhttp.open("GET","com_momax/functions/updatebal.php?act="+t+"&bid="+n+"&bnam="+i+"&bamt="+r,!0);xmlhttp.send()}function formatCurrency(n){n=n.toString().replace(/\$|\,/g,"");isNaN(n)&&(n="0");sign=n==(n=Math.abs(n));n=Math.floor(n*100+.50000000001);cents=n%100;n=Math.floor(n/100).toString();cents<10&&(cents="0"+cents);for(var t=0;t<Math.floor((n.length-(1+t))/3);t++)n=n.substring(0,n.length-(4*t+3))+","+n.substring(n.length-(4*t+3));return(sign?"":"-")+"$"+n+"."+cents}function getNumber(n){return num=0,kugiri=new RegExp(",","g"),num=n.replace(kugiri,""),num=num.replace("\\",""),num=Number(num)}function update_lacct(){return document.eloan_form.loan_name.value==""?(alert("Loan name is needed."),document.eloan_form.loan_name.focus(),!1):document.eloan_form.creditor.value==""?(alert("Creditor is needed."),document.eloan_form.creditor.focus(),!1):(!isNaN(parseFloat(document.eloan_form.loan_amount.value))&&isFinite(document.eloan_form.loan_amount.value))?(!isNaN(parseFloat(document.eloan_form.loan_rate.value))&&isFinite(document.eloan_form.loan_rate.value))?(!isNaN(parseFloat(document.eloan_form.loan_term.value))&&isFinite(document.eloan_form.loan_term.value))?(!isNaN(parseFloat(document.eloan_form.add_payment.value))&&isFinite(document.eloan_form.add_payment.value))?void 0:(alert("Addition payment is not numeric."),document.eloan_form.add_payment.value="",document.eloan_form.add_payment.focus(),!1):(alert("Loan term is not numeric."),document.eloan_form.loan_term.value="",document.eloan_form.loan_term.focus(),!1):(alert("Loan rate is not numeric."),document.eloan_form.loan_rate.value="",document.eloan_form.loan_rate.focus(),!1):(alert("Loan amount is not numeric."),document.eloan_form.loan_amount.value="",document.eloan_form.loan_amount.focus(),!1)}function edit_addpayment(n,t,i,r,u){var f,e;xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");u==1&&(document.getElementById(r).innerHTML="",f=document.createElement("input"),f.setAttribute("value",i),f.setAttribute("id","i"+r),f.setAttribute("size","5"),document.getElementById(r).appendChild(f),document.getElementById("l"+r).innerHTML="&nbsp;<a href='javascript: edit_addpayment("+n+","+t+","+i+","+r+",2)'>update<\/a>");u==2&&(e=document.getElementById("i"+r).value,!isNaN(parseFloat(e))&&isFinite(e)?(xmlhttp.onreadystatechange=function(){xmlhttp.readyState==4&&xmlhttp.status==200&&(returnval=xmlhttp.responseText,returnval==1&&window.location.reload(!0),returnval==2&&window.location.reload(!0))},xmlhttp.open("GET","com_momax/functions/updateloan.php?lid="+n+"&laid="+t+"&ym="+r+"&amt="+e,!0),xmlhttp.send()):(alert("Amount is not numeric."),document.getElementById("i"+r).value="",setTimeout(function(){document.getElementById("i"+r).focus()},10)))}function validateReport(n,t,r){var e,f,u;if(f="no",document.report_form.from_date.value!=""||document.report_form.to_date.value!=""){if(document.report_form.to_date.value=="")return alert("Transaction (to) date is needed!"),!1;if(document.report_form.from_date.value=="")return alert("Transaction (from) date is needed!"),!1}if(document.report_form.from_date.value>document.report_form.to_date.value)return alert("From Date cannot be after To Date"),!1;if(document.getElementById("budget_options").checked&&r=="account"||document.getElementById("account_options").checked&&r=="budget")return alert("Please click on the selected report!"),!1;if(document.getElementById("budget_options").checked||document.getElementById("account_options").checked){if(r=="budget"){for(u=0,i=0;i<t;i++)e=n.substr(u,6),u=u+6,document.getElementById(e).checked&&(f="yes");if(f=="no")return alert("Please choose an account!"),!1;document.report_form.submit()}r=="account"&&document.report_form.submit()}else return alert("Please choose a report option!"),!1}function report_off_no(n,t){var u,r;if(document.getElementById("account_options").checked){for(r=0,i=0;i<t;i++)u=n.substr(r,6),r=r+6,document.getElementById(u).checked=!1,document.getElementById(u).disabled=!0;document.getElementById("unposted_comp").checked=!1;document.getElementById("unposted_comp").disabled=!0;document.getElementById("group_by_cat").disabled=!1;document.getElementById("unposted").disabled=!1}if(document.getElementById("budget_options").checked)for(document.getElementById("group_by_cat").checked=!1,document.getElementById("group_by_cat").disabled=!0,document.getElementById("unposted").checked=!1,document.getElementById("unposted").disabled=!0,document.getElementById("unposted_comp").disabled=!1,r=0,i=0;i<t;i++)u=n.substr(r,6),r=r+6,document.getElementById(u).disabled=!1}function turnon_act_dd(n,t){var u,f,r;for(r=0,n=n.toString(),u=n.substr(6,6),document.getElementById("act"+u).style.display="",i=0;i<t.length;i++)f=t.substr(r,6),f!=u&&(document.getElementById("act"+f).style.display="none"),r=r+6,i=r}function isNumber_amtsave(n,t){if(!(!isNaN(parseFloat(n))&&isFinite(n))&&n!="")return alert("Amount In is not numeric."),document.getElementById(t).value="",setTimeout(function(){document.getElementById(t).focus()},10),!1}function re_trans_type(){return confirm("Are you sure you want to remove this?")}function create_invited_id(n){for(var i,f=n.substring(0,2),e=n.substring(2,4),o=n.substring(4,6),r="0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz",t="",u=0;u<20;u++)i=Math.floor(Math.random()*r.length),t+=r.substring(i,i+1);var s=t.substring(0,5),h=t.substring(5,10),c=t.substring(10,15),l=t.substring(15,20);document.invited_form.invited_id.value=s+f+h+e+c+o+l}function validate_invite(){return document.invited_form.invited_id.value==""?(alert("Please generate an invited ID."),!1):document.invited_form.owner_email.value==""?(alert("Owner's e-mail is needed."),!1):echeck(document.invited_form.owner_email.value)==!1?(document.invited_form.owner_email.value="",document.invited_form.owner_email.focus(),!1):void 0}function check_owneremail(n,t){var i="com_momax/functions/check_ownemail_inv.php";if(i+="?em="+n,i+="&id="+t,xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP"),n=="")document.getElementById("owner_email").innerHTML="",document.getElementById("emailchk").innerHTML="Please enter owner e-mail.";else{if(xmlhttp.open("GET",i,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval==1)return document.getElementById("emailchk").innerHTML="<br>No group owner email <b>"+n+"<\/b> found.<br>",document.getElementById("owner_email").value="",setTimeout(function(){document.getElementById("owner_email").focus()},10),!1;if(returnval==2)return document.getElementById("emailchk").innerHTML="<br>You already joined with <b>"+n+"<\/b>.<br>",document.getElementById("owner_email").value="",setTimeout(function(){document.getElementById("owner_email").focus()},10),!1;if(returnval==3)return document.getElementById("emailchk").innerHTML="<br>You cannot request to yourself.<br>",document.getElementById("owner_email").value="",setTimeout(function(){document.getElementById("owner_email").focus()},10),!1;returnval!=1&&returnval!=2&&returnval!=3&&(document.getElementById("emailchk").innerHTML="")}}function check_invitecode(n,t){n==""&&(document.getElementById("i"+t).value="",alert("Please enter invited code."),setTimeout(function(){document.getElementById("i"+t).focus()},10))}function value_invitecode(n,t){return(xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP"),n=="")?(alert("Please enter invited code."),!1):(xmlhttp.open("GET","com_momax/functions/check_invitecode.php?code="+n,!1),xmlhttp.send(null),returnval=xmlhttp.responseText,returnval=="1")?(alert("No invited code match!"),document.getElementById("i"+t).value="",setTimeout(function(){document.getElementById("i"+t).focus()},10),!1):void 0}function fileyype(n,t,i){t&&(dots=t.split("."),fileType=dots[dots.length-1],i.join(".").indexOf(fileType)==-1?(alert("Please only upload files that end in types: \n\n"+i.join(" .")+"\n\nPlease select a new file and try again."),document.getElementById(n).form.reset(),document.getElementById(n).focus()):(lastIndex=t.lastIndexOf("\\"),lastIndex>=0&&(fname=t.substring(lastIndex+1)),n=="cmx_self"&&(document.getElementById("c_uploadFile").value=fname),n=="cd1mx_self"&&(document.getElementById("cd1_uploadFile").value=fname),n=="cd2mx_self"&&(document.getElementById("cd2_uploadFile").value=fname),n=="mx_self"&&(document.getElementById("uploadFile").value=fname)))}function toggle(n){var t=document.getElementById(n);t.style.display=t.style.display=="none"?"block":"none"}function window_pos(n){viewportwidth=typeof innerWidth!="undefined"?window.innerHeight:document.documentElement.clientHeight;window_width=viewportwidth>document.body.parentNode.scrollWidth&&viewportwidth>document.body.parentNode.clientWidth?viewportwidth:document.body.parentNode.clientWidth>document.body.parentNode.scrollWidth?document.body.parentNode.clientWidth:document.body.parentNode.scrollWidth;var t=document.getElementById(n);window_width=window_width/2-150;t.style.left=window_width+"px"}function popup(n){toggle("terms_blanket");toggle(n)}function setval_budact(){var e=document.bud_vs_acct_form.all_acct_id.value,r=document.bud_vs_acct_form.acct_ct.value,u=0,n=6,t=0,f;if(r>0){for(i=0;i<r;i++)f=e.substring(u,n),document.getElementById(f).checked==!0&&(t=t+1),u=n,n=n+6;if(t==0)return alert("Please choose at least one account."),!1}else return alert("No account found."),!1}