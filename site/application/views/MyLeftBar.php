      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul id="ulRootMenu" class="sidebar-menu">
            <!--li class="header">MAIN NAVIGATION</li-->
            <?php
            $jsonMenu = file_get_contents('assets/myjson/left_menu.json');
            //print $jsonMenu;
            $dataMenu = json_decode($jsonMenu);
			//print_r($dataMenu);exit;
			$GLOBALS['menuMatchUriChar'] = 0;
			function myFrameworkIterateForDisplayMenu($dataMenus, $activeMenuId=''){
            	foreach ($dataMenus as $idx1 => $dtMenu) {
                	$hasChild = FALSE;
					if(isset($dtMenu->sub)){
						if(sizeof($dtMenu->sub)>0){
							$hasChild = TRUE;
						}
					}
					if(($dtMenu->linkType == 'internal')and ($dtMenu->linkUrl != '#')){
						$menuLink = base_url().$dtMenu->linkUrl;
					}else{
						$menuLink = $dtMenu->linkUrl;
					}
					$s1 = substr(base_url().uri_string(), 0, strlen($menuLink));
					//print '>>>>>>>>>>>>>>>>>>>';
					//print $GLOBALS['menuMatchUriChar'];
					//print '  ('.$s1.'=='.$menuLink.')  ';
					if($s1 == $menuLink){
						if($GLOBALS['menuMatchUriChar'] < strlen($menuLink)){
							//print '>>>>>>>>>>>>>>>>>>>';
							$GLOBALS['menuMatchUriChar'] = strlen($menuLink);
							$GLOBALS['currentMenuId'] = $dtMenu->id;
							$GLOBALS['currentMenuLabel'] = $dtMenu->label;
							?>
							<script type="text/javascript">
								APP_ACTIVE_MENU_ID = "<?php if(isset($GLOBALS['currentMenuId'])){print $GLOBALS['currentMenuId'];} ?>";
							</script>
							<?php
						}
					}
					if($hasChild){
						?>
						<li id="menu_<?php print $dtMenu->id;?>" 
							data-url="<?php print $dtMenu->linkUrl; ?>" 
							data-label="<?php print $dtMenu->label; ?>" 
							data-icon="<?php print $dtMenu->icon; ?>" 
							class="treeview">
              				<a href="<?php print $menuLink; ?>">
                				<i class="<?php print $dtMenu->icon; ?>"></i>
                				<span><?php print $dtMenu->label; ?></span>
                				<i class="fa fa-angle-left pull-right"></i>
              				</a>
              				<ul class="treeview-menu">
              				<?php myFrameworkIterateForDisplayMenu($dtMenu->sub, $activeMenuId);?>
              				</ul>
              			</li>
						<?php
					}else{
						$strClass = '';
						if($activeMenuId == $dtMenu->id ){
							$strClass = 'active';
						}
						?>
						<li id="menu_<?php print $dtMenu->id;?>" 
							data-url="<?php print $dtMenu->linkUrl; ?>" 
							data-label="<?php print $dtMenu->label; ?>" 
							data-icon="<?php print $dtMenu->icon; ?>" 
							class="<?php print $strClass; ?>">
              				<a href="<?php print $menuLink; ?>">
                				<i class="<?php print $dtMenu->icon; ?>"></i>
                				<span><?php print $dtMenu->label; ?></span>
              				</a>
              			</li>
						<?php
					}
            	}
			}
			if(isset($TDBFPARAM['currentMenuId'])){
				$s1=$TDBFPARAM['currentMenuId'];
			}else{$s1='';}
			myFrameworkIterateForDisplayMenu($dataMenu, $s1);
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
