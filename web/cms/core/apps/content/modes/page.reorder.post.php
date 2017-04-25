<?php  
    echo $HTML->title_panel([
        'heading' => $Lang->get('Reordering pages'),
        ]);

    $Alert->set('warning', PerchLang::get('Drag and drop the pages to reorder them.'));
        $Alert->output();

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
            'active' => false,
            'title'  => 'All',
            'link'   => '/core/apps/content/'
        ]); 

        $Smartbar->add_item([
            'active' => false,
            'title'  => 'New',
            'link'   => '/core/apps/content/?filter=new'
        ]); 


        $Smartbar->add_item([
            'title'    => 'Reorder Pages',
            'link'     => '/core/apps/content/reorder/',
            'priv'     => 'content.pages.reorder',
            'icon'     => 'core/menu',
            'position' => 'end',
            'active'   => true,
        ]);  

        $Smartbar->add_item([
            
            'title'    => 'Republish',
            'link'     => '/core/apps/content/republish/',
            'priv'     => 'content.pages.republish',
            'icon'     => 'core/documents',
            'position' => 'end',
        ]);  

    echo $Smartbar->render();

    ?>

<div class="inner">
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="reorder form-simple">
    
    <?php
        


        echo render_tree($Pages, $CurrentUser, 0, 'sortable sortable-tree');
        
        function render_tree($Pages, $CurrentUser, $parentID=0, $class=false)
        {
            $pages = $Pages->get_by_parent($parentID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';

            if (PerchUtil::count($pages)) {
                
                foreach($pages as $Page) {
                    $s .= '<li id="page_'.$Page->id().'" data-parent="'.$parentID.'" '.(!$Page->role_may_create_subpages($CurrentUser)?' class="ui-nestedSortable-no-nesting "':'class=""').'><div class="page icon">';
                    $s .= '<input type="text" name="p-'.$Page->id().'" value="'.$Page->pageOrder().'" />';
                    $s .= PerchUI::icon('core/document');
                    $s .= ''.PerchUtil::html($Page->pageNavText()).'</div>';
                    
                    $s .= render_tree($Pages, $CurrentUser, $Page->id());
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



<script>
//    var locked_root = <?php echo ($CurrentUser->has_priv('content.pages.create.toplevel') ? 'false' : 'true'); ?>; 
</script>
<?php
/*
    $Perch->add_javascript_block("

    jQuery(function($){
        $('.sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
            disableNesting: 'ui-nestedSortable-no-nesting',
            protectRoot: locked_root,
            isAllowed: function(item, parent) {
                if (locked_root && parent==null) return false;
                return true;
            }
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
");
*/