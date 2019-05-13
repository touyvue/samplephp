<?php
	include($p_guest); //get guest info
	include('m_momax/budget/con_log/log_budget.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a></h2>
              <h2 class="pull-left">&nbsp;<a href="<?php echo $mx002mb."&bid=".$budgetID."&smid=".$_GET['smid']; ?>"><?php echo str_replace('\\','',$budgetNameMemberID[0]);?></a></h2>
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
                    
                    <!-- Begin specific budget widget -->
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
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                      <th>Date</th>
                                      <th>Income</th>
                                      <th>Planned Amt</th>
                                      <th>Actual Amt</th>
                                      <th>Variance</th>
                                      <th></th>
                                    </tr>
                                    <?php echo $spbudgetIncList; ?>
                                     <tr>
                                      <th></th>
                                      <th>Expenses</th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    <?php echo $spbudgetExpList; ?> 
                                  </table>
                              </div>
                          <div class="widget-foot">
                          	<ul class="pagination pagination-sm pull-right">
                              <li><a href="<?php echo $mx002pb."&bid=".$budgetID."&mo=".$preBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-backward"></i></a></li>
                              <li><a href="<?php echo $mx002pb."&bid=".$budgetID."&mo=".$selectedMonth.$selectedYear."&smid=".$sharedMemberID;?>"><?php echo $actSelectedMonth; ?></a></li>
                              <li><a href="<?php echo $mx002pb."&bid=".$budgetID."&mo=".$nextBudgetMonth."&smid=".$sharedMemberID;?>"><i class="fa fa-forward"></i></a></li>
                            </ul>
                        	<div class="clearfix"></div>
                          </div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End specific budget widget -->
                    
                    <!-- add specific budget modal -->
                    <div id="mySpcModalAdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title">Budget</h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Active:</label>
                                          <div class="col-lg-7">
                                            <div class='radio'>
                                                <label><input type='radio' checked="checked" name='budactive' id='budactivey' value='yes'>Yes</label>&nbsp;&nbsp;
                                                <label><input type='radio' name='budactive' id='budactiven' value='no'>No</label>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Budget Name:*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="budName" name="budName" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Amount:*</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" id="amount" name="amount" placeholder="$0.00" onchange="isNumber_amtina(this.id,this.value)" />
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">From:</label>
                                          <div class="col-lg-5">
                                            <div id="datetimepicker" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="budset_strdate" name="budset_strdate" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">To:</label>
                                          <div class="col-lg-5">
                                            <div id="datetimepicker1" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="budset_enddate" name="budset_enddate" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Details:</label>
                                          <div class="col-lg-7">
                                         	<textarea class="form-control" id="description" name="description" rows="5" placeholder="Details"></textarea>
                                          </div>
                                        </div>
                                                                               
                                        <input type="hidden" id="mid" value="<?php echo $memberID ?>" />
                                        <input type="hidden" id="state" value="new" />
                                        <input type="hidden" id="newstate" value="new" />
                                        <input type="hidden" id="edtBudID" name="edtBudID" value="" />
                                        <input type="hidden" id="budgetsheetID" name="budgetsheetID" value="" />
                                        <input type="hidden" id="budgetsheetTypeId" name="budgetsheetTypeId" value="" />
                                        <input type="hidden" id="annmon" name="annmon" value="" />
	                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" id="insertSpcNewBudget" class="btn btn-primary"><span id="eSpcBudType"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end specific budget modal -->
                    
                    
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