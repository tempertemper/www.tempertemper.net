
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


   <h1><?php echo PerchLang::get('Reordering Categories'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */

    echo $HTML->smartbar(
            $HTML->smartbar_breadcrumb(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/?id='.$Set->id(),
                        'label' => $Set->setTitle(),
                    )
            ),
            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/edit?id='.$Set->id(),
                        'label' => PerchLang::get('Set Options'),
                    )
                ),
            $HTML->smartbar_link(true, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/reorder/?id='.$Set->id(),
                        'label' => PerchLang::get('Reorder Categories'),
                        'class' => 'icon reorder'
                    ), 
                    true
                )
        );


    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>


    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
    
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the categories to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();


        echo render_tree($Categories, $Set, 0, 'sortable');
        
        function render_tree($Categories, $Set, $parentID=0, $class=false)
        {
            $categories = $Categories->get_by_parent($parentID, $Set->id());
            
            $s = '';
            $s = '<ol class="'.$class.'">';

            if (PerchUtil::count($categories)) {
                
                foreach($categories as $Category) {
                    $s .= '<li id="category_'.$Category->id().'" data-parent="'.$parentID.'"><div class="category icon">';
                    $s .= '<input type="text" name="c-'.$Category->id().'" value="'.$Category->catOrder().'" />';
                    $s .= ''.PerchUtil::html($Category->catTitle()).'</div>';
                    
                    $s .= render_tree($Categories, $Set, $Category->id());
                    $s .= '</li>';
                }
                
            }
            $s .= '</ol>';
            
            return $s;
        }
    ?>
        <div>
            <?php echo $Form->hidden('orders', ''); ?>
        </div> 
    </form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>


<script>
    var locked_root = false; 
</script>
<?php
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
?>