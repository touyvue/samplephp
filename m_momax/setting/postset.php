<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_postset.php'); 
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
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label"></label>
                                      <div class="col-lg-5"><label>Choose a setting to share your post</label>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Post rights:</label>
                                      <div class="col-lg-5">
                                        <div class="radio"><label><input type="radio" name="sharePost" id="selfGrp" value="self" <?php if($curRights==1){echo "checked";}?>>For self only</label></div>
                                        <div class="radio"><label><input type="radio" name="sharePost" id="specificGrp" value="specific" <?php if($curRights==2){echo "checked";}?>>Specific groups only</label></div>
                                        <div class="radio"><label><input type="radio" name="sharePost" id="allGrp" value="all" <?php if($curRights==3){echo "checked";}?>>All groups</label></div>
                                      </div>
                                    </div>
                                    <span id="on_groupList" style="display: <?php if ($curRights != 2){echo "none";}?>;">
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Groups:</label>
                                      <div class="col-lg-5">
                                        <?php echo $grpList; ?>
                                      </div>
                                    </div>
									</span>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="button" id="updatePost" class="btn btn-primary">Update</button>
                                      </div>
                                    </div>
                                    <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                    <input type="hidden" id="grpCt" value="<?php echo $grpCt ?>" />
                                    <input type="hidden" id="grpIDs" value="<?php echo $grpListArr ?>" />
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