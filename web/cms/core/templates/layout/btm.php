</main>
<!-- /MAIN PANEL -->
</div>
</div>
<?php
	$javascript = $Perch->get_javascript();
	foreach($javascript as $js) {
	    echo "\t".'<script src="'.PerchUtil::html($js).'"></script>'."\n";
	}
?>
<script>
	<?php echo $Perch->get_javascript_blocks(); ?>
</script>
<?php
    echo $Perch->get_foot_content();