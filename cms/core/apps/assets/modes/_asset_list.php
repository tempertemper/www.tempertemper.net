<?php
	if (PerchUtil::count($assets)) {
?>
<table class="list-asset">
    <thead>
        <tr>
            <th class="first"><?php echo PerchLang::get('Name'); ?></th>
            <th></th>
            <th><?php echo PerchLang::get('Type'); ?></th>
            <th><?php echo PerchLang::get('Dimensions'); ?></th>
            <th><?php echo PerchLang::get('Size'); ?></th>
            <th class="action last"></th>
        </tr>
    </thead>
    <tbody>
<?php
	foreach($assets as $Asset) { 
?>
	<tr>
		<td class="primary">
			<a href="<?php echo PERCH_LOGINPATH . '/core/apps/assets/edit/?id='.$Asset->id(); ?>">
				<?php echo PerchUtil::html($Asset->resourceTitle()); ?>
			</a>
		</td>
		<td class="asset-icon-cell"><span class="icon asset-icon asset-<?php echo $Asset->get_type(); ?>"></span></td>
		<td><?php 
			$type = $Asset->get_type();
			echo PerchUtil::html($Asset->display_mime()); ?></td>
		<td><?php if ($type=='image') {
				echo PerchUtil::html($Asset->display_width() . ' x '. $Asset->display_height());
				}else{
					echo '-';
				} ?></td>
		<td><?php echo PerchUtil::html($Asset->file_size()); ?></td>
		<td></td>
	</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}
?>
