<?php
	
	$block_type     = $_POST['add-block'];
	$block_index    = (int)$_POST['count'];
	$field_id	    = $item_id;

	echo PerchContent_Util::get_empty_block($field_id, $block_type, $block_index, $Page, $Template, $Form);
