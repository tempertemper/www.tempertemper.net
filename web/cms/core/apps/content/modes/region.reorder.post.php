<?php 

    if ($Region->regionTemplate() != '') {
        $view_page_url = false;

        if ($Region->regionPage() != '*') {

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $details    = $Region->get_items_for_editing();
                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }
        }

    } 

    echo $HTML->title_panel([
        'heading' => sprintf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; '),
    ]);


    $Alert->set('info', PerchLang::get('Drag and drop the items to reorder them.'));
    $Alert->output();



    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];
    if ($Region->regionPage()=='*') {
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id=-1',
        ];
    }else{
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id='.$Region->pageID(),
        ];
    }

    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];

    } else {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];
    }

    $Smartbar->add_item([
            'active' => false,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Region Options buttons
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Region Options',
            'link'   => '/core/apps/content/options/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-toggles',
        ]);

    if (PERCH_RUNWAY) {
         $Smartbar->add_item([
            'active' => false,
            'title'  => 'Revision History',
            'link'   => '/core/apps/content/revisions/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-backup',
            'position' => 'end',
        ]);

    }

    // View Page button
    if (isset($view_page_url) && $view_page_url) {
        $Smartbar->add_item([
                'active'        => false,
                'title'         => 'View Page',
                'link'          => $view_page_url,
                'link-absolute' => true,
                'position'      => 'end',
                'icon'          => 'core/o-world',
            ]);
    }

    // Reorder button
    if ($Region->regionMultiple()) {
        $Smartbar->add_item([
                'active'   => true,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/reorder/region/?id='.$Region->id(),
                'position' => 'end',
                'icon'     => 'core/menu',
            ]);
    } 


    echo $Smartbar->render();


?>
<div class="inner">
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="reorder form-simple">
    <?php

        if (PerchUtil::count($items)) {
            
            /*
            echo '<ul class="reorder">';
                $i = 1;
                foreach($items as $item) {
                    echo '<li class="icon">';
                            if (isset($item['_title'])) {
                                $title = $item['_title'];
                            }else{
                                $title = PerchLang::get('Item').' '.$i;
                            }
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="">'.PerchUtil::html($title).'</a>';
                            echo $Form->text('item_'.$item['itemID'], $item['itemOrder'], 's');
                    echo '</li>';
                    $i++;
                }
            echo '</ul>';
            */

            /* ---------------------------- */


            echo '<ol class="basic-sortable sortable-tree">';
                
                $i = 1;
                $first = true;
                $Template = new PerchTemplate;
                foreach($items as $item) {
                    echo '<li><div>';
                        
                            foreach($cols as $col) {

                                if ($first) { 
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="col">';
                                }else{
                                    echo '<span class="col">'.$col['title'].': ';
                                    //PerchUtil::debug($col);
                                }

                                if ($col['id']=='_title') {
                                    if (isset($item['_title'])) {
                                        $title = $item['_title'];
                                    }else{
                                        $title = PerchLang::get('Item').' '.$i;
                                    }
                                }else{
                                    if (isset($item[$col['id']])) {
                                        $title = $item[$col['id']];    
                                    }else{
                                        if ($first) {
                                            if (isset($item['_title'])) {
                                                $title = $item['_title'];
                                            }else{
                                                $title = PerchLang::get('Item').' '.$i;
                                            }
                                        }else{
                                            $title = '-';
                                        }
                                    }
                                    
                                }

                                if ($col['Tag']) {

                                    $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                                    $title = $FieldType->render_admin_listing($title);

                                    if ($col['Tag']->format()) {
                                        $title = $Template->format_value($col['Tag'], $title);
                                    }
                                }
                                
                                if ($first && trim($title)=='') $title = '#'.$item['_id'];

                                echo $title;    
                                                                

                                if ($first) {
                                    echo '</a>';  
                                }else{
                                    echo '</span>';
                                }
                                 

                                $first = false;
                            }
                            $first = true;

                            echo $Form->text('item_'.$item['itemID'], $item['itemOrder'], 's');
                        
                    echo '</div></li>';
                    $i++;
                }
                
            
            
            echo '</ol>';
            echo '<style>
            .col { display: inline-block; width: '.(100/PerchUtil::count($cols)).'%; color: rgba(0,0,0,0.5);}
            .col img { max-height: 1.5em; width: auto; display: inline-block; vertical-align: middle;}
            </style>';




            
        }
    
    ?>
        <div class="submit-bar">
            <?php 
            echo $Form->submit('reorder', 'Save Changes', 'button action');
            echo $Form->hidden('orders', ''); 
            ?>
        </div> 
    </form>
</div>