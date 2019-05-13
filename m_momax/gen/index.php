<?php
	include($p_guest); //get guest info
	include('m_momax/gen/con_log/log_index.php'); 
?>
<!-- Begin main content -->
<div class="content">
  	<!-- Sidebar -->
	<?php require_once('m_content/sidebar.php'); ?>
    <!-- Sidebar ends -->

  	<!-- Begin main -->
  	<div class="mainbar">
	    <!-- Begin breadcrumb1 -->
            <div class="page-head">
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> Dashboard </h2>
              <h2 class="pull-left">&nbsp;</h2>
              <div class="bread-crumb pull-right"><a href="<?php echo $mx001hp?>"><i class="fa fa-question"></i>Help</a></div>
              <div class="clearfix"></div>
            </div>
	    <!-- End breadcrumb1 -->

	    <!-- Begin matter -->
	    <div class="matter">
        	<!-- Begin container -->
            <div class="container">
              <!-- Begin row1 -->
              <div class="row">
                <?php if ($_GET['err']=="d1000"){
                	require_once('m_momax/gen/error.php'); }else { ?>
                    <!-- Begin budgetview1 -->
                    <div class="col-md-8">
                        <?php require_once('m_content/budgetview.php'); ?>
                    </div>
                    <!-- End budgetview1 -->
                    <!-- Begin message1 -->
                    <div class="col-md-3">
                        <?php require_once('m_momax/message/message.php'); ?>
                    </div>
                    <!-- End message1 -->
              	<?php } ?>
              </div>
              <!-- End row1 -->
            </div>
			<!-- End container -->
        </div>
		<!-- End matter -->
    </div>
	<!-- End main -->
    <div class="clearfix"></div>
</div>
<!-- End content -->