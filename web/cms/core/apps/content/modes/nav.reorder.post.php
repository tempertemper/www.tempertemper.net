<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ navigation group', PerchUtil::html($NavGroup->groupTitle())),
        ]);

    $Alert->set('info', PerchLang::get('Drag and drop the pages to reorder them.'));
        $Alert->output();

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active' => false,
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
        'icon'   => 'core/o-toggles',
    ]);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Reorder',
        'link'     => '/core/apps/content/navigation/reorder/?id='.$groupID,
        'icon'     => 'core/menu',
        'position' => 'end',
    ]);
    echo $Smartbar->render();

    echo $HTML->open('div.inner');
    echo $Form->form_start(false, 'reorder');
    
        echo render_tree($Pages, $groupID, 0, 'sortable sortable-tree');
        
        function render_tree($Pages, $groupID, $parentID=0, $class=false)
        {
            $pages = $Pages->get_by_parent($parentID, $groupID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';
            
            if (PerchUtil::count($pages)) {
                
                foreach($pages as $Page) {
                    $s .= '<li id="page_'.$Page->id().'"><div class="page">';
                    $s .= '<input type="text" name="p-'.$Page->id().'" value="'.$Page->pageOrder().'" />';
                    $s .= PerchUI::icon('core/document');
                    $s .= ''.PerchUtil::html($Page->pageNavText()).'</div>';
                    $s .= render_tree($Pages, $groupID, $Page->id(), $class);
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
    
<?php 
    echo $Form->form_end(); 
    echo $HTML->close('div.inner');
    /*$Perch->add_javascript_block("


    jQuery(function($){
        $('.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div'
        }).disableSelection()
          .on('click', 'a', function(e) {
              e.preventDefault();
          })
          .find('input').remove();
                
        $('form').on('submit', function(e){
            var serialized='';
            $('ol.sortable').each(function(i, o){
                var out;
                out = $(o).nestedSortable('serialize');
                if (out) serialized +='&'+out;
            });
            $('#orders').val(serialized);
        });
        
    });

");*/
