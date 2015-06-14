
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<p><?php echo PerchLang::get('This page shows you the pages of your site. If any page has a new editable region on it that has not yet been configured you will see NEW in the Type column for that page.'); ?></p>

<h3><?php echo PerchLang::get('Deleting pages'); ?></h3>
<p><?php echo PerchLang::get('Take care when deleting pages, as they cannot be recovered and incoming links will break. A page can only be deleted if it has no content and no sub-pages.'); ?></p>



<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

	<?php
		
	
		if ($CurrentUser->has_priv('content.pages.create')) {
        ?>
        <a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/'); ?>"><?php echo PerchLang::get('Add page'); ?></a>
        <?php
        } // create
	
	?>
    
    <h1><?php echo PerchLang::get('Listing pages'); ?></h1>

    
    <?php echo $Alert->output(); ?>
    


	<?php
	/* ----------------------------------------- SMART BAR ----------------------------------------- */
	?>



	<ul class="smartbar">
        <li><span class="set"><?php echo PerchLang::get('Filter'); ?></span></li>
        <li class="<?php echo ($filter=='all'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/'); ?>"><?php echo PerchLang::get('All'); ?></a></li>
        <li class="new <?php echo ($filter=='new'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?filter=new'); ?>"><?php echo PerchLang::get('New'); ?></a></li>
		<?php

            if ($filter == 'new') {
                $Alert->set('filter', PerchLang::get('You are viewing pages with new regions.'). ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/" class="action">'.PerchLang::get('Clear Filter').'</a>');
            }

	        $templates = $Regions->get_templates_in_use();
			if (PerchUtil::count($templates)) {
				
				$items = array();
				foreach ($templates as $template) {
					if ($template['regionTemplate']!='') {
						$items[] = array(
							'arg'=>'template',
							'val'=>$template['regionTemplate'],
							'label'=>$Regions->template_display_name($template['regionTemplate']),
                            'path'=>PERCH_LOGINPATH.'/core/apps/content/'
						);
					}
	            }
				
				echo PerchUtil::smartbar_filter('rtf', 'By Region Type', 'Filtered by ‘%s’', $items, 'region', $Alert, "You are viewing pages filtered by region type ‘%s’", PERCH_LOGINPATH.'/core/apps/content/');
		
		} ?>


        


		<?php 
        	if ($CurrentUser->has_priv('content.pages.reorder')) { 
        ?>
        <li class="fin"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/reorder/'); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li>
        <?php
        	}// reorder
        ?>

        <?php 
            if ($CurrentUser->has_priv('content.pages.republish')) { 
        ?>
        <li class="fin"><a class="icon page" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/republish/'); ?>"><?php echo PerchLang::get('Republish'); ?></a></li>
        <?php
            }// republish
        ?>
    </ul>
	

	 <?php echo $Alert->output(); ?>


	<?php
	/* ----------------------------------------- /SMART BAR ----------------------------------------- */
	?>


    <?php
    if (PerchUtil::count($pages) > 0) {
    ?>
    <table class="<?php echo ($do_list_collapse?' collapse':''); ?>" id="content-list">
        <thead>
            <tr>
                <th class="kindofabigdeal"><?php echo PerchLang::get('Title'); ?></th>
                <th class="region"><?php echo PerchLang::get('Regions'); ?></th>
                <th class="action"></th>
            </tr>
        </thead>
        <tbody>
        <?php
    
        
            foreach($pages as $Page) {

                if ($Page->pagePath()=='*') {
                    $regions = $shared_regions;
                }else{
					$new_only = ($filter=='new'?true:false);
					$template = ($filter=='template'? $template_to_filter: false);
				
                    $regions = $Regions->get_for_page($Page->id(), $include_shared=false, $new_only, $template);
                }
                
                            
                if (PerchUtil::count($regions)) {
                    $first_region_for_page = true;
                    
                    // do we have drafts?
                    $page_has_drafts = false;
                    foreach($regions as $Region) {
                        if ($Region->has_draft()) {
                            $page_has_drafts = true;
                            break;
                        }
                    }
                    
 
                    // Collapsed - no region rows
                    if ((!$do_regions || $do_list_collapse)  && ($Page->subpages() || PerchUtil::count($regions)>1)) {

                        echo '<tr>';
                        
                            $closed = ' closed';

                            if (in_array($Page->id(), $expand_list)) {
                                $closed = false;
                            }

                            echo '<td id="page'.$Page->id().'" class="level'.((int)$Page->pageDepth()-1).' page'.$closed.($Page->pagePath()=='*'?' shared':'').($do_list_collapse?'':' notoggle').($page_has_drafts?' draft':'').' primary">';




                            

                            $arg = ($closed ? 'ex' : 'cl');
                            if ($do_list_collapse && $Page->subpages()) echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle icon"><span>+</span></a>';


                           

                                echo '  <a class="icon page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?id='.PerchUtil::html($Page->id()).'"><span>' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

							 if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
	                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subpage">'.PerchLang::get('New subpage').'</a>';
	                         }
							
								// Draft
                                if ($page_has_drafts) echo '<span class="icon draft" title="'.PerchLang::get('This page has draft content.').'"></span>';
	

                            echo ' </td>';
                            
                            
                            // Region name / Edit link                        
                            echo '<td class="region">';
                                $new = false;
                                $count = 0;

								$arr_region_html = array();

                                foreach($regions as $Region) {

                                    if ($Region->role_may_view($CurrentUser, $Settings)) {
                                    
    									$region_html = '';

                                        $count++;
                                        
                                        // only show 7 items
                                        if ($count < 8) {

                                            if ($Region->role_may_edit($CurrentUser)) {

                                                // New?
                                                if ($Region->regionNew()) {
                                                    $new = true;
                                                    $region_html .= ' <span class="new">'.PerchLang::get('New').'</span> ';
                                                }


                                                $region_html .= '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '" class="edit">' . PerchUtil::html($Region->regionKey()) . '</a>';
                                            }else{
                                                $region_html .= '<span class="denied">'.PerchUtil::html($Region->regionKey()).'</span>';
                                            }
                                            
                                            
                                        
                                        }
                                        
                                        if ($count===7) {
                                            $region_html .= '&hellip;';
                                        }

                                        
                                        

                                        
                                        if ($region_html!='') $arr_region_html[] = $region_html;
                                    }
                                }

								echo implode(', ', $arr_region_html);

                                //echo ($new ? '<span class="new">'.PerchLang::get('New').'</span>' : '');

                            echo '</td>';    

                            // Preview
                            echo '<td>';

							if ($page_has_drafts && $Region->regionPage() != '*') {
                                $path = rtrim($Settings->get('siteURL')->val(), '/');
                                echo '<a href="'.PerchUtil::html($path.$Region->regionPage()).'?'.PERCH_PREVIEW_ARG.'=all" class="draft preview">'.PerchLang::get('Preview').'</a>';
                            }


                            echo '</td>';
                            
                            
                            
                            
                        
                        echo '</tr>';

                    }else{
                        // Expanded region
                        
                        foreach($regions as $Region) {
                            echo '<tr>';
                            if ($first_region_for_page) {

                                $closed = '';

                                if ($do_list_collapse) {                       
                                    if (!in_array($Page->id(), $expand_list)) {
                                        $closed = ' closed';
                                    }
                                } 


                                echo '<td id="page'.$Page->id().'" class="level'.((int)$Page->pageDepth()-1).' page'.$closed.($Page->pagePath()=='*'?' shared':'').($do_list_collapse?'':' notoggle').($page_has_drafts?' draft':'').' primary">';


                                if ($do_list_collapse && ($Page->subpages() || PerchUtil::count($regions)>1)) {
                                    $arg = ($closed ? 'ex' : 'cl');
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle icon"><span>+</span></a>';
                                }


                                

                                    echo '  <a class="icon page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?id='.PerchUtil::html($Page->id()).'"><span>' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

								if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subpage">'.PerchLang::get('New subpage').'</a>';
                                }



                                     echo ' </td>';
                            }else{                        
                                echo '<td class="level'.((int)$Page->pageDepth()).'"><span class="ditto">-</span></td>';
                            }

                            // Region name / Edit link                        
                            echo '<td class="region">';



                                if ($Region->role_may_view($CurrentUser, $Settings)) {
                                    if ($Region->role_may_edit($CurrentUser)) {
                                        echo ($Region->regionNew() ? ' <span class="new">'.PerchLang::get('New').'</span> ' : '');
                                        echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '" class="edit">' . PerchUtil::html($Region->regionKey()) . '</a>';
                                    }else{
                                        echo '<span class="denied">'.PerchUtil::html($Region->regionKey()).'</span>';
                                    }

                                    // Draft
                                    if ($Region->has_draft()) echo '<span class="draft" title="'.PerchLang::get('This item is a draft.').'"></span>';

                                    
                                }
                                
                                

                            echo '</td>';    

                            // Preview
                            echo '<td>';

                                if ($page_has_drafts && !$first_region_for_page && $Region->regionPage() != '*') {
                                    $path = rtrim($Settings->get('siteURL')->val(), '/');
                                    echo '<a href="'.PerchUtil::html($path.$Region->regionPage()).'?'.PERCH_PREVIEW_ARG.'=all" class="draft preview">'.PerchLang::get('Preview').'</a>';
                                }

                            echo '</td>';



                            echo '</tr>';
                            $first_region_for_page = false;
                        }
                        
                    }

                    
                    
                    
                    
                    
                }else{
                    // Page with no regions
                    
                     echo '<tr>';
                        
						$closed = '';

                        if ($do_list_collapse) {                       
                            if (!in_array($Page->id(), $expand_list)) {
                                $closed = ' closed';
                            }
                        } 

                                
                        echo '<td id="page'.$Page->id().'" class="level'.((int)$Page->pageDepth()-1).($do_list_collapse?'':' notoggle').' page'.$closed.' primary">';
                        

                            if ($do_list_collapse && $Page->subpages()) {
								$arg = ($closed ? 'ex' : 'cl');
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle icon"><span>+</span></a>';
                            }
                        
                            
                            

	                       echo '  <a class="icon page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?id='.PerchUtil::html($Page->id()).'"><span>' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

                           if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subpage">'.PerchLang::get('New subpage').'</a>';
                            }


                        echo'</td>';
                        echo '<td></td>';
                        
                        // Delete
                        echo '<td>';
                        if (($CurrentUser->has_priv('content.pages.delete') || ($CurrentUser->has_priv('content.pages.delete.own') && $Page->pageCreatorID()==$CurrentUser->id()) ) && !$Page->subpages()) {
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/delete/?id=' . PerchUtil::html($Page->id()) . '" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                        }else{
                            //echo '<span class="delete action">'.PerchLang::get('Delete').'</span>';
                        }
                        echo '</td>';
                    echo '</tr>';
                }
                
               
            }       
        ?>
        </tbody>
    </table>
    <?php
    }else{
    ?>
        
        <?php if ($filter == 'all') { ?>
            <p class="alert filter"><?php echo PerchLang::get('No content yet?'); ?> <?php echo PerchLang::get('Make sure you have added some editable regions into your page, and then visited that page in your browser. Once you have, the regions should show up here.'); ?>
                <a href="http://grabaperch.com/go/gettingstarted"><?php echo PerchLang::get('Read the getting started guide to find out more'); ?>&hellip;</a></p>
        <?php 
            } else {
        ?>
            <p class="alert filter"><?php echo PerchLang::get('Sorry, there\'s currently no content available based on that filter'); ?> - <a href="?by=all"><?php echo PerchLang::get('View all'); ?></a></p>
        <?php
            }
        ?>
        
    <?php    
    }
    ?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>