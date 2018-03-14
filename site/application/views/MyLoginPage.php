<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>SDN ColdBrew</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php print base_url().'assets/'; ?>bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php print base_url().'assets/'; ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- jQuery 2.1.4 -->
    <script src="<?php print base_url().'assets/'; ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php print base_url().'assets/'; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php print base_url().'assets/'; ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
    	function myJson2HexStr(pDtJSON){
			s1=JSON.stringify(pDtJSON);
			s2='';
			for(i=0;i<s1.length;i++){
				//alert(s1[i]);
				s3=s1.charCodeAt(i).toString(16).toUpperCase();
				//alert(s3);
				s2=s2+s3;
				//if(i==2){break;}
			}
			return s2;
		};
		function myHexStr2Json(pHexStr){
			var pDtJSON;
			var s1,s2,s3;
			var i,j,k,l;
			s1='';
			for(i=0;i<pHexStr.length-1;i=i+2){
				s2=pHexStr[i]+pHexStr[i+1];
				j=parseInt(s2,16);
				s1=s1+String.fromCharCode(j);
			}
			pDtJSON=JSON.parse(s1);
			return pDtJSON;
		};
    	function myLogin(){
    		var pUsernameObj=document.getElementById('txtUsername');
    		var pPasswordObj=document.getElementById('txtPassword');
    		var pUsername=pUsernameObj.value;
    		var pPassword=pPasswordObj.value;
    		//if((pUsername=='')or(pPassword=='')){
    		//	return;
    		//}
    		var pDtJSON={};
			pDtJSON['userCommand']='login';
			pDtJSON['customParam']={};
			pDtJSON['customParam']['username']=pUsername;
			pDtJSON['customParam']['password']=pPassword;
    		var pDataPost={};
			pDataPost['TDBF']=myJson2HexStr(pDtJSON);
    		var pRequestUrl="<?php print $pActionPage;?>";
			//alert(pRequestUrl);
    		$.ajax({
				url: pRequestUrl,
				method: 'POST',
				data: pDataPost,
				async: false,
				cache: false,
				success: function (pHTML) {
					//alert(pHTML);
					var pDtJSON2=myHexStr2Json(pHTML);
					if(pDtJSON2['loginResult']){
						window.location.href="<?php print $pLandingPage; ?>";
					}else{
						document.getElementById('divLoginResult').innerHTML=pDtJSON2['loginDesc'];
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
				}
			});
    	}
    </script>
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>SDN ColdBrew</b>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Please sign in...</p>
          <div class="form-group has-feedback">
            <input id="txtUsername" type="username" class="form-control" placeholder="Username" value=""/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input id="txtPassword" type="password" class="form-control" placeholder="Password" value=""/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div id="divLoginResult"></div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat" onclick="myLogin();">Sign In</button>
            </div><!-- /.col -->
          </div>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
  </body>
</html>
