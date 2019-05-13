<?php
	include($p_guest); //get guest info
	include('m_momax/setting/con_log/log_groupset.php'); 
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
                    <?php if ($_GET['gid'] != "new"){ ?>
                    <!-- Begin my accounts widget -->
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
                                    <?php echo $groupList; ?>   
                                  </table>
                              </div>
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my accounts widget -->
                    <?php } ?>
                    <?php if ($_GET['gid'] != ""){ ?>
                    <!-- Begin my consortium widget -->
                    <div class="widget" id="organSetup">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php if($_GET['gid']=="new"){echo "New Group";}else{echo "Update Info";} ?></div>
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
                                <form class="form-horizontal" role="form" action="#" onsubmit="return validateGroupFrm()" name="formOrgan" id="formOrgan" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Group:*</label>
                                      <div class="col-lg-5">
                                        <input type="text" class="form-control" id="groupname" name="groupname" value="<?php echo $groupName ?>">
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
                                        </div><?php echo $groupPix; ?>
                                      </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label">Members:</label>
                                      <div class="col-lg-7">
                                        <?php if ($_GET['gid'] == "new"){echo "No member";}else{ ?>
                                        <table class="table table-striped table-hover">
                                        	<tr><th colspan="3">Grant member's rights</th><th colspan="2"><?php if ($trackgroup!=""){echo '<input type="checkbox" id="'.$trackgrpID.'" name="'.$trackgrpID.'" checked="checked" > Tracking On';}else{echo '<input type="checkbox" id="'.$trackgrpID.'" name="'.$trackgrpID.'" > Tracking Off';} ?></th></tr>
                                            <tr><th>Admin</th><th>Member</th><th colspan="2">Active</th><th>Remove</th></tr>
                                        	<?php echo $memberList; ?>
                                        </table>
                                        <?php } ?>
                                      </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                      <label class="col-lg-2 control-label"></label>
                                      <div class="col-lg-5">
                                        <button type="submit" id="addGroup" class="btn btn-primary"><?php echo $groupButton ?></button>
                                      </div>
                                    </div>
                                    <input type="hidden" id="mid" name="mid" value="<?php echo $memberID ?>" />
                                    <input type="hidden" id="conID" name="conID" value="<?php echo $consortID ?>" />
                                    <input type="hidden" id="organID" name="organID" value="<?php echo $organID ?>" />
                                    <input type="hidden" id="groupID" name="groupID" value="<?php echo $groupID ?>" />
                                    <input type="hidden" id="chgstate" name="chgstate" value="<?php echo $chgState ?>" />
                                </form>
                                  
                          <div class="widget-foot"></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my consortium widget -->
                    <?php } ?>
                    <!-- add new member modal -->
                    <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">New Member</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                     	<?php if ($curExistMemberList != ""){ ?>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Existing Members</label>
                                          <div class="col-lg-7">
                                            <select class="form-control" id="existMember" name="existMember">
                                              <option value="none">Add existing member</option>
                                              <?php echo $curExistMemberList ?>
                                            </select>
                                          </div>
                                        </div>
                                        <hr />
                                        <?php } ?>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">First Name:*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="newfirst" name="newfirst">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Last Name:*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="newlast" name="newlast">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">E-mail:*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="cd1email" name="cd1email" onchange="checkMemEmail(this.id, this.value)">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Admin:*</label>
                                          <div class="col-lg-7">
                                            <div class="radio"><label><input type="radio" name="gadmin" id="gadminNo" checked="checked" value="no">NO</label>
                                            				   <label><input type="radio" name="gadmin" id="gadminYes" value="yes">YES</label></div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Include Address:</label>
                                          <div class="col-lg-7">
                                            <div class="radio"><label><input type="radio" name="addresschg" id="addressNo" checked="checked" value="no">NO</label>
                                            				   <label><input type="radio" name="addresschg" id="addressYes" value="yes">YES</label></div>
                                          </div>
                                        </div>
                                        <span id="on_address" style="display:none;">
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label">Street:</label>
                                              <div class="col-lg-7">
                                                <input type="text" class="form-control" id="rstreet" name="rstreet">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label">City:</label>
                                              <div class="col-lg-7">
                                                <input type="text" class="form-control" id="rcity" name="rcity">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label">State:</label>
                                              <div class="col-lg-3">
                                                <input type="text" class="form-control" id="rstate" name="rstate">
                                              </div>
                                              <div class="col-lg-1">Zip:</div>
                                              <div class="col-lg-3">
                                                <input type="text" class="form-control" id="rzip" name="rzip">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label">Phone:</label>
                                              <div class="col-lg-7">
                                                <input type="text" class="form-control" id="rphone" name="rphone">
                                              </div>
                                            </div>
                                        </span>
                                        <input type="hidden" id="newState" value="add" />
                                        <input type="hidden" id="newConID" name="newConID" value="<?php echo $consortID ?>" />
                                        <input type="hidden" id="newGrpID" name="newGrpID" value="<?php echo $groupID ?>" />
                                        <input type="hidden" id="exMember" name="exMember" value="no" />
                                        <input type="hidden" id="exMid" name="exMid" value="" />
	                                  </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="insertNewMember" class="btn btn-primary">Add Member</button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end add new member modal -->
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