<?php
	include($p_guest); //get guest info
	include('m_momax/message/con_log/log_index.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx007 ?>">Message</a></h2>
              <div class="clearfix"></div>
              <div class="bread-crumb pull-right"><a href="<?php echo $mx001hp?>"><i class="fa fa-question"></i>Help</a></div>
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
                          <div class="pull-left">Send Message</div>
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
                            <form class="form-horizontal" name="requestfrm">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">To:*</label>
                                	<div class="col-lg-10">
                                		<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $selectName?>" />
                                	</div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><span id="spacePre"></span>Subject:*</label>
                                	<div class="col-lg-10">
                                		<span id="fromPre"></span>
                                        <input type="text" class="form-control" id="subject" name="subject">
                                	</div>
                                </div>
                               <input type="hidden" class="form-control" id="mstatus" name="mstatus" />
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Message:*</label>
                                	<div class="col-lg-10">
                                        <div class="text-area">
                                            <textarea class="form-control" rows="5" name="mainmsg" id="mainmsg"></textarea>
                                        </div>
                                	</div>
                                </div>
                                <span id="on_status" style="display: none;">
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"></label>
                                  <div class="col-lg-5">
                                  	<label class="checkbox-inline"><input type="checkbox" name="reqDone" id="reqDone" value="yes">Completed</label>
                                  </div>
                                </div>
                                </span>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"></label>
                                	<div class="col-lg-10">
                                        <button type="button" id="addRequest" class="btn btn-primary">Send</button>
                                        <span id="del_msg" style="display: none;">
                                        	&nbsp;&nbsp;&nbsp;<button type="button" id="delRequest" class="btn btn-primary">Delete</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <input type="hidden" id="memberID" name="memberID" value="<?php echo $memberID ?>"  />
                                <input type="hidden" id="selectID" name="selectID" value="<?php echo $selectID ?>" />
                                <input type="hidden" id="orgReqID" name="orgReqID" value="" />
                                <input type="hidden" id="reqType" name="reqType" value="" />
                                <input type="hidden" id="resend" name="resend"	value="no" />
                            </form>
                            
                          </div>
                          <div class="widget-foot">
                            <span id="ctWord"></span></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End my budget widget -->
                    
                    
                    <!-- Begin my budget widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Message Received</div>
                          
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
                                      <th>From</th>
                                      <th>Subject</th>
                                      <th>Date</th>
                                      <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $messageRec ?>  
                                </tbody>
                                <tfoot>
                                </tfoot>                                                      
                              </table>
                          </div>
                          
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
                          <div class="pull-left">Message Sent</div>
                          
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
                                      <th>To</th>
                                      <th>Subject</th>
                                      <th>Date</th>
                                      <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $messageSen ?>  
                                </tbody>
                                <tfoot>
                                </tfoot>                                                      
                              </table>
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
<script type="text/javascript">
	window.onload=my_load_request;
</script>