<?php
	include($p_guest); //get guest info
	include('m_momax/project/con_log/log_project.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx006 ?>">Events</a></h2>
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
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left"><?php echo $myBudgetOverview ?></div>
                          
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
                                  <label class="col-lg-2 control-label">Date:*</label>
                                  <div class="col-lg-4">
                                     <div id="datetimepicker1" class="input-append input-group dtpicker">
                                        <input data-format="MM-dd-yyyy" type="text" id="trans_date" class="form-control" value="<?php echo $projectNameID[2];?>">
                                        <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                     </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Name:*</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?php echo $projectNameID[0]?>" id="projectName">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Amount:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" disabled="disabled" value="<?php echo $projectTotAmt ?>" id="budgetAmount">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Description:</label>
                                  <div class="col-lg-5">
                                    <input type="text" class="form-control" id="description" value="<?php echo $projectNameID[3];?>">
                                  </div>
                                </div>
                                
                                <?php if ($budgetlistMain != ""){ ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Budget:</label>
                                  <div class="col-lg-5">
                                    <select id="budgetlist" name="budgetlist" class="form-control" >
                                        <option value="0">Choose Budget</option>
                                        <?php echo $budgetlistMain ?>
                                    </select>
                                  </div>
                                </div>
                                <?php } ?>
								<?php if (($_GET['smid']!="" and $projectRights > 1)or $_GET['smid']=="" ){ ?>
                               
                            	<div class="form-group">
                                  <label class="col-lg-2 control-label">Tracking:</label>
                                  <div class="col-lg-7">
                                    <div class="radio">
                                      <label><input type="radio" name="addacct" id="addacct1" value="yes" <?php if($projAccountFound>0){echo 'checked="yes"';}?> onclick="insertbudget('yes',<?php echo $acctNumAllCt ?>,'<?php echo $acctNumAll ?>')">YES</label>&nbsp;&nbsp;
                                      <label><input type="radio" name="addacct" id="addacct2" value="no" <?php if($projAccountFound<=0){echo 'checked="yes"';}?> onclick="insertbudget('no',<?php echo $acctNumAllCt ?>,'<?php echo $acctNumAll ?>')">NO</label>
                                    </div>
                                    <span id="on_acct_insert" style="display: <?php if($projAccountFound>0){echo "";}else{echo "none";}?>"><?php echo $acctItemsList; ?></span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"></label>
                                  <div class="col-lg-5">
                                    <button type="button" id="addProject" class="btn btn-primary">Update</button>
                                  </div>
                                </div>
                                <?php } ?>
                                <input type="hidden" id="budState" value="add" />
                                <input type="hidden" id="allActiveAcct" value="<?php echo $acctNumAll ?>" />
                                <input type="hidden" id="allActivdAcctCt" value="<?php echo $acctNumAllCt ?>" /> 
                                <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                <input type="hidden" id="projectid" value="<?php echo $projectID ?>" />
                            </form>
                              
                          <div class="widget-foot">
                        	<div class="clearfix"></div>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left" id="pdetail">Event Details</div>
                          
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
                                <thead>
                                    <tr>
                                      <th>Items</th>
                                      <th>Amount</th>
                                      <th>Completed</th>
                                      <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $itemList ?>  
                                    <?php echo $itemListTot ?>
                                </tbody>
                              </table>
                              
                              <!-- add budget modal -->
                                <div id="myModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                            <h4 class="modal-title"><span id="aTitle"></span></h4>
                                          </div>
                                          <div class="modal-body">
                                            
                                            <!-- form -->
                                              <div class="padd">
                            
                                                <!-- Form starts.  -->
                                                 <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Name*</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="projItem" rows="1" placeholder="" />
                                                        <label class="checkbox-inline"><input type="checkbox" name="pdone" id="pdone" value="yes" />&nbsp;&nbsp;Completed</label>
                                                      </div>
                                                    </div> 
                                                                                                      
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Amount*</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="projAmount" placeholder="$0.00" onchange="isNumber_amtina(this.id,this.value)">
                                                      </div>
                                                    </div>
                                                    
                                                    <span id="show_budlist" style="display:none;">
                                                    <?php if ($budgetAnnList != ""){ ?>
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Budget:</label>
                                                      <div class="col-lg-5">
                                                        <select id="budgerannset" name="budgerannset" class="form-control" >
                                                        	<option value="0">Choose Budget</option>
                                                            <?php echo $budgetAnnList ?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <?php } ?>
                                                    </span>
                                                                                                                                                       
                                                    <div class="form-group">
                                                      <label class="col-lg-3 control-label">Note</label>
                                                      <div class="col-lg-5">
                                                        <input type="text" class="form-control" id="note" rows="1" placeholder="" />
                                                      </div>
                                                    </div>    
													<input type="hidden" name="itemState" id="itemState" value="none" />
                                                    <input type="hidden" name="projectID" id="projectID" value="<?php echo $projectID ?>"  />
                                                    <input type="hidden" name="itemID" id="itemID" value=""  />
                                                    <input type="hidden" name="budlistid" id="budlistid" value="<?php echo $budgetlistID ?>">
                                                  </form>
                                              </div>
                                            <!-- form -->
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="addProjItem" class="btn btn-primary"><span id="aValType"></span></button>
                                          </div>
                                    	</div>
                                    </div>
                                </div>
                                <!-- end add budget modal -->
                              
                          </div>
                          
                          <div class="widget-foot">
                        	<div class="clearfix"></div>
                          </div>
                          
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    
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