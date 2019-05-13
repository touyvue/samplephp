<!-- Begin main content -->
<div class="admin-form">
  <div class="container">
  	<div class="row">
      <div class="col-md-12">
        <!-- Begin widget -->
            <div class="widget worange">
              <div class="widget-head">
                <i class="fa fa-lock"></i> Forgot Password </div>
              <div class="widget-content">
                <div class="padd">
                  <form class="form-horizontal" id="loginform_re" name="loginform_re" onsubmit="return validate_login_re()" onkeypress="return disableEnterKey(event)" action="<?php echo $index_url?>?p=fgpass&amp;pa=<?php echo $_GET['pa']?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputEmail">Email</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputPassword"></label>
                      <div class="col-lg-9">Enter your email to reset your password.</div>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <button type="submit" class="btn btn-info btn-sm" id="btnLostPass">Submit</button>
                        <button type="reset" class="btn btn-default btn-sm" id="btnReset">Reset</button>
                    </div>
                    <br />
                  </form>
				</div>
              </div>              
              <div class="widget-foot">
              	 <label><?php echo $chk_login; ?></label>                
              </div>
            </div>  
        <!-- Begin widget -->
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<!-- End content -->
<script type="text/javascript">
	window.onload=my_load_login_re;
</script>