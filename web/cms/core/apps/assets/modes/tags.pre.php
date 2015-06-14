<?php

	$Paging = new PerchPaging();
	$Paging->set_per_page(24);

    $Tags = new PerchAssets_Tags();

    $tags = $Tags->all($Paging);
   

?>