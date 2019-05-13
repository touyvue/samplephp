<?php
	session_start();
	ini_set('session.bug_compat_warn', 0); 
	ini_set('session.bug_compat_42', 0); 
	ini_set( 'error_reporting', E_ALL ^ E_NOTICE );
	ini_set( 'display_errors', '0' );
	
	include ('m_inc/m_config.php'); //mysql cridential	
	include('m_class/class_lib.php'); //main classes
	include ('m_inc/def_value.php'); //variables definition
	require 'm_inc/p_hash.php'; //hashing login
	include ('m_inc/checker.php'); //check login credentials
	
	//switch header style 
	if ($_SESSION['login']=="no" or $_SESSION['login']=="") {
		$logheader = "m_css/headpub.css"; //public
		$logfooter = "m_inc/m_footer_pub.php"; //public
	} else{
		$logheader = "m_css/headpri.css"; //private
		$logfooter = "m_inc/m_footer_clt.php"; //private
	}
?>