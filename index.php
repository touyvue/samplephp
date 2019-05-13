<?php 
	//header('Location: https://www.corecision.com/');
	include('m_inc/head_check.php');
	include('m_js/chk_js.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and others -->
  <title>MAXMONI :: A very simple way for groups to track money real-time.</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Collaborative Budgeting, Budgeting, Expenses Tracking, Maximize Money, Simple Budget, Manage Money" />
  <meta name="keywords" content="Budgeting, Collaborative Budgeting, Monthly Budget, Personal Budget, Personal Finance, Financial Planning, Financial, Finance, Personal Money" />
  <meta name="author" content="MAXMONI">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="m_css/bootstrap.min.css" >
  <link rel="stylesheet" href="m_css/font-awesome.min.css"> 
  <link rel="stylesheet" href="m_css/jquery-ui.css">
  <link rel="stylesheet" href="m_css/fullcalendar.css">
  <link rel="stylesheet" href="m_css/prettyPhoto.css">  
  <link rel="stylesheet" href="m_css/rateit.css">
  <link rel="stylesheet" href="m_css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="m_css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="m_css/jquery.cleditor.css">  
  <link rel="stylesheet" href="m_css/jquery.dataTables.css">
  <link rel="stylesheet" href="m_css/jquery.onoff.css">
  <!-- Main stylesheet -->
  <link rel="stylesheet" href="m_css/style.css" >
  <link rel="stylesheet" href="<?php echo $logheader ?>" >
  <link rel="stylesheet" href="m_css/widgets.css" >  
  <link rel="stylesheet" href="m_css/green.css" >
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <![endif]-->
  <!-- JS -->
  <script type="text/javascript" src="js/respond.min.js"></script>
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script> 
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script> 
  <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
  <script type="text/javascript" src="<?php echo $main_js ?>"></script> 
  <script type="text/javascript" src="<?php echo $js_dat_file ?>"></script>
  <script type="text/javascript" src="<?php echo $js_base ?>"></script>
  <script type="text/javascript" src="<?php echo $js_file ?>"></script>
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon/favicon.png">
</head>
<body>
<?php 
	include ('m_inc/header.php'); 
	include ('m_content/content.php');
 	include ($logfooter); 
?>	
<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 

<?php if ($_SESSION['login']=="yes" and $_SESSION['mid']!="") { ?>
<!-- JS -->
<script src="js/fullcalendar.min.js"></script> 
<script src="js/jquery.rateit.min.js"></script> 
<script src="js/jquery.prettyPhoto.js"></script> 
<script src="js/jquery.slimscroll.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<!-- jQuery Flot -->
<script src="js/excanvas.min.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.time.js"></script> 
<script src="js/jquery.flot.symbol.js"></script>
<script src="js/jquery.flot.axislabels.js"></script>
<script src="js/jquery.flot.valuelabels.js"></script>
<script src="js/jquery.flot.barnumbers.js"></script>
<!-- jQuery Notification - Noty -->
<script src="js/jquery.noty.js"></script> 
<script src="js/themes/default.js"></script> 
<script src="js/layouts/bottom.js"></script> 
<script src="js/layouts/topRight.js"></script> 
<script src="js/layouts/top.js"></script> 
<!-- jQuery Notification ends -->
<script src="js/sparklines.js"></script> 
<script src="js/jquery.cleditor.min.js"></script> 
<script src="js/jquery.onoff.min.js"></script> 
<script src="js/filter.js"></script> 
<script src="js/custom.js"></script> 
<script src="js/charts.js"></script> 

<?php } ?>

</body>
</html>
<?php if($active_page=="mx001lg"){ ?>
<script type="text/javascript">
	window.onload=my_load_login;
</script>
<?php } ?>