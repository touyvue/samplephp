<?php
	include($p_guest); //get guest info
	include('m_momax/member/con_log/log_index.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx007 ?>">Member</a></h2>
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
                
                	<!-- Begin budget setup widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Budget Forecasting</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content">
                            <div class="padd">
                              <div class="page-tables">
                              <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="data_forecast" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                      <th class="ysort">Name</th>
                                      <th class="ysort">Forecast</th>
                                      <th class="ysort">Actual</th>
                                      <th class="ysort">Start</th>
                                      <th class="ysort">End</th>
                                      <th class="ysort">Active</th>
                                      <th class="nsort"><a href="#" onclick="uptForecastInfo(0,0)"><button type="button" class="btn btn-xs btn-success">New</button></a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      <?php echo $forecastList ?>
                                  	</tbody>
                                  </table>
                              </div>
                              </div>
                          	  <div class="widget-foot">
                              	<a href="#" onclick="uptForecastInfo(0,0)"><button type="button" class="btn btn-xs btn-success">New Budget</button></a>
                              </div>
                        	</div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget setup widget -->
                    
                    <!-- Begin budget setup widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Budget Tracking</div>
                          <div class="widget-icons pull-right">
                            <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                            <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                          </div>  
                          <div class="clearfix"></div>
                        </div>
                        <!-- End widget-head -->
                        <!-- Begin widget-content -->
                        <div class="widget-content">
                            <div class="padd">
                              <div class="page-tables">
                              <div class="table-responsive">
                                  <table cellpadding="0" cellspacing="0" border="0" id="data_track" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                      <th class="ysort">Name</th>
                                      <th class="ysort">Amount</th>
                                      <th class="ysort">Date</th>
                                      <th class="nsort"><a href="#" onclick="uptTrackInfo(0,0)"><button type="button" class="btn btn-xs btn-success">New</button></a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      <?php echo $trackList ?>
                                  	</tbody>
                                  </table>
                              </div>
                              </div>
                          	  <div class="widget-foot">
                              	<a href="#" onclick="uptTrackInfo(0,0)"><button type="button" class="btn btn-xs btn-success">New Tracking</button></a>
                              </div>
                        	</div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End budget setup widget -->
                    
                    <!-- add forecasting budget modal -->
                    <div id="memberForecast" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title"><span id="forecastTitle"></span></h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Active:</label>
                                          <div class="col-lg-5">
                                            <div class="radio">
                                              <label><input type='radio' name='factive' id='activeyes' value='yes'>Yes</label>&nbsp;&nbsp;
                                              <label><input type='radio' name='factive' id='activeno' value='no'>No</label>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Budget Name:*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="budgetname" name="budgetname" placeholder="" />
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">From:</label>
                                          <div class="col-lg-5">
                                            <div id="datetimepicker" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="startdate" name="startdate" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">To:</label>
                                          <div class="col-lg-5">
                                            <div id="datetimepicker1" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="enddate" name="enddate" class="form-control" value="<?php echo date("m-t-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div>                                       
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Note</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="forecastnote" name="forecastnote" placeholder="comment" />
                                          </div>
                                        </div>
                                        <input type="hidden" id="fstate" name="fstate" value="" />
                                        <input type="hidden" id="membudgetid" name="membudgetid" value="" />
                                        <input type="hidden" id="consortiumid" name="consortiumid" value="<?php echo $consortiumID?>"  />
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                               		<a href="#" onclick="addForecastInfo()"><button type="button" id="saveForecast" class="btn btn-primary"><span id="forecastState"></span></button></a>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end forecasting budget modal -->
                                  
                    <!-- add tracking budget modal -->
                    <div id="memberTrack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title"><span id="trackTitle"></span></h4>
                              </div>
                              <div class="modal-body">
                                
                                <!-- form -->
                                  <div class="padd">
                                    <!-- Form starts.  -->
                                     <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Track Name:*</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="trackname" name="trackname" placeholder="" />
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Date:</label>
                                          <div class="col-lg-5">
                                            <div id="datetimepicker2" class="input-append input-group dtpicker">
                                                <input data-format="MM-dd-yyyy" type="text" id="trackdate" name="trackdate" class="form-control" value="<?php echo date("m-d-Y");?>">
                                                <span class="input-group-addon add-on"><i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i></span>
                                             </div>
                                          </div>
                                        </div> 
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Member:</label>
                                          <div class="col-lg-5">
                                            <select id="memberlistid" name="memberlistid" class="form-control" multiple="multiple" >
                                                <?php echo $memberList ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Budget:</label>
                                          <div class="col-lg-5">
                                            <select id="membudgetlistid" name="membudgetlistid" class="form-control" >
                                                <option value="0">Choose Budget</option>
                                                <?php echo $forecastBudgetList ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Account:</label>
                                          <div class="col-lg-5">
                                            <select id="acttracklistid" name="acttracklistid" class="form-control" onchange="turnonCreDeb(this.id,this.value)" >
                                                <option value="0">Choose Account</option>
                                                <?php echo $accountList ?>
                                            </select>
                                          </div>
                                        </div>
                                        <span id="on_credit_debit" style="display:none;">
                                            <div class="form-group">
                                              <label class="col-lg-3 control-label"></label>
                                              <div class="col-lg-5">
                                                <div class="radio">
                                                  <label><input type='radio' checked="checked" name='transtype' id='debit' value='1001'>Debit</label>&nbsp;&nbsp;
                                                  <label><input type='radio' name='transtype' id='credit' value='1000'>Credit</label>
                                                </div>
                                              </div>
                                            </div>  
                                        </span>                                  
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label">Note:</label>
                                          <div class="col-lg-5">
                                            <input type="text" class="form-control" id="tracknote" name="tracknote" placeholder="comment" />
                                          </div>
                                        </div>
                                        <input type="hidden" id="mid" name="mid" value="<?php echo $memberID ?>" />
                                        <input type="hidden" id="memtrackid" name="memtrackid" value="" />
                                        <input type="hidden" id="tstate" name="tstate" value="" />
                                        <input type="hidden" id="accountct" name="accountct" value="<?php echo $accountCount?>" />
                                      </form>
                                  </div>
                                <!-- form -->
                                
                              </div>
                              <div class="modal-footer">
                                <a href="#" onclick="addTrackInfo()"><button type="button" id="saveTrack" class="btn btn-primary"><span id="trackState"></span></button></a>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- end forecasting budget modal -->
                    
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