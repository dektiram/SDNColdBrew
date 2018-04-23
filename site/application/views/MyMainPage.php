<?php print $pHTMLMyHtmlMeta; ?>
<?php
if(!isset($pPageLoadMode)){$pPageLoadMode='full';}
?>
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
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link href="<?php print base_url().'assets/'; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php print base_url().'assets/'; ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php print base_url().'assets/'; ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php print base_url().'assets/'; ?>plugins/colorpicker/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery.ui.combify.css" rel="stylesheet" type="text/css" />
    <link href="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" />
    <link href="<?php print base_url().'assets/'; ?>plugins/jQueryUI/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
  	<link href="<?php print base_url().'assets/'; ?>mycss/mycss.css" rel="stylesheet" type="text/css" />
  	
  	<!-- jQuery 2.1.4 -->
    
    <script src="<?php print base_url().'assets/'; ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/jQueryUI/i18n/jquery-ui-timepicker-addon-i18n.min.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery-ui-sliderAccess.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/jQueryUI/jquery.ui.combify.js" type="text/javascript"></script>
    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php print base_url().'assets/'; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/colorpicker/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
    <script src="<?php print base_url().'assets/'; ?>plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php print base_url().'assets/'; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php print base_url().'assets/'; ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="<?php print base_url().'assets/'; ?>plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php print base_url().'assets/'; ?>dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php print base_url().'assets/'; ?>dist/js/demo.js" type="text/javascript"></script>
    
  </head>
  <?php
  if($this->config->item('left_menu_sidebar_collapse')){
  	?>
  	<body class="skin-blue sidebar-mini sidebar-collapse">
  	<?php
  }else{
  	?>
  	<body class="skin-blue sidebar-mini">
  	<?php
  }
  ?>
  
    
    <!--<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/myFunction.js"></script>-->
  	
  	<?php
  	require_once 'MyApp.js.php';
  	?>
    <script src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js" type="text/javascript"></script>
    
    <?php
	if($pPageLoadMode=='full'){
	?>
    <div class="wrapper">
	<?php
	}
	?>
	  <?php
	  if($pPageLoadMode=='full'){
	  ?>
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>SDN</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SDN&nbsp;ColdBrew</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <?php print $pHTMLMyNavBar; ?>
        </nav>
      </header>
      <?php
	  }
      ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php 
      if($pPageLoadMode=='full'){
      	print $pHTMLMyLeftBar; 
	  }
      ?>
      <?php
	  if($pPageLoadMode=='full'){
	  ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
      <?php
	  }
      ?>
        <!-- Content Header (Page header) -->
        <?php
        if($pPageLoadMode=='full'){
        	print $pHTMLMyContentHeader; 
		}
        ?>
        <!-- Main content -->
        <?php print $pHTMLMyContent; ?>  
      <?php
	  if($pPageLoadMode=='full'){
	  ?>    
      </div><!-- /.content-wrapper -->
      <?php
	  }
	  //$GLOBALS['dataLisence']['lisenceStatus'] ==> models/Tdbflisence.php
	  if($GLOBALS['dataLisence']['lisenceStatus']){
		$copyRight = $GLOBALS['dataLisence']['lisenceTo'];
		$yearsCopy = substr($GLOBALS['dataLisence']['lisenceDate'],0,4);
		$appVersion = $GLOBALS['dataLisence']['appVersion'];
	  }
      ?>
      <?php
      if($pPageLoadMode=='full'){
      ?>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> <?php print $appVersion;?>
        </div>
        <strong>Copyright &copy; <?php print $yearsCopy;?> <a href="http://"><?php print $copyRight;?></a>.</strong> All rights reserved.
        <?php
        if(!$GLOBALS['dataLisence']['lisenceStatus']){
        	print '<code>TRIAL MODE</code>';
        }
        ?>
      </footer>
      <!-- Control Sidebar -->
      <?php print $pHTMLMyRightBar; ?>      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->      
      <div class="control-sidebar-bg"></div>
	  <?php
	  }
	  ?>
    <?php
	if($pPageLoadMode=='full'){
	?>
    </div><!-- ./wrapper -->
    <?php
	}
    ?>
  </body>
</html>