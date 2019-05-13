<?php
	$active_page = $_GET["pa"];
	$member_id = $_SESSION['member_id']; 
	$active_page = substr($active_page,5,1).substr($active_page,9,2).substr($active_page,13,2).substr($active_page,20,2);
	$d_week = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	$d_meaning = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	for ($i=0; $i<count($d_meaning); $i++)
	{
		if (date(D)==$d_week[$i])
		{	$d_go = $d_meaning[$i];	}
	}
	include('m_inc/m_inc/log_header.php');
	
?>

<!-- Begin head-row1 -->
<div class="navbar navbar-fixed-top bs-docs-nav" role="banner">
    <div class="conjtainer">
      <!-- Begin smallar screen -->
      <div class="navbar-header">
        <span class="navbar-toggle" data-toggle="collapse" data-target=".bs-navbar-collapse">
            <span class="dropdown pull-right">                       
                <!-- Begin dropdown menu -->
                <?php if ($_SESSION['login']=="yes") {
					    require('m_inc/m_admin_menu.php');
					  } else {
						require('m_inc/m_login.php'); 
					  }
				?>
                <!-- End dropdown menu -->
            </span>        
        </span>
        <?php if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){ ?>
            <?php if ($conID == "100100100"){ ?>
            	<a href="<?php echo $mx00100 ?>" class="navbar-brand hidden-lg"><img src="img/logo-s.png" /><span class="subheadtxt hidden-lg">Money Management for Groups</span></a>
            <?php }else{ ?>
            	<a href="<?php echo $mx00100 ?>" class="navbar-brand hidden-lg"><span><?php echo $disNameShort; ?></span><br />
            	<span class="subheadtxt hidden-lg">Money Management for Groups</span></a>
        	<?php } ?>
		<?php }else{ ?>
        	<a href="<?php echo $mx00100 ?>" class="navbar-brand hidden-lg"><img src="img/logo-s.png" /><span class="subheadtxt hidden-lg">Money Management for Groups</span></a>
      	<?php } ?>
      </div>
	  <!-- End smallar screen -->
      <!-- Begin large screen -->
      <nav class="collapse navbar-collapse bs-navbar-collapse1" role="navigation">         
        <ul class="nav navbar-nav pull-left">
        	<li class="dropdown">
                <a href="#"><span class="label label-success"></span>Welcome <?php echo $firstName?>!</a>
        	</li>
        </ul>
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown pull-right">            
            <!-- Begin dropdown menu -->
            <?php if ($_SESSION['login']=="yes") {
					require('m_inc/m_admin_menu.php');
				  } else {
					require('m_inc/m_login.php'); 
				  }
			?>
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
        <div class="col-md-7">
          <div class="logo">
          		<?php if ($_SESSION['login']=="yes" and $_SESSION['log_ss'] != ""){ ?>
                	<?php if ($conID == "100100100"){ ?>
                    	<h2><a href="<?php echo $mx00100 ?>">MAX</a><span><a href="index.php">MONI</a></span></h2>
                	<?php }else{ ?>
                    	<h1><a href="<?php echo $mx00100 ?>"><?php echo $disName; ?></a></h1>
                    <?php } ?>
				<?php }else{ ?>
                    <h1><a href="<?php echo $mx00100 ?>">MAX</a><span><a href="index.php">MONI</a></span></h1>
                <?php } ?>    
                <p class="meta">Money Management for Groups</p>
          </div>
        </div>
		<!-- End logo section -->
        <?php if ($_SESSION['login']=="yes") { ?>
		<!-- Begin button section -->
        <div class="col-md-5">
        	<div class="logo">
        	<?php //if ($memberID=="100100146"){ require('m_inc/m_top_but.php');} ?>
            </div>
        </div>
		<!-- Begin button section -->
		<?php } ?>
      </div>
      <!-- End row2 -->
    </div>
</header>
<!-- Header ends -->