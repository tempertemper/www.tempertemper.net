<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p><?php echo PerchLang::get('You can edit roles and add new roles with custom privileges.'); ?></p>
    

    
	
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    
    <a class="button add" href="<?php echo PERCH_LOGINPATH; ?>/core/users/roles/edit/"><?php echo PerchLang::get('Add Role'); ?></a>

    <h1><?php echo PerchLang::get('Listing All User Roles'); ?></h1>
    
    <?php echo $Alert->output(); ?>

    

    <table>
        <thead>
            <tr>
                <th class="first"><?php echo PerchLang::get('Role'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (PerchUtil::count($roles)) {
                foreach($roles as $item) {
                    echo '<tr class="'.PerchUtil::flip('odd').'">';
                        echo '<td><a href="edit/?id=' . PerchUtil::html($item->id()) . '">' . PerchUtil::html($item->roleTitle()) . '</a></td>';
                        if (!$item->roleMasterAdmin()) {
                            echo '<td><a href="delete/?id=' . PerchUtil::html($item->id()) . '" class="delete">'.PerchLang::get('Delete').'</a></td>';
                        }else{
                            echo '<td><span class="delete" title="'.PerchLang::get('You cannot delete the master admin role.').'">'.PerchLang::get('Delete').'</span></td>';
                        }
                    echo '</tr>';
                }
            }
        
        ?>
        </tbody>
    </table>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>