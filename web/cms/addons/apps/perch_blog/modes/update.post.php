<div class="inner">
<div style="update-box">
	<div class="hd">
	    <h1>Software Update</h1>
	</div>

	<div class="bd">
	    <?php
				if (!$Paging->is_last_page()) {
					echo '<ul class="progress-list">';
						echo '<li class="progress-item progress-success">'.PerchUI::icon('core/circle-check').' Updating posts '.$Paging->lower_bound(). ' to '.$Paging->upper_bound().' of '.$Paging->total().'.</li>';
					echo '</ul>';
				}
			?>
	</div>
	<?php
	 	if ($Paging->is_last_page()) {
			echo '<div class="submit"><a href="'.$API->app_path().'" class="button button-simple action-success">Continue</a></div>';
		}
	?>
</div>
</div>
<?php
	if (!$Paging->is_last_page()) {
		$paging = $Paging->to_array();
		echo "<script>
				window.setTimeout(function(){
					window.location='".PerchUtil::html($paging['next_url'], true)."';
				}, 0)
				
			 </script>";
	}
