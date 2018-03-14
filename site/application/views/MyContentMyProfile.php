<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/myProfile.js"></script>
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">My Profile</h3>
                  <h5></h5>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<div class="myAlertBox"></div>
                	<div class="panel panel-default">
					  <div class="panel-heading">Personal Data</div>
					  <div class="panel-body">
					    Display picture :<br />
                		<img width="150px" height="150px" src="<?php print base_url().$USERINFO['userImage'];?>" class="img-circle" alt="User Image" />
                		<br />
                		<button type="button" class="btn btn-primary" onclick="myChangeDisplayPicture();">Change</button>
                		<br />
                		<br />
					    Display name :
                		<input id="displayName" name="displayName" type="text" class="form-control" aria-describedby="basic-addon1" value="<?php print $USERINFO['displayName'];?>">
                		<br />
                		<button type="button" class="btn btn-primary" onclick="myChangeDisplayName();">Save</button>
					  </div>
					</div>
                	<div class="panel panel-default">
					  <div class="panel-heading">Change Password</div>
					  <div class="panel-body">
					    Recent Password :
                		<input id="oldPassword" name="oldPassword" type="password" class="form-control" aria-describedby="basic-addon1">
					    New Password :
                		<input id="newPassword1" name="newPassword1" type="password" class="form-control" aria-describedby="basic-addon1">
					    Retype New Password :
                		<input id="newPassword2" name="newPassword2" type="password" class="form-control" aria-describedby="basic-addon1">
                		<br />
                		<button type="button" class="btn btn-primary" onclick="myChangePassword();">Change Password</button>
					  </div>
					</div>
                	
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->