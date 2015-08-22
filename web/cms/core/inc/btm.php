	<div class="footer">        
	<?php  if ($CurrentUser->logged_in()) { ?>	
		<div class="version">
		    <?php
		        if (($CurrentUser->has_priv('perch.updatenotices')) && (version_compare($Perch->version, $Settings->get('latest_version')->settingValue(), '<'))) {
		            echo '<a href="http://grabaperch.com/update">' . sprintf(PerchLang::get('You are running version %s - a newer version is available.'), $Perch->version) . '</a>';
		        }
		    ?>
		</div>
		<?php  } ?>
		<div class="credit">
	        <?php
	            if (!$Settings->get('hideBranding')->settingValue()) {
	        
		           	if (PERCH_RUNWAY) {
		        ?>
		        <p><a href="http://grabaperch.com"><img src="<?php echo PERCH_LOGINPATH; ?>/core/runway/assets/img/runway.png" width="90" height="15" alt="Perch Runway" /></a>
		        <?php
		            }else{
		        ?>
		        <p><a href="http://grabaperch.com"><img src="<?php echo PERCH_LOGINPATH; ?>/core/assets/img/perch.png" width="35" height="12" alt="Perch" /></a>
		        <?php
		            }
		    echo PerchUtil::html(PerchLang::get('by')); ?> <a href="http://edgeofmyseat.com">edgeofmyseat.com</a></p>
	        <?php
	            }else{
	                echo '&nbsp;';
	            }
	    	?>
		</div>
	</div>
</div>
<?php
	if ($CurrentUser->logged_in()) {
?>
<script src="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/js/jquery-ui.js?v=<?php echo PerchUtil::html($Perch->version); ?>"></script>
<script src="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/js/perch.min.js?v=<?php echo PerchUtil::html($Perch->version); ?>"></script>
<?php
	$javascript = $Perch->get_javascript();
	foreach($javascript as $js) {
	    echo "\t".'<script src="'.PerchUtil::html($js).'"></script>'."\n";
	}
?>
<script>
	Perch.token = '<?php $CSRFForm = new PerchForm('csrf'); echo $CSRFForm->get_token(); ?>';
	Perch.path = '<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>';
	Perch.version = '<?php echo $Perch->version; ?>';
	<?php echo $Perch->get_javascript_blocks(); ?>
</script>
<?php
        echo $Perch->get_foot_content();
    }

    if (file_exists(PERCH_PATH.'/addons/plugins/ui/_config.inc')) {
        include PERCH_PATH.'/addons/plugins/ui/_config.inc';
    }
    
    if (PERCH_DEBUG) {
    	PerchUtil::debug('Queries: '. PerchDB_MySQL::$queries);
    	PerchUtil::debug('Memory: '. round(memory_get_peak_usage()/1024/1024, 4));
    	PerchUtil::output_debug(); 
    }
?>
</body>
</html><?php
flush();