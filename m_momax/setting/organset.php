<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_organset.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx005gs ?>">Group Setting</a></h2>
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
                    <?php if ($_GET['oid'] != "new"){ ?>
                    <?php if ($licenseID == "1003" or $licenseID == "1002"){ ?>
                    <!-- Begin orglist widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $groupSetting ?></div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <?php echo $orgList; ?>   
                                  </table>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End orglist widget -->
                    <?php } ?>
                    <?php } ?>
                    <?php if ($_GET['oid'] != ""){ ?>
                    <!-- Begin insert/update org widget -->
                    <div class="widget" id="organSetup">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php if($_GET['oid']=="new"){echo "New Organization";}else{echo "Update Info";}?></div>
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
                                <form class="form-horizontal" role="form" action="#" onsubmit="return validateOrganFrm()" name="formOrgan" id="formOrgan" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Org Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="organization" name="organization" value="<?php echo $organization ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">street:</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="cstreet" name="cstreet" value="<?php echo $street ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">City:</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="ccity" name="ccity" value="<?php echo $city ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">State:</label>
                                      <div class="col-lg-2">
                                        <input type="text" class="form-control" id="cstate" name="cstate" value="<?php echo $state ?>">
                                      </div>
                                      <label class="col-lg-1 control-label">Zip:</label>
                                      <div class="col-lg-2">
                                        <input type="text" class="form-control" id="czip" name="czip" value="<?php echo $zip ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">E-mail:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="cemail" name="cemail" onchange="checkMemEmail(this.id, this.value)" value="<?php echo $email ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Website:</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="cwebsite" name="cwebsite" value="<?php echo $website ?>">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Logo:</label>
                                      <div class="col-lg-5">
                                      	<input id="c_uploadFile" placeholder="Choose File" disabled="disabled" />
                                        <div class="fileUpload btn btn-danger">
                                            <span>File</span>
                                            <input type="file" class="upload" name="cmx_self" id="cmx_self" onChange="fileyype(this.id,this.value,['gif','GIF','jpg','JPG','png','PNG','jpeg','JPEG']);" />
                                        </div><?php echo $organPix; ?>
                                      </div>
                                    </div>
                                    <hr />
                                    <?php if ($_GET['oid'] == "new"){ ?>
                                    <div class="form-group">
                                      <label class="col-lg-5 control-label">Org Admin 1</label>
                                      <div class="col-lg-2"><div class="radio"><label><input type="radio" name="meadmin" id="meadmin1" value="admin1" onclick="diableAdmin(this.id)">I'm Admin</label></div></div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">First Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $firstName ?>" id="cd1firstname" name="cd1firstname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Last Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $lastName ?>" id="cd1lastname" name="cd1lastname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">E-mail:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $email ?>" id="cd1email" name="cd1email" onchange="checkMemEmail(this.id, this.value)">
                                      </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                      <label class="col-lg-5 control-label">Org Admin 2</label>
                                      <div class="col-lg-2"><div class="radio"><label><input type="radio" name="meadmin" id="meadmin2" value="admin2" onclick="diableAdmin(this.id)">I'm Admin</label></div></div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">First Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $firstName ?>" id="cd2firstname" name="cd2firstname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Last Name:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $lastName ?>" id="cd2lastname" name="cd2lastname">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">E-mail:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $email ?>" id="cd2email" name="cd2email" onchange="checkMemEmail(this.id, this.value)">
                                      </div>
                                    </div>
                                    <hr />
                                    <?php } ?>
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="submit" id="addConsortium" class="btn btn-primary"><?php echo $organButton ?></button>
                                      </div>
                                    </div>
                                    <input type="hidden" id="mid" name="mid" value="<?php echo $memberID ?>" />
                                    <input type="hidden" id="conID" name="conID" value="<?php echo $consortID ?>" />
                                    <input type="hidden" id="organID" name="organID" value="<?php echo $organID ?>" />
                                    <input type="hidden" id="chgstate" name="chgstate" value="<?php echo $chgState ?>" />
                                </form>
                                  
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End insert/update org widget -->
                    <?php } ?>
                    <!-- End main group setting widget -->
                    <?php if ($orgAdmin=="yes" or $consAdmin=="yes"){ ?>
                    <!-- Begin member setting widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Member Status</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                            <!-- Begin widget-content -->
                        <div class="widget-content referrer">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
									<tr><th>Admin</th><th>Member</th><th colspan="3">Active</th></tr>
									<?php echo $allActMem; ?>
                                  </table>
                                  
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End member setting widget -->
	                <?php } ?>
                    
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