<?php 

    
    echo $HTML->title_panel([
            'heading' => $Lang->get('Reordering routes'),
            ], $CurrentUser);

     $Alert->set('info', PerchLang::get('Drag and drop the routes to reorder them.'));
        $Alert->output();

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
        $Smartbar->add_item([
                'active' => false,
                'title'  => 'Routes',
                'link'   => '/core/apps/content/routes/',
                'icon'   => 'core/o-signs',
            ]);
        $Smartbar->add_item([
                'active'   => true,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/routes/reorder/',
                'position' => 'end',
                'icon'   => 'core/menu',
            ]);
        echo $Smartbar->render();


    echo $HTML->open('div.inner');
    echo $Form->form_start(false, 'reorder');


        $cols = 2;

        $s = '<ol class="basic-sortable sortable-tree" data-start="1">';
        if (PerchUtil::count($routes)) {
            
            foreach($routes as $Route) {
                $s .= '<li id="route_'.$Route->id().'" class="icon"><div>';
                $s .= '<input type="text" name="item_'.$Route->id().'" value="'.$Route->routeOrder().'" />';
                $s .= '<code class="col">'.PerchUtil::html($Route->routePattern()).'</code>';
                $s .= '<span class="col">'.PerchUtil::html($Route->pagePath()).'</span>';
                $s .= '</div></li>';
            }
            
        }
        $s .= '</ol>';

        echo $s;

        echo '<style>
        .col { display: inline-block; width: '.(100/$cols).'%;}
        </style>'

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