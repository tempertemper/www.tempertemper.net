<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ navigation group', PerchUtil::html($NavGroup->groupTitle())),
        ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
            'active' => true,
            'type'   => 'breadcrumb',
            'links'  => [
                [
                    'title' => 'Navigation groups',
                    'link'  => '/core/apps/content/navigation/',
                ],
                [
                    'title' => $NavGroup->groupTitle(),
                    'translate' => false,
                    'link'  => '/core/apps/content/navigation/pages/?id='.$groupID,
                ]
            ]
        ]);
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Group Options',
            'link'     => '/core/apps/content/navigation/edit/?id='.$groupID,
            'icon'     => 'core/o-toggles',
        ]);
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/navigation/reorder/?id='.$groupID,
            'position' => 'end',
            'icon'   => 'core/menu',
        ]);
    echo $Smartbar->render();

    echo $HTML->open('div.inner');

    echo render_tree($Pages, $groupID, 0, 'page-list');

    echo $HTML->close('div.inner');
    
    function render_tree($Pages, $groupID, $parentID=0, $class=false)
    {
        $pages = $Pages->get_by_parent($parentID, $groupID);
        
        $s = '';
        $s = '<ol class="'.$class.'">';

        $class = false;
        
        if (PerchUtil::count($pages)) {
            
            foreach($pages as $Page) {
                $s .= '<li><div class="page">';
                $s .= PerchUI::icon('core/document');
                $s .= PerchUtil::html($Page->pageNavText()).'</div>';
                $s .= render_tree($Pages, $groupID, $Page->id(), $class);
                $s .= '</li>';
            }
            
        }
        $s .= '</ol>';
        
        return $s;
    }
