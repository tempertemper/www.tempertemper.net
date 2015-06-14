<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('These are the pages that belong to this navigation group. Each page can appear in multiple groups.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php printf(PerchLang::get('Editing %s Navigation Group'), PerchUtil::html($NavGroup->groupTitle())); ?></h1>
    
    <?php echo $Alert->output(); ?>

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    ?>
    <ul class="smartbar">
        <li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/pages/?id='.$groupID); ?>"><?php echo PerchLang::get('Pages'); ?></a></li>
        <li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/edit/?id='.$groupID); ?>"><?php echo PerchLang::get('Group Options'); ?></a></li>
        <li class="selected fin"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/reorder/?id='.$groupID); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li>
    </ul>
    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
    
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the pages to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();


        echo render_tree($Pages, $groupID, 0, 'sortable');
        
        function render_tree($Pages, $groupID, $parentID=0, $class=false)
        {
            $pages = $Pages->get_by_parent($parentID, $groupID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';
            
            if (PerchUtil::count($pages)) {
                
                foreach($pages as $Page) {
                    $s .= '<li id="page_'.$Page->id().'"><div class="page icon">';
                    $s .= '<input type="text" name="p-'.$Page->id().'" value="'.$Page->pageOrder().'" />';
                    $s .= ''.PerchUtil::html($Page->pageTitle()).'</div>';
                    
                    $s .= render_tree($Pages, $groupID, $Page->id(), $class);
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

<?php
    $Perch->add_javascript_block("


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

");
?>