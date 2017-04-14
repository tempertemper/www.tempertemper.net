<?php
    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ Collection', PerchUtil::html($Collection->collectionKey())),
        ]);

    $Alert->set('warning', PerchLang::get('Drag and drop the items to reorder them.'));
        $Alert->output();

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];

    $links[] = [
        'title' => 'Collections',
        'link'  => '/core/apps/content/manage/collections/',
    ];

    $links[] = [
        'title' => $Collection->collectionKey(),
        'translate' => false,
        'link'  => '/core/apps/content/collections/?id='.$Collection->id(),
    ];

    $Smartbar->add_item([
            
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Options',
            'link'   => '/core/apps/content/collections/options/?id='.$Collection->id(),
            'priv'   => 'content.collections.options',
            'icon'   => 'core/o-toggles',
        ]);

    // Import button
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Import',
            'link'     => '/core/apps/content/collections/import/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/inbox-download',
        ]);


    // Reorder button    
    $Smartbar->add_item([
            'active' => true,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/reorder/collection/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);




    echo $Smartbar->render();

?>
<div class="inner">
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="reorder form-simple">
<?php
        if (PerchUtil::count($items)) {
           

            echo '<ol class="basic-sortable sortable-tree">';
                
                $i = 1;
                $first = true;
                $Template = new PerchTemplate;
                foreach($items as $item) {
                    echo '<li><div>';
                        
                            foreach($cols as $col) {

                                if ($first) { 
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/collection/edit/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="col">';
                                }else{
                                    echo '<span class="col">'.$col['title'].': ';
                                    PerchUtil::debug($col);
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