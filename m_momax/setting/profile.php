<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_profile.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx005 ?>">Setting</a></h2>
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
                <div class="col-md-8">
                    <!-- Begin my accounts widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $profileSetting ?></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                            <div class="widget-content referrer">
                                <br />
                                <form class="form-horizontal" role="form" action="<?php echo $mx005pu?>" onsubmit="return validateProfileFrm()" name="formProfile" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">First Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $firstName ?>" id="firstname" name="firstname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Last Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $lastName ?>" id="lastname" name="lastname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">E-mail:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $email ?>" id="memail" name="memail" onchange="checkMemEmail(this.id, this.value)">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Password:*</label>
                                      <div class="col-lg-5">
                                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $password ?>" onchange="check_password(this.id,this.value)">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Picture:</label>
                                      <div class="col-lg-5">
                                      	<input id="uploadFile" placeholder="Choose File" disabled="disabled" />
                                        <div class="fileUpload btn btn-danger">
                                            <span>File</span>
                                            <input type="file" class="upload" name="mx_self" id="mx_self" onChange="fileyype(this.id,this.value,['gif','GIF','jpg','JPG','png','PNG','jpeg','JPEG']);" />
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="submit" id="updateProfile" class="btn btn-primary">Update</button>
                                      </div>
                                    </div>
                                    <input type="hidden" id="budState" value="add" />
                                    <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                </form>
                                  
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my accounts widget -->
                    
                </div>
                <div class="col-md-3">
                    <?php require_once('m_momax/message/message.php'); ?>
                </div>
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