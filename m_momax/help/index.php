<?php
	include($p_guest); //get guest info
	include('m_momax/help/con_log/log_index.php'); 
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
              <h2 class="pull-left"><a href="index.php"><i class="fa fa-home"></i></a> <a href="<?php echo $mx001hp ?>">Support & How To</a></h2>
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
                    
                    <!-- Begin setting - how to widget -->
                    <div class="widget">
                        <!-- Begin widget-head -->
                        <div class="widget-head">
                          <div class="pull-left">Setting Help</div>
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
                                    <tr><td><a href="#" onclick="helpsettingf(1)"><li>Change Profile</li></a></td><td></td></tr>
                                    <tr><td><a href="#" onclick="helpsettingf(2)"><li>Add/update Category</li></a></td><td></td></tr>
                                    <tr><td><a href="#" onclick="helpsettingf(3)"><li>Set Post Viewing Rights</li></a></td><td></td></tr>
                                    <tr><td ><a href="#" onclick="helpsettingf(4)"><li>Create New Group</li></a></td><td></td></tr>
                                    <tr><td ><a href="#" onclick="helpsettingf(5)"><li>Update Group Info</li></a></td><td></td></tr>
                                    <tr><td><a href="#" onclick="helpsettingf(6)"><li>Add New Group Member</li></a></td><td></td></tr>
                                    <tr><td ><a href="#" onclick="helpsettingf(7)"><li>Remove Group</li></a></td><td></td></tr>
                                    <tr><td ><a href="#" onclick="helpsettingf(8)"><li>Remove Member from Group</li></a></td><td></td></tr>
                                  </table>
                              </div>
                          <div class="widget-foot"><i class="fa fa-envelope-o"></i> <a href="mailto:info@maxmoni.com">Anything else?</a></div>
                        </div>
                        <!-- End widget-content -->
                    </div>
                    <!-- End setting - how to widget -->
                    <!-- add setting - how to modal -->
                    <div id="myModalHSetting" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-close'></i></button>
                                <h4 class="modal-title" id="settingtitle">General</h4>
                              </div>
                              <div class="modal-body">
                                <div class="table-responsive">
                                  <span id="s_profile" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to change profile info:</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Profile</span>.</li>
                                                <li>When the Profile form shows up, update the fields as needed
                                                    <ul>
                                                        <li>First Name</li>
                                                        <li>Last Name</li>
                                                        <li>E-mail</li>
                                                        <li>Password</li>
                                                        <li>Self image</li>
                                                    </ul>
                                                </li>
                                                <li>Click <span class="helpred">Update</span> to save the new information</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_category" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to add/update/delete category:</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Admin Setting, click on <span class="helpred">Category Setup</span> to see all
                                                	available categories.</li>
                                                <li>You can add/update/delete category.
                                                    <ul>
                                                        <li>Add new category - click on the <span class="helpred">New Category</span> button to enter info.</li>
                                                        <li>Update category - click on the <span class="helpred">category name</span> to make the necessary update.</li>
                                                        <li>Delete category - click on the red <span class="helpred">"x" button</span> to the right of the category name.</li>
                                                    </ul>
                                                </li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_post" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to set post viewing rights:</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Admin Setting, click on <span class="helpred">Post Setup</span> to see the
                                                	available options.</li>
                                                <li>Choose a rights option.
                                                    <ul>
                                                        <li>For self only - only you see your post.</li>
                                                        <li>Specific groups only - choose the desire group(s).</li>
                                                        <li>All groups - all group members can see post.</li>
                                                    </ul>
                                                </li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_newgroup" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to create new group (Admin only):</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Profile Setting, click on <span class="helpred">Group Setting</span>.</li>
                                                <li>If you have Admin rights, a green <span class="helpred">New Group</span> button will appear next to your organization's name.</li>
                                            	<li>Click on the <span class="helpred">New Group</span> button to bring up the new group form.</li>
                                            	<li>Enter the group name in the textbox and click the <span class="helpred">Create Group</span> button to save the new group</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_updategroup" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to update group info (Admin only):</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Profile Setting, click on <span class="helpred">Group Setting</span>.</li>
                                            	<li>Click on the <span class="helpred">group name</span> to go to the group info page.</li>
                                            	<li>Update any group info as well as members within the group and click <span class="helpred">Update Group</span> button to save the group info</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_groupmember" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to add new group member (Admin only):</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Profile Setting, click on <span class="helpred">Group Setting</span>.</li>
                                            	<li>Click on the <span class="helpred">group name</span> to go to the group info page.</li>
                                                <li>Click on the <span class="helpred">New Member</span> button to bring up the new member form.
                                                	<ul>
                                                        <li>Enter First Name</li>
                                                        <li>Enter Last Name</li>
                                                        <li>Enter E-mail</li>
                                                        <li>Check for Group Admin</li>
                                                    </ul>
                                                </li>
                                            	<li>Click <span class="helpred">Add Member</span> button to save the new member</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_removegroup" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to remove a group (Admin only):</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Profile Setting, click on <span class="helpred">Group Setting</span>.</li>
                                            	<li>Click on the red <span class="helpred">"x" button</span> to remove the group.</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                   <span id="s_removemember" style="display: none;">
                                      <table class="table table-striped table-bordered table-hover">
                                        <tr><td>Steps to remove a member (Admin only):</td></tr>
                                        <tr><td>
                                            <ol>
                                                <li>Click on <span class="helpred">Admin</span> on the top-right corner.</li>
                                                <li>Click on <span class="helpred">Setting</span>.</li>
                                                <li>Under Profile Setting, click on <span class="helpred">Group Setting</span>.</li>
                                            	<li>Click on the <span class="helpred">group name</span> to go to the group info page.</li>
                                                <li>Click on the red <span class="helpred">"x" button</span> to remove the group.</li>
                                            </ol>
                                        </td></tr>
                                       </table>
                                   </span>
                                </div>
                              </div>
                              <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end setting - how to modal -->
                    
                    
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