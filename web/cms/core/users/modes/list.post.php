<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <p><?php echo PerchLang::get('You can add user accounts with different roles.'); ?></p>
    <p><?php echo PerchLang::get('Note you cannot delete the Primary Admin user account, only edit it.'); ?></p>
    <p><?php echo PerchLang::get('You can add and remove roles, as well as adjusting their privileges.'); ?></p>
    
	
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    
    
	<a class="add button" href="<?php echo PERCH_LOGINPATH; ?>/core/users/add/"><?php echo PerchLang::get('Add User'); ?></a>


    <h1><?php echo PerchLang::get('Listing all user accounts'); ?></h1>
	


    <?php echo $Alert->output(); ?>

    

    <table class="users">
        <thead>
            <tr>
                <th class="first"><?php echo PerchLang::get('Username'); ?></th>
				<th><?php echo PerchLang::get('Name'); ?></th>                
				<th><?php echo PerchLang::get('Role'); ?></th>                
                <th><?php echo PerchLang::get('Email'); ?></th>
                <th><?php echo PerchLang::get('Last login'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (PerchUtil::count($users) > 0) {
                foreach($users as $item) {
                    echo '<tr class="'.PerchUtil::flip('odd').'">';
                        echo '<td class="icon ' . ($item->roleMasterAdmin()?'admin':'user') . '"><a href="edit/?id=' . PerchUtil::html($item->id()) . '">' . PerchUtil::html($item->userUsername()) . '</a></td>';
						echo '<td>' . PerchUtil::html($item->userGivenName().' '.$item->userFamilyName()) . '</td>';                        
						echo '<td>'; 
                            
                                if ($item->userMasterAdmin()) {
                                    echo '<strong>'.PerchUtil::html(PerchLang::get('Primary Admin'));
                                }else{
                                    echo PerchUtil::html(PerchLang::get($item->roleTitle()));
                                }

                        echo '</td>';
                        echo '<td><a href="mailto:' . PerchUtil::html($item->userEmail()) . '">' . PerchUtil::html($item->userEmail()) . '</a></td>';
                        echo '<td>' . PerchUtil::html(strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($item->userLastLogin()))) . '</td>';
                        
                        if ($item->id()!=$CurrentUser->id() && !$item->userMasterAdmin()) {
                            echo '<td><a href="delete/?id=' . PerchUtil::html($item->id()) . '" class="delete inline-delete" data-item-name="user">'.PerchLang::get('Delete').'</a></td>';
                        }else{
                            echo '<td></td>';
                        }
                    echo '</tr>';
                }
            }
        
        ?>
        </tbody>
    </table>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>