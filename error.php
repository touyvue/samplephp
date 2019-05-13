<?php

	include('common/functions/path.php');
	$status = $_SERVER['REDIRECT_STATUS'];
	$codes = array(
	       400 => array('Bad Request', 'Invalid request.'),
	       401 => array('Authorization Required', 'Invalid login credentials.'),
	       403 => array('Forbidden', 'The server has refused to fulfill your request.'),
	       404 => array('Not Found', 'The document/file requested was not found on this server.'),
	       405 => array('Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
	       408 => array('Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
	       500 => array('Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
	       501 => array('Not Implemented', 'The server does not support this requested feature.'),
	       502 => array('Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
	       503 => array('Service Unavailable', 'Server busy, or you might have lost your Internet connection.'),
	       504 => array('Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
	);

	$title = $codes[$status][0];
	$message_error = $codes[$status][1];
	//if ($title == false || strlen($status) != 3) 
	//{
	//       $message_error = 'Request cannot be completed.  Thank you.';
	//}
	
	// Insert headers here
	//echo '<h1>'.$title.'</h1>
	//<p>'.$message.'</p>';

	//send error

	$ip = getenv ("REMOTE_ADDR");
	$requri = getenv ("REQUEST_URI");
	$servname = getenv ("SERVER_NAME");
	$combine = $ip;
	$url = $servname . $requri ;
	$httpref = getenv ("HTTP_REFERER");
	$httpagent = getenv ("HTTP_USER_AGENT");
	$today = date("D M j Y g:i:s a T");
	$message2 = "On:".$today."<br>IP Address:".$ip."<br>Tried to load:".$url."<br>User agent:".$httpagent."<br>Reference:".$httpref;

	$to = "tomyvue@yahoo.com";
	$subject = $status.":".$title;
	$message = $status.":".$title."<br>".$message."<br>".$message2;
	$from = "info@maxmoni.com";
	//$headers = "From:" . $from;
	$headers  = "From: Maxmoni Error<".$from.">\r\n";
	$headers .= "Content-type: text/html\r\n";
	//mail($to,$subject,$message,$headers);
	
	print "<script> self.location='https://www.maxmoni.com'; </script>";
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>MAXMONI :: Easiest to manage and share budget and spending</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Collaborative Budgeting, Budgeting, Expenses Tracking, Maximize Money, Simple Budget, Manage Money" />
  <meta name="keywords" content="Budgeting, Collaborative Budgeting, Monthly Budget, Personal Budget, Personal Finance, Financial Planning, Financial, Finance, Personal Money" />
  <meta name="author" content="MAXMONI">
  
  <!-- Stylesheets -->
  <link href="m_css/bootstrap.min.css" rel="stylesheet">
  <!-- Font awesome icon -->
  <link rel="stylesheet" href="m_css/font-awesome.min.css"> 
  <!-- jQuery UI -->
  <link rel="stylesheet" href="m_css/jquery-ui.css">
  <!-- Main stylesheet -->
  <link href="m_css/style.css" rel="stylesheet">
  <link href="m_css/headpri.css" rel="stylesheet"> 
  <link href="m_css/green.css" rel="stylesheet">  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon/favicon.png">
</head>

<body>	

<!-- Begin head-row1 -->
<div class="navbar navbar-fixed-top bs-docs-nav" role="banner">
    <div class="conjtainer">
      <!-- Begin smallar screen -->
      <div class="navbar-header">
        <span class="navbar-toggle" data-toggle="collapse" data-target=".bs-navbar-collapse">
            <span class="dropdown pull-right">                       
            </span>        
        </span>
            	<a href="index.php" class="navbar-brand hidden-lg"><img src="img/logo-s.png" /><span class="subheadtxt hidden-lg">Easiest budget planning & tracking</span></a>
      </div>
	  <!-- End smallar screen -->
      <!-- Begin large screen -->
      <nav class="collapse navbar-collapse bs-navbar-collapse1" role="navigation">         
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown pull-right">            
            <!-- Begin dropdown menu -->
            <a href="#"><span class="lcolor"><i class="fa fa-user"></i>Login</span> </a>
            <!-- End dropdown menu -->
          </li>
        </ul>
      </nav>
	  <!-- End large screen -->
    </div>
</div>
<!-- End header-row1 -->

<!-- Header starts -->
<header>
    <div class="container">
      <!-- Begin row2 -->
      <div class="row">
        <!-- Begin logo section -->
        <div class="col-md-6">
          <div class="logo">
                <h2><a href="index.php">MAX</a><span><a href="index.php">MONI</a></span></h2>
                <p class="meta">Easiest to share budget and track spending</p>
          </div>
        </div>
		<!-- End logo section -->
      </div>
      <!-- End row2 -->
    </div>
</header>
<!-- Header ends -->
<!-- intro Starts -->
<div class="container">
  <div class="intorp">
    <div class="row">
      <div class="col-md-6 col-sm-6">
        <div class="intropa">
          <h1>Simple Group Budget Tracking</h1>
          <?php if ($title == false || strlen($status) != 3) {  ?>
                    <h5>Request cannot be completed.  Thank you.<br />Click here to go to <a href="https://www.maxmoni.com">Maxmoni.com</a>.</h5>
                    <h3>Maxmoni provides a simple way to manage your monthly budget.</h3>
                <?php } else { ?>
                    <p>&nbsp;</p>
                    <h3>Making financial collaboration simple and productive<br/>
                    for organizations, groups, and family.</h3>
                    <h3>Click here to go to <a href="https://www.maxmoni.com">Maxmoni.com</a>.</h3>
                    <h3>Thank you for visiting Maxmoni.com</h3>
                    <p>&nbsp;</p>
                    <h5><?php echo $message_error ?></h5>
                <?php } ?>	 
                <p>&nbsp;</p><br />
            <h1><span class="label label-warning">Start today! <a href="mailto:info@maxmoni.com"><i class="fa fa-arrow-circle-o-right"></i></a></span></h1>
        </div>
      </div>
      <div class="col-md-6 col-sm-6">
        <div class="intropa">
          <img src="img/techimg-sm.png">
          <p class="stext">Use Maxmoni App in any devices (mobile, tablet & computer)</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- intro Ends -->
<?php
 	include ('m_inc/m_footer_pub.php'); 
?>
</body>
</html>