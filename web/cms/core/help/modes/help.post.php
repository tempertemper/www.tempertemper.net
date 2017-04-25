<?php
	echo $HTML->title_panel([
        'heading' => 'Help & Support',
        ]);

	echo $HTML->open('div.inner');
?>
		<p>Welcome to the help page for your content administration panel.</p>

		<h2>Editing Content on your website</h2>

		<p>To get started editing content click the Pages link in the header of the administration section. This page displays all of the available editable pages across your website.</p>

		<p>If a page has an arrow to the left, that means you can click the arrow to see pages beneath this page in the website structure. If this list is too long you can use the filter bar at the top to display content by type - for example displaying only text blocks across the site.</p>

		<p>If a page is configured to allow subpages to be created, you will see a “New subpage” link when you move your mouse cursor over that row. Clicking that link will take you to a screen where the details of the new page can be entered.</p>

		<p>Click on the name of a page to see a list of editable regions on that page.</p>

		<h2>Edit text in a region</h2>

		<p>After selecting a page as described above, you can now edit the content in the Regions of that page.</p>

		<p>To edit any region click the region name. You can then complete the form changing the content as required. Click the Save button to make the change and the content on your website will be immediately updated. </p>

		<h2>Editors and formatting</h2>

		<p>The formatting controls and editors installed into Perch have been selected by the person who set up your site. We support a number of different text formatting languages and plugin editors which can be configured at install. If the formatting languages Textile or Markdown have been configured then any text input set to use one of those languages will display a link to view a help page on the formatting available to you.</p>

		<h3>Drafts and Preview</h3>

		<p>If you would like to save the content as a draft first and preview the change before committing it, select the “Save as draft” checkbox before clicking the Save button. You will then find a link appears at the top of the edit form that highlights the fact you are working on a draft and gives you a link to preview the content on the page you are editing.</p>

		<h3>Undoing a change</h3>

		<p>If you realise that you have made a mistake, click the Undo link in the top right of the smart bar above your Region edit form.</p>

		<h3>Regions that allow multiple blocks of content</h3>

		<p>Some regions will allow multiple items of content. These can be configured to display “All on one page” - typically for Regions with small amounts of content - or in “List/Detail Mode”. If you are editing all on one page then the multiple blocks will display one below the other and you can make changes to content in any or all of these blocks before saving the Region.</p>

		<p>If you are editing in List/Detail mode then the initial page of the Region will just show the title of each block in a list. You can re-order the blocks on this page. Click through to each individual block to edit and save the content.</p>

		<h2>Apps</h2>

		<p>Any Apps that have been installed as part of your site will be available under the Apps menu.</p>

		<?php
			$apps = $Perch->get_apps();
			if (PerchUtil::count($apps)) {
				foreach($apps as $app) {
					$file = PerchUtil::file_path(PERCH_PATH.'/addons/apps/'.$app['id'].'/help.php');
					if (file_exists($file)) {
						echo '<h2>'.PerchUtil::html($app['label']).'</h2>';
						include($file);
					}
				}
			}
		?>


		<h2>Getting further help</h2>

		<p>Perch is a highly configurable system and the help is is generic to most installs. For further help contact the person who developed your site as they will know which configuration options have been selected and be able to help you make changes to the editing environment as required.</p>




<?php echo $HTML->close('div'); ?>