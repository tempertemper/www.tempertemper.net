<?php
    echo $HTML->title_panel([
    'heading' => PerchLang::get('Reordering Categories')
    ], $CurrentUser);

    $Alert->set('info', PerchLang::get('Drag and drop the categories to reorder them.'));
    $Alert->output();

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
    'active' => false,
    'type' => 'breadcrumb',
    'links' => [
        [
            'title' => 'Sets',
            'link'  => '/core/apps/categories/',
        ],
        [
            'title' => $Set->setTitle(),
            'link'  => '/core/apps/categories/sets/?id='.$Set->id(),
            'translate' => false,
        ]
    ],
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title'  => 'Set Options',
        'link'   => '/core/apps/categories/sets/edit?id='.$Set->id(),
        'icon'   => 'core/o-toggles',
    ]);

    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Reorder',
        'link'     => '/core/apps/categories/reorder/?id='.$Set->id(),
        'position' => 'end',
        'icon'     => 'core/menu',
    ]);

    echo $Smartbar->render();


    ?>
<div class="inner">
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="reorder form-simple">
    <?php
        echo render_tree($Categories, $Set, 0, 'sortable sortable-tree');
        
        function render_tree($Categories, $Set, $parentID=0, $class=false)
        {
            $categories = $Categories->get_by_parent($parentID, $Set->id());
            
            $s = '';
            $s = '<ol class="'.$class.'">';

            if (PerchUtil::count($categories)) {
                
                foreach($categories as $Category) {
                    $s .= '<li id="category_'.$Category->id().'" data-parent="'.$parentID.'"><div class="category">';
                    $s .= '<input type="text" name="c-'.$Category->id().'" value="'.$Category->catOrder().'" />';
                    $s .= PerchUI::icon('core/chart-pie');
                    $s .= ''.PerchUtil::html($Category->catTitle()).'</div>';
                    
                    $s .= render_tree($Categories, $Set, $Category->id());
                    $s .= '</li>';
                }
                
            }
            $s .= '</ol>';
            
            return $s;
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