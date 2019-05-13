<!-- Begin main content -->
<div class="admin-form">
  <div class="container">
  	<div class="row">
      <div class="col-md-12">
        <!-- Begin widget -->
            <div class="widget worange">
              <div class="widget-head">
                <i class="fa fa-lock"></i> Login </div>
              <div class="widget-content">
                <div class="padd">
                  <form class="form-horizontal" id="loginform" name="loginform" action="<?php echo $index_url?>?p=login&amp;pa=<?php echo $_GET['pa']?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputEmail">Email</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputPassword">Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <button type="submit" class="btn btn-info btn-sm" id="btnSignin">Sign in</button>
                        <button type="reset" class="btn btn-default btn-sm" id="btnReset">Reset</button>
                    </div>
                    <br />
                  </form>
				</div>
              </div>              
              <div class="widget-foot">
              	<a href="<?php echo $mx001fp?>">Forgot password</a>
              	 <?php  
					if ($chk_login == 3){
						echo "Enter E-mail and Password.";
					}
					if ($chk_login == 2){
						echo "E-mail and Password are incorrect.";
					}
					if ($chk_login == 1){
						echo "Incorrect e-mail.  Contact administrator.";
					}
				?>  <a href="<?php echo $mx112dm?>" class="pull-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>              
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
	window.onload=my_load_login;
</script>