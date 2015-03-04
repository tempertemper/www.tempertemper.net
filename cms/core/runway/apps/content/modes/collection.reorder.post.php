<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>

    <h1><?php 
            printf(PerchLang::get('Editing %s Collection'),' &#8216;' . PerchUtil::html($Collection->collectionKey()) . '&#8217; '); 
        ?></h1>
    
    <?php echo $Alert->output(); ?>



	<ul class="smartbar">
        <li>
			<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
		</li>
		<?php
			if ($CurrentUser->has_priv('content.regions.options')) {
	            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/options/?id='.PerchUtil::html($Collection->id()).'">' . PerchLang::get('Options') . '</a></li>';
	        }
		
        ?>

		<li class="fin selected"><a class="icon reorder" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchLang::get('Reorder'); ?></a></li>
    </ul>


    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the items to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();


        if (PerchUtil::count($items)) {
           

            echo '<ul class="reorder">';
                
                $i = 1;
                $first = true;
                $Template = new PerchTemplate;
                foreach($items as $item) {
                    echo '<li class="icon">';
                        
                            foreach($cols as $col) {

                                if ($first) { 
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/collection/edit/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="col">';
                                }else{
                                    echo '<span class="col">'.$col['title'].': ';
                                    PerchUtil::debug($col);
                                }

                                if ($col['id']=='_title') {
                                    if (isset($item['_title'])) {
                                        $title = $item['_title'];
                                    }else{
                                        $title = PerchLang::get('Item').' '.$i;
                                    }
                                }else{
                                    if (isset($item[$col['id']])) {
                                        $title = $item[$col['id']];    
                                    }else{
                                        if ($first) {
                                            if (isset($item['_title'])) {
                                                $title = $item['_title'];
                                            }else{
                                                $title = PerchLang::get('Item').' '.$i;
                                            }
                                        }else{
                                            $title = '-';
                                        }
                                    }
                                    
                                }

                                if ($col['Tag']) {

                                    $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                                    $title = $FieldType->render_admin_listing($title);

                                    if ($col['Tag']->format()) {
                                        $title = $Template->format_value($col['Tag'], $title);
                                    }
                                }
                                
                                if ($first && trim($title)=='') $title = '#'.$item['_id'];

                                echo $title;    
                                                                

                                if ($first) {
                                    echo '</a>';  
                                }else{
                                    echo '</span>';
                                }
                                 

                                $first = false;
                            }
                            $first = true;

                            echo $Form->text('item_'.$item['itemID'], $item['itemOrder'], 's');
                        
                    echo '</li>';
                    $i++;
                }
                
            
            
            echo '</ul>';
            echo '<style>
            .col { display: inline-block; width: '.(100/PerchUtil::count($cols)).'%; color: rgba(0,0,0,0.5);}
            .col img { max-height: 1.5em; width: auto; display: inline-block; vertical-align: middle;}
            </style>';




            
        }
    
    ?>
    </form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
