<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring sidebar menus'),
            'button'  => [
                        'text' => $Lang->get('Add section'),
                        'link' => '/core/settings/menu/section/edit/',
                        'icon' => 'core/plus',
                        'priv' => 'perch.menus.manage',
                    ]
        ], $CurrentUser);

	   

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Menus',
        'link'     => '/core/settings/menu/',
        'icon'     => 'blocks/bookmark',
    ]);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Reorder',
        'link'     => '/core/settings/menu/reorder/',
        'icon'     => 'core/menu',
        'position' => 'end',
    ]);
    echo $Smartbar->render();
?>
<div class="inner">
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="reorder form-simple">
    <?php
        echo render_tree($MenuItems, 0, 'sortable sortable-tree');
        
        function render_tree($MenuItems, $parentID=0, $class=false)
        {
            $menu_items = $MenuItems->get_for_parent($parentID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';

            if (PerchUtil::count($menu_items)) {
                
                foreach($menu_items as $MenuItem) {
                    $s .= '<li id="category_'.$MenuItem->id().'" data-parent="'.$parentID.'"><div class="category">';
                    $s .= '<input type="text" name="c-'.$MenuItem->id().'" value="'.$MenuItem->itemOrder().'" />';
                    $s .= PerchUI::icon('blocks/bookmark');
                    $s .= ''.PerchUtil::html($MenuItem->itemTitle()).'</div>';
                    
                    $s .= render_tree($MenuItems, $MenuItem->id());
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