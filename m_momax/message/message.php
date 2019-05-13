<?php
	include('m_momax/message/con_log/log_message.php'); 
?>
<!-- Begin widget -->
<div class="widget">
    <!-- Begin widget-head -->
    <div class="widget-head">
      <div class="pull-left">Post</div>
      <div class="widget-icons pull-right">
        <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
        <a href="#" class="wclose"><i class="fa fa-times"></i></a>
      </div>  
      <div class="clearfix"></div>
    </div>
    <!-- End widget-head -->
    <!-- Begin widget-content -->
    <div class="widget-content">
      <!-- Below class "scroll-chat" will add nice scroll bar. It uses Slim Scroll jQuery plugin. Check custom.js for the code -->
      <div class="padd scroll-chat">
        
        <ul class="chats">
          <?php echo $newPost ?>    
        </ul>
      </div>
      <!-- Begin widget-footer -->
      <div class="widget-foot">
        <div class="input-group">
          <input type="text" class="form-control" id="postName" name="postName" placeholder="type here...">
          <input type="hidden" name="postMemID" id="postMemID" value="<?php echo $memberID ?>"  />
          <span class="input-group-btn">
            <button class="btn btn-default" id="addPost" type="button">Post</button>
          </span>
        </div>
      </div>
      <!-- Begin widget-footer -->
    </div>
    <!-- End widget-content -->
</div> 
<!-- End widget -->
<!-- Begin widget -->
<div class="widget">
    <!-- Begin widget-head -->
    <div class="widget-head">
      <div class="pull-left">Reports</div>
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
            <?php if ($budgetlistFound > 0){ ?>
            <tr>
              <td><a href="<?php echo $mx004as ?>">Budget Report</a></td>
              <td><span class="smtext">Active</span></td>
            </tr>
            <?php } ?>
            <tr>
              <td><a href="<?php echo $mx004aa ?>">Account Report</a></td>
              <td><span class="smtext">Active</span></td>
            </tr> 
            <tr>
              <td><a href="<?php echo $mx004ct ?>">Category Report</a></td>
              <td><span class="smtext">Active</span></td>
            </tr>
            <tr>
              <td><a href="<?php echo $mx004tk ?>">Tracking Report</a></td>
              <td><span class="smtext">Active</span></td>
            </tr>
            <?php if ($tagFound > 0){ ?>
            <tr>
              <td><a href="<?php echo $mx004tg ?>">Tag Report</a></td>
              <td><span class="smtext">Active</span></td>
            </tr>
            <?php } ?>
		</table>
      </div>
      <div class="widget-foot"></div>
    </div>
    <!-- End widget-content -->
</div>
<!-- Begin widget -->