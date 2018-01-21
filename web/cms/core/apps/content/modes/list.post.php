<?php
    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing pages'),
        'button'  => [
                        'text' => $Lang->get('Add page'),
                        'link' => '/core/apps/content/page/add/',
                        'icon' => 'core/plus',
                        'priv' => 'content.pages.create',
                    ]
        ], $CurrentUser);

    $templates        = $Regions->get_templates_in_use();
    $template_options = [];
    if (PerchUtil::count($templates)) {
        foreach ($templates as $template) {
            if ($template['regionTemplate']!='') {
                $template_options[] = array(
                    'value' => $template['regionTemplate'],
                    'title' => $Regions->template_display_name($template['regionTemplate']),
                );
            }
        }
    }


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
            'active' => $filter=='all',
            'title'  => 'All',
            'link'   => '/core/apps/content/'
        ]); 

        $Smartbar->add_item([
            'active' => $filter=='new',
            'title'  => 'New',
            'link'   => '/core/apps/content/?filter=new'
        ]); 

        $Smartbar->add_item([
            'id'      => 'rtf',
            'title'   => 'By Region Type',
            'icon'    => 'core/o-grid',
            'active'  => PerchRequest::get('template'),
            'type'    => 'filter',
            'arg'     => 'template',
            'options' => $template_options,
            'actions' => [

                    ],
            ]);

        $Smartbar->add_item([
            'title'    => 'Reorder Pages',
            'link'     => '/core/apps/content/reorder/',
            'priv'     => 'content.pages.reorder',
            'icon'     => 'core/menu',
            'position' => 'end',
        ]);  

        $Smartbar->add_item([
            'title'    => 'Republish',
            'link'     => '/core/apps/content/republish/',
            'priv'     => 'content.pages.republish',
            'icon'     => 'core/documents',
            'position' => 'end',
        ]);  

    echo $Smartbar->render();

    echo $HTML->open('div.inner');

    if (PerchUtil::count($pages) > 0) {
    ?>
    <table class="<?php echo ($do_list_collapse?' collapse':''); ?> nested-list" id="content-list">
        <thead>
            <tr>
                <th class="kindofabigdeal"><?php echo PerchLang::get('Title'); ?></th>
                <th class="region"><?php echo PerchLang::get('Regions'); ?></th>
                <th class="action"></th>
            </tr>
        </thead>
        <tbody>
        <?php

            $icon_page          = PerchUI::icon('core/document', 16, PerchLang::get('Page')).' ';
            $icon_pages         = PerchUI::icon('core/documents', 16, PerchLang::get('Page')).' ';
            $icon_toggle_closed = PerchUI::icon('core/arrow-circle-right', 12, PerchLang::get('Subpages are not shown')).'';
            $icon_toggle_open   = PerchUI::icon('core/arrow-circle-down', 12, PerchLang::get('Subpages are shown')).'';
        
            $origin = ($Settings->get('content_skip_region_list')->val() ? 'f=pl' : 'f=x');

            foreach($pages as $Page) {

                $delete_link = '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/delete/?id=' . PerchUtil::html($Page->id()) . '" class="button button-small action-alert inline-delete"  data-delete="confirm-cascade" data-msg="'.PerchUtil::html(PerchLang::get('Are you sure? This will delete the page, all the content belonging to the page, and any pages below this page.'), true).'">'.PerchLang::get('Delete').'</a>';

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
                            $shared = false;

                            if ($Page->pagePath() == '*') $shared = true;

                            if (in_array($Page->id(), $expand_list)) {
                                $closed = false;
                            }

                            echo '<td id="page'.$Page->id().'" class="nested-level-'.((int)$Page->pageDepth()-1).' page'.$closed.($do_list_collapse?'':' notoggle').($page_has_drafts?' draft':'').'">';
                            

                            $arg = ($closed ? 'ex' : 'cl');
                            if ($do_list_collapse && $Page->subpages()) echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle">'.($closed ? $icon_toggle_closed : $icon_toggle_open).'</a>';


                           

                                echo '  <a class="page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?'.$origin.'&amp;id='.PerchUtil::html($Page->id()).'">'. ($shared ? $icon_pages : $icon_page) .'<span class="primary">' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

							 if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
	                                echo ' <a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subitem">'.PerchUI::icon('core/plus', 8).' '.PerchLang::get('New subpage').'</a>';
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
                                        
                                        // only show 20 items
                                        if ($count <= 20) {

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
                                        
                                        if ($count===20) {
                                            $region_html .= '&hellip;';
                                        }

                                        
                                        if ($region_html!='') $arr_region_html[] = $region_html;
                                    }
                                }

								echo implode(', ', $arr_region_html);

                            echo '</td>';    

                            // Preview
                            $button = '';
                            $button_count = 0;
                            if ($page_has_drafts && $Region->regionPage() != '*') {
                                $path = rtrim($Settings->get('siteURL')->val(), '/');
                                $button .= '<a href="'.PerchUtil::html($path.$Region->regionPage()).'?'.PERCH_PREVIEW_ARG.'=all" class="button button-small action-warning viewext">'.PerchLang::get('Preview').'</a>';
                                $button_count++;
                            }

                            if ($Page->role_may_delete($CurrentUser)) {
                                $button .= $delete_link;
                                $button_count++;
                            }

                            if ($button != '') {
                                if ($button_count > 1) {
                                    echo '<td class="action"><div class="button-group">'.$button.'&nbsp;</div></td>';
                                } else {
                                    echo '<td class="action">'.$button.'&nbsp;</td>';    
                                }
                                
                            } else {
                                echo '<td></td>';
                            }
                        
                        echo '</tr>';

                    }else{
                        // Expanded region
                        
                        foreach($regions as $Region) {
                            echo '<tr>';
                            if ($first_region_for_page) {

                                $closed = '';
                                $shared = false;

                                if ($Page->pagePath() == '*') $shared = true;

                                if ($do_list_collapse) {                       
                                    if (!in_array($Page->id(), $expand_list)) {
                                        $closed = ' closed';
                                    }
                                } 


                                echo '<td id="page'.$Page->id().'" class="nested-level-'.((int)$Page->pageDepth()-1).' page'.$closed.($Page->pagePath()=='*'?' shared':'').($do_list_collapse?'':' notoggle').($page_has_drafts?' draft':'').'">';


                                if ($do_list_collapse && ($Page->subpages() || PerchUtil::count($regions)>1)) {
                                    $arg = ($closed ? 'ex' : 'cl');
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle"><span>+</span></a>';
                                }


                                    echo '  <a class="page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?'.$origin.'&amp;id='.PerchUtil::html($Page->id()).'">'. ($shared ? $icon_pages : $icon_page) .'<span class="primary">' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

								if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
                                    echo ' <a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subitem">'.PerchUI::icon('core/plus', 8).' '.PerchLang::get('New subpage').'</a>';
                                }



                                     echo ' </td>';
                            }else{                        
                                echo '<td class="nested-level-'.((int)$Page->pageDepth()).'"><span class="ditto">-</span></td>';
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

                            // Preview / delete
                            $button = '';
                            $button_count = 0;
                            if ($page_has_drafts &&  $Region->regionPage() != '*') {
                                $path = rtrim($Settings->get('siteURL')->val(), '/');
                                $button .= '<a href="'.PerchUtil::html($path.$Region->regionPage()).'?'.PERCH_PREVIEW_ARG.'=all" class="button button-small action-warning viewext">'.PerchLang::get('Preview').'</a>';
                                $button_count++;
                            }

                            if ($Page->role_may_delete($CurrentUser)) {
                                $button .= $delete_link;
                                $button_count++;
                            }

                            if ($button != '') {
                                if ($button_count>1) {
                                    echo '<td class="action"><div class="button-group">'.$button.'&nbsp;</div></td>';
                                } else {
                                    echo '<td class="action">'.$button.'&nbsp;</td>';    
                                }
                                
                            } else {
                                echo '<td class="action"></td>';
                            }

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

                        echo '<td id="page'.$Page->id().'" class="nested-level-'.((int)$Page->pageDepth()-1).($do_list_collapse?'':' notoggle').' page'.$closed.'">';

                            if ($do_list_collapse && $Page->subpages()) {
								$arg = ($closed ? 'ex' : 'cl');
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?'.$arg.'='.$Page->id()).'" class="toggle icon">'.($closed ? $icon_toggle_closed : $icon_toggle_open).'</a>';
                            }
                        
	                       echo '<a class="page" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/?'.$origin.'&amp;id='.PerchUtil::html($Page->id()).'"> '.$icon_page.'<span class="primary">' . PerchUtil::html($Page->pageNavText()) . '</span></a>';

                           if ($CurrentUser->has_priv('content.pages.create') && $Page->role_may_create_subpages($CurrentUser) && $Page->pagePath()!='*') {
                                    echo ' <a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/add/?pid='.$Page->id()).'" class="create-subitem">'.PerchUI::icon('core/plus', 8).' '.PerchLang::get('New subpage').'</a>';
                            }


                        echo'</td>';
                        echo '<td class="region"></td>';
                        
                        // Delete
                       
                        if ($Page->role_may_delete($CurrentUser)) {
                            echo '<td class="action">';
                            echo $delete_link;
                            echo '&nbsp;</td>';
                        } else {
                            echo '<td class="action"></td>';
                        }
                        
                    echo '</tr>';
                }
                
            }       
        ?>
        </tbody>
    </table>
    <?php
    }else{
        
        if ($filter == 'all') { ?>
            <div class="notification notification-info"><?php echo PerchLang::get('No content yet?'); ?> <?php echo PerchLang::get('Make sure you have added some editable regions into your page, and then visited that page in your browser. Once you have, the regions should show up here.'); ?>
                <a class="notification-link" href="https://grabaperch.com/go/gettingstarted"><?php echo PerchLang::get('Read the getting started guide to find out more'); ?>&hellip;</a></div>
        <?php 
            } else {
        ?>
            <p class="alert filter"><?php echo PerchLang::get('Sorry, there\'s currently no content available based on that filter'); ?> - <a href="?by=all"><?php echo PerchLang::get('View all'); ?></a></p>
        <?php
            }
  
    }
    echo $HTML->close('div');