
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Drag and drop the pages or groups of pages to reorder them. Greyed out sections cannot be reordered.'); ?></p>

<h3><?php echo PerchLang::get('Advisory'); ?></h3>
<p><?php echo PerchLang::get('In general you should try to avoid moving pages out of their parent-child relationship in the site and try only to reorder within a section. This helps to keep your site structure logical for visitors. If you want a page to appear in a certain section you should create it in that section rather than creating it elsewhere and dragging it in.'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


	    <h1><?php echo PerchLang::get('Reordering Pages'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */

        $filter = false;
    ?>



    <ul class="smartbar">
        <li><span class="set"><?php echo PerchLang::get('Filter'); ?></span></li>
        <li class="<?php echo ($filter=='all'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/'); ?>"><?php echo PerchLang::get('All'); ?></a></li>
        <li class="new <?php echo ($filter=='new'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?filter=new'); ?>"><?php echo PerchLang::get('New'); ?></a></li>
        <?php

            if ($filter == 'new') {
                $Alert->set('filter', PerchLang::get('You are viewing pages with new regions.'). ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/" class="action">'.PerchLang::get('Clear Filter').'</a>');
            }

            $templates = $Regions->get_templates_in_use();
            if (PerchUtil::count($templates)) {
                
                $items = array();
                foreach ($templates as $template) {
                    if ($template['regionTemplate']!='') {
                        $items[] = array(
                            'arg'=>'template',
                            'val'=>$template['regionTemplate'],
                            'label'=>$Regions->template_display_name($template['regionTemplate']),
                            'path'=>PERCH_LOGINPATH.'/core/apps/content/'
                        );
                    }
                }
                
                echo PerchUtil::smartbar_filter('rtf', 'By Region Type', 'Filtered by ‘%s’', $items, 'region', $Alert, "You are viewing pages filtered by region type ‘%s’", PERCH_LOGINPATH.'/core/apps/content/');
        
        } ?>



        <?php 
            if ($CurrentUser->has_priv('content.pages.reorder')) { 
        ?>
        <li class="fin selected"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/reorder/'); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li>
        <?php
            }// reorder
        ?>

        <?php 
            if ($CurrentUser->has_priv('content.pages.republish')) { 
        ?>
        <li class="fin"><a class="icon page" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/republish/'); ?>"><?php echo PerchLang::get('Republish'); ?></a></li>
        <?php
            }// republish
        ?>
    </ul>
    

     <?php echo $Alert->output(); ?>


    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>







    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
    
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the pages to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();


        echo render_tree($Pages, $CurrentUser, 0, 'sortable');
        
        function render_tree($Pages, $CurrentUser, $parentID=0, $class=false)
        {
            $pages = $Pages->get_by_parent($parentID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';

            if (PerchUtil::count($pages)) {
                
                foreach($pages as $Page) {
                    $s .= '<li id="page_'.$Page->id().'" data-parent="'.$parentID.'" '.(!$Page->role_may_create_subpages($CurrentUser)?' class="ui-nestedSortable-no-nesting "':'class=""').'><div class="page icon">';
                    $s .= '<input type="text" name="p-'.$Page->id().'" value="'.$Page->pageOrder().'" />';
                    $s .= ''.PerchUtil::html($Page->pageNavText()).'</div>';
                    
                    $s .= render_tree($Pages, $CurrentUser, $Page->id());
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
    var locked_root = <?php echo ($CurrentUser->has_priv('content.pages.create.toplevel') ? 'false' : 'true'); ?>; 
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