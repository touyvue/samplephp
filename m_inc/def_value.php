<?php		
	// general links //
	//$start_p = "https://www.maxmoni.com/";
	//$start_login = "https://www.maxmoni.com/";
	$start_p = "http://localhost/maxmoni/";
	$start_login = "http://localhost/maxmoni/";
    $main_page = $start_p;
	$main_js = "m_js/function.js";
	$index_url_out = $start_p;  //in the header page
	$index_url = $start_login;
	
	$p_guest = "m_inc/get_guest.php";
	
	$headpub_css = "m_css/headpub.css";
	$headpri_css = "m_css/headpri.css";

	// images path //
	$logo = "images/mg_logo.jpg";
	$mg_b_check = "images/mg_b_check.gif";
	$mg_blank = "images/mg_blank.gif";
	$m_beta = "images/m_beta.png";
	$m_beta1 = "images/m_beta1.png";
	$m_home_bg = "images/mx_home_bg.gif";
	$m_budgeting = "images/budgeting1.png";
	$m_saving = "images/saving1.png";
	$m_tracking = "images/tracking1.png";
	$m_main_page = "images/m_main_page.jpg";
	$m_b_check = "images/m_b_check.gif";
	$m_new_trans = "images/m_add_trans.jpg";
	$m_sign_up_now = "images/m_sign_up_now.jpg";
	$rotate_words = "images/rotate_words.gif";
	$rotate_words1 = "images/rotate_words1.gif";
	$m_img_path = "images/m_pic/";
	$m_attach_path = "in_attach/";
	$m_attach = "images/m_attach.jpg";
	
	//profile images path
	$imgProfile = "images/m_pic/"; //not use yet 7/22/15

	// database definition //
	$db_account = "a_account";
	$db_account_rights = "a_account_rights";
	$db_category = "a_category";
	$db_recurring = "a_recurring";
	$db_recurring_group = "a_recurring_group";
	$db_transaction = "a_transaction";
	$db_transaction_attach = "a_transaction_attach";
	$db_transaction_type = "a_transaction_type";
	$db_budget = "b_budget";
	$db_budgetlist = "b_budgetlist";
	$db_budgetlist_detail = "b_budgetlist_detail";
	$db_budgetlist_rights = "b_budgetlist_rights";
	$db_budget_detail = "b_budget_detail"; //not use 7/23/15
	$db_budget_rights = "b_budget_rights";
	$db_budtransaction = "b_budtransaction";
	$db_project = "b_project";
	$db_project_detail = "b_project_detail";
	$db_project_rights = "b_project_rights";
	$db_tag = "b_tag";
	$db_consortium = "g_consortium";
	$db_consortium_pix = "g_consortium_pix";
	$db_group = "g_group";
	$db_group_member = "g_group_member"; //not use 7/29/15
	$db_group_pix = "g_group_pix";
	$db_group_rights = "g_group_rights";
	$db_group_rights_type = "g_group_rights_type";
	$db_license = "g_license";
	$db_license_type = "g_license_type";
	$db_member = "g_member";
	$db_memberbudget = "g_memberbudget";
	$db_memberbudget_amt = "g_memberbudget_amt";
	$db_memberplus = "g_memberplus";
	$db_membertrack = "g_membertrack";
	$db_membertrack_amt = "g_membertrack_amt";
	$db_member_pix = "g_member_pix";
	$db_organization = "g_organization";
	$db_organization_pix = "g_organization_pix";
	$db_setting = "g_setting";
	$db_guest = "o_guest_info";
	$db_inout = "o_inout";
	$db_invite = "o_invite";
	$db_reset_log = "o_reset_log";
	$db_post = "p_post";
	$db_post_group = "p_post_group";
	$db_post_rights = "p_post_rights";
	$db_post_rights_type = "p_post_rights_type";
	$db_request = "p_request";
	$db_grpattend = "t_grpattend";
	$db_grpattend_sub = "t_grpattend_sub";
	$db_mileage = "t_mileage";
	$db_mileage_detail = "t_mileage_detail";
	$db_track = "t_track";
	$db_track_detail = "t_track_detail";
	$db_trkcategory = "t_trkcategory";
	$db_trkmember = "t_trkmember";
	$db_trkmember_detail = "t_trkmember_detail";
			
	//admin setting values
	$beginFiscalYDay = 01; //start day of fiscal year
	$endFiscalYDay = 01; //end day of fiscal year
	$beginFiscalYMonth = 07; //start month of fiscal year
	$endFiscalYMonth = 01; //end month of fiscal year
	$numMonthShow = 12; //number of months display for budgetsheet

	// Level 001 general links //
	$p_code = get_link_code();
	$logout = $start_login."?id=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."00".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx00100 = $start_p."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."01".substr($p_code,10,5)."00".substr($p_code,15,5);	
	$p_code = get_link_code();
	$mx001lg = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."01".substr($p_code,10,5)."lg".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx001fp = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."01".substr($p_code,10,5)."fp".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx001hp = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."01".substr($p_code,10,5)."hp".substr($p_code,15,5);
	
	// Level 002 budget links //
	$p_code = get_link_code();
	$mx002 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."02".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx002gb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."02".substr($p_code,10,5)."gb".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx002mb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."02".substr($p_code,10,5)."mb".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx002pb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."02".substr($p_code,10,5)."pb".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx002pj = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."02".substr($p_code,10,5)."pj".substr($p_code,15,5);

	// Level 003 account links //
	$p_code = get_link_code();
	$mx003 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."03".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx003ac = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."03".substr($p_code,10,5)."ac".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx003av = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."03".substr($p_code,10,5)."av".substr($p_code,15,5);

	// Level 004 reports links //mx004ov
	$p_code = get_link_code();
	$mx004 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx004ba = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."ba".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx004aa = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."aa".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx004as = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."as".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx004ct = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."ct".substr($p_code,15,5);	
	$p_code = get_link_code();
	$mx004tg = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."tg".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx004tk = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."04".substr($p_code,10,5)."tk".substr($p_code,15,5);

	// Level 005 admin links //
	$p_code = get_link_code();
	$mx005 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005as = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."as".substr($p_code,15,5);	
	$p_code = get_link_code();
	$mx005bl = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."bl".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005bs = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."bs".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005cs = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."cs".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005ct = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."ct".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005gs = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."gs".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005gt = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."gt".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005ot = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."ot".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005ps = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."ps".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005pt = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."pt".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005pu = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."pu".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005sa = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."sa".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005sb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."sb".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005sp = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."sp".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005tc = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."tc".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx005tt = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."05".substr($p_code,10,5)."tt".substr($p_code,15,5);

	// Level 006 project links //
	$p_code = get_link_code();
	$mx006 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."06".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx006pj = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."06".substr($p_code,10,5)."pj".substr($p_code,15,5);

	// Level 007 message links //
	$p_code = get_link_code();
	$mx007 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."07".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx007fb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."07".substr($p_code,10,5)."fb".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx007tb = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."07".substr($p_code,10,5)."tb".substr($p_code,15,5);

	// Level 008 outside links //
	$p_code = get_link_code();
	$mx008cp = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."08".substr($p_code,10,5)."cp".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx008lp = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."08".substr($p_code,10,5)."lp".substr($p_code,15,5);

	// Level 009 tracking links //
	$p_code = get_link_code();
	$mx009 = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."09".substr($p_code,10,5)."00".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx009gt = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."09".substr($p_code,10,5)."gt".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx009ml = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."09".substr($p_code,10,5)."ml".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx009mt = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x0".substr($p_code,8,2)."09".substr($p_code,10,5)."mt".substr($p_code,15,5);

	// Level members //
	$p_code = get_link_code();
	$mx112ci = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x1".substr($p_code,8,2)."12".substr($p_code,10,5)."ci".substr($p_code,15,5);
	$p_code = get_link_code();
	$mx112dm = $start_login."?pa=".substr($p_code,0,5)."m".substr($p_code,5,3)."x1".substr($p_code,8,2)."12".substr($p_code,10,5)."dm".substr($p_code,15,5);

?>