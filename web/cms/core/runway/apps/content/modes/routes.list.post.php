<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>


    <h1><?php 
             echo PerchLang::get('Listing Page Routes'); 
        ?></h1>
    
    <?php echo $Alert->output();


        echo $HTML->smartbar(
            $HTML->smartbar_link(true, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/content/routes/',
                        'label' => PerchLang::get('Routes'),
                    )
                ),
            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/content/routes/reorder/',
                        'label' => PerchLang::get('Reorder'),
                        'class' => 'icon reorder'
                    ), 
                    true
                )
        );




        if (PerchUtil::count($routes)) {

            echo '<table class="d itemlist">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>'.PerchLang::get('Pattern').'</th>';
                        echo '<th>'.PerchLang::get('Page').'</th>';
                        echo '<th>'.PerchLang::get('Order').'</th>';
                        echo '<th class="last action"></th>';
                    echo '</tr>';
                echo '</thead>';
            
                echo '<tbody>';
                foreach($routes as $Route) {
                    echo '<tr>';
                        echo '<td class="primary"><pre class="icon page">';
                            echo $Route->routePattern();
                        echo '</pre></td>';
                        echo '<td>';
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/page/edit/?id='.$Route->pageID().'">'.$Route->pagePath().'</a>';
                        echo '</td>';
                        echo '<td>';
                            echo $Route->routeOrder();
                        echo '</td>';
                        echo '<td>';
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/routes/delete/?id=' . PerchUtil::html($Route->id()) . '" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                            
                        echo ' </td>';
                    echo '</tr>';
                }
                echo '</tbody>';
            
            
            echo '</table>';
            
            
        
        }
    
    ?>



<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
