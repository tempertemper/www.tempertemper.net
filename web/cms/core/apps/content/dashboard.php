<?php 
	include('PerchContent_Pages.class.php');
	include('PerchContent_Page.class.php');
	include('PerchContent_Regions.class.php');
	include('PerchContent_Region.class.php');

	$Pages = new PerchContent_Pages;
	$pages = $Pages->get_by_parent(0);

	$Regions = new PerchContent_Regions;
	$shared = $Regions->get_shared();

?>
<div class="widget">
	<h2>
		<?php 
			echo PerchLang::get('Pages');
			if ($CurrentUser->has_priv('content.pages.create')) {
				echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/').'" class="add button">'.PerchLang::get('Add Page').'</a>';
			}
		?>
	</h2>
	<div class="bd">
		<?php
			if (PerchUtil::count($pages)) {
				echo '<ul>';
				if (PerchUtil::count($shared)) {
					echo '<li>';
						echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?id=-1">';
							echo PerchUtil::html(PerchLang::get('Shared'));
						echo '</a>';
					echo '</li>';
				}

				foreach($pages as $Page) {
					echo '<li>';
						echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/?id='.$Page->id()).'">';
							echo PerchUtil::html($Page->pageNavText());
						echo '</a>';
					echo '</li>';
				}
				echo '</ul>';
			}
		?>
	</div>

</div>