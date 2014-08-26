<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

	<p><?php
		if ($Page->pagePath()=='*') {
			printf(PerchLang::get('These are all the editable regions that are shared across many pages of the site.')); 
		}else{
			printf(PerchLang::get('These are all the editable regions on the %s page.'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; '); 	
		}
	?></p>
	<p>
		<?php echo PerchLang::get('Click on the region name to begin editing content.'); ?>
	</p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

	<h1><?php 
		if ($Page->pagePath()=='*') {
			printf(PerchLang::get('Editing Shared Regions')); 
		}else{
			printf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; '); 	
		}
		
	?></h1>
    
    <?php echo $Alert->output(); ?>



	<ul class="smartbar">
        <li class="selected"><a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Page->id());?>"><?php echo PerchLang::get('Regions'); ?></a></li>
		<?php
            if ($CurrentUser->has_priv('content.pages.attributes') && $Page->pagePath()!='*') {
                echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/details/?id='.PerchUtil::html($Page->id()).'">' . PerchLang::get('Page Details') . '</a></li>';
            }
        ?>
		<?php
			if ($CurrentUser->has_priv('content.pages.edit') && $Page->pagePath()!='*') {
	            echo '<li class="fin"><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/edit/?id='.PerchUtil::html($Page->id()).'" class="icon setting">' . PerchLang::get('Page Options') . '</a></li>';
	        }

	        if ($Page->pagePath() != '*') {
	            $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath();

	            echo '<li class="fin">';
	            echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
	            echo '</li>';
	        }
		?>

    </ul>

	<table>
		<thead>
			<tr>
				<th><?php echo PerchLang::get('Region'); ?></th>
				<th><?php echo PerchLang::get('Type'); ?></th>
				<th><?php echo PerchLang::get('Items'); ?></th>
				<th class="action"></th>
				<th class="action"></th>
			</tr>
		</thead>
		<tbody>
		<?php
			if (PerchUtil::count($regions)) {
				foreach($regions as $Region) {
					if ($Region->role_may_view($CurrentUser, $Settings)) {
						echo '<tr>';
					
								// Region name / edit link
								echo '<td class="primary">';
							
								if ($Region->role_may_edit($CurrentUser)) {
		                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '" class="edit">' . PerchUtil::html($Region->regionKey()) . '</a>';
		                        }else{
		                            echo '<span class="denied">'.PerchUtil::html($Region->regionKey()).'</span>';
		                        }

		                        // Draft
		                        if ($Region->has_draft()) echo '<span class="draft icon" title="'.PerchLang::get('This item is a draft.').'"></span>';
							
							
								echo '</td>';
							
								// Region type
		                        echo '<td class="type">' . ($Region->regionNew() ? '<span class="new">'.PerchLang::get('New').'</span>' : PerchUtil::html($Regions->template_display_name($Region->regionTemplate()))) . '</td>';
							
						
							
								// Item count
								echo '<td>'.$Region->get_item_count().'</td>';
							
							
								// Draft preview
								echo '<td>';						
									if ($Region->has_draft() && $Region->regionPage() != '*') {
		                                $path = rtrim($Settings->get('siteURL')->val(), '/');
		                                echo '<a href="'.PerchUtil::html($path.$Region->regionPage()).'?'.PERCH_PREVIEW_ARG.'=all" class="draft preview">'.PerchLang::get('Preview').'</a>';
		                            }
								echo '</td>';
							
								// Delete
								echo '<td>';
		                        if ($CurrentUser->has_priv('content.regions.delete') || ($CurrentUser->has_priv('content.pages.delete.own') && $Page->pageCreatorID()==$CurrentUser->id())) {
		                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/delete/?id=' . PerchUtil::html($Region->id()) . '" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
		                        }
		                        echo '</td>';
					
					
						echo '</tr>';
					}
				}
			}
		
		?>
		</tbody>
	</table>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
