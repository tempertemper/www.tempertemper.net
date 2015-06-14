
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>


   <h1><?php echo PerchLang::get('Reordering Routes'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */


    echo $HTML->smartbar(
        $HTML->smartbar_link(false, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/apps/content/routes/',
                    'label' => PerchLang::get('Routes'),
                )
            ),
        $HTML->smartbar_link(true, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/apps/content/routes/reorder/',
                    'label' => PerchLang::get('Reorder'),
                    'class' => 'icon reorder'
                ), 
                true
            )
    );




    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>


    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
    
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the routes to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();

        $cols = 2;

        $s = '<ul class="reorder" data-start="1">';
        if (PerchUtil::count($routes)) {
            
            foreach($routes as $Route) {
                $s .= '<li id="route_'.$Route->id().'" class="icon">';
                $s .= '<input type="text" name="item_'.$Route->id().'" value="'.$Route->routeOrder().'" />';
                $s .= '<pre class="col">'.PerchUtil::html($Route->routePattern()).'</pre>';
                $s .= '<span class="col">'.PerchUtil::html($Route->pagePath()).'</span>';
                $s .= '</li>';
            }
            
        }
        $s .= '</ul>';

        echo $s;

        echo '<style>
        .col { display: inline-block; width: '.(100/$cols).'%;}
        </style>'

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
    /*$Perch->add_javascript_block("

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
");*/
?>