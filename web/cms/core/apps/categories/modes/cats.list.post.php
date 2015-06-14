<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php //echo PerchLang::get(''); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
   
<?php if ($CurrentUser->has_priv('categories.create')) { ?>
<a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/categories/edit/?sid='.$Set->id()); ?>"><?php echo PerchLang::get('Add category'); ?></a>
<?php } // categories.create ?>


    <h1><?php echo PerchLang::get('Listing categories in ‘%s’ set', $Set->setTitle()); ?></h1>

    <?php

    echo $HTML->smartbar(
            $HTML->smartbar_breadcrumb(true, 
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
            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/reorder/?id='.$Set->id(),
                        'label' => PerchLang::get('Reorder Categories'),
                        'class' => 'icon reorder'
                    ), 
                    true
                )
        );



    if (PerchUtil::count($cats) > 0) {
    ?>
    <table id="content-list">
        <thead>
            <tr>
                <th class="kindofabigdeal"><?php echo PerchLang::get('Category'); ?></th>
                <th><?php echo PerchLang::get('Path'); ?></th>
                <th class="action"></th>
            </tr>
        </thead>
        <tbody>
        <?php
 
        
            foreach($cats as $Cat) {
                echo '<tr>';
                    echo '<td id="cat'.$Cat->id().'" class="level'.((int)$Cat->catDepth()-2).' primary">';

                    echo '  <a class="icon category" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/categories/edit/?id='.PerchUtil::html($Cat->id()).'"><span>' . PerchUtil::html($Cat->catTitle()) . '</span></a>';

                    if ($CurrentUser->has_priv('categories.create')) {
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/categories/edit/?pid='.$Cat->id()).'" class="create-subpage">'.PerchLang::get('New subcategory').'</a>';
                    }

                    echo'</td>';

                    echo '<td>'.PerchUtil::html($Cat->catPath()).'</td>';
                        
                    // Delete
                    echo '<td>';
                    if ($CurrentUser->has_priv('categories.delete')) {
                        echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/categories/delete/?id=' . PerchUtil::html($Cat->id()) . '" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                    }
                    echo '</td>';
                echo '</tr>';             
            }       
        ?>
        </tbody>
    </table>
    <?php
    }
        ?>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>        