<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this region'); ?></span></h3>
    
    <p><?php 
            echo PerchLang::get("This region may contain one or more items.");
            echo ' ';
            echo PerchLang::get("Select an item to edit its content.");
    ?></p>
    
    <?php
        if ($Region->regionTemplate() != '') {
            
            if ($CurrentUser->has_priv('content.regions.options')) {
                echo '<h4>'.PerchLang::get('Options').'</h4>';
            }else{
                echo '<h4>' . PerchLang::get('Page assignment') . '</h4>';
            }

            if ($Region->regionPage() == '*') {
                echo '<p>' . PerchLang::get('This region is shared across all pages.') . '</p>';
            }else{
                echo '<p>' . PerchLang::get('This region is only available within') . ':</p><p><code><a href="' . PerchUtil::html($Region->regionPage()) . '">' . PerchUtil::html($Region->regionPage()) . '</a></code></p>';
            }

            if ($CurrentUser->has_priv('content.regions.options')) {
                echo '<p>';
                echo ' <a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Set your options for this region.') . '</a></p>';
            }
        
        
        }
    ?>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
            printf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; '); 
        ?></h1>
    
    <?php echo $Alert->output(); ?>



	<ul class="smartbar">
        <li>
			<span class="set">
			<a class="sub" href="<?php 
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>">Regions</a> 
			<span class="sep icon"></span> 
			<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
			</span>
		</li>
		<?php
			if ($CurrentUser->has_priv('content.regions.options')) {
	            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Region Options') . '</a></li>';
	        }
		?>
		<li class="fin selected"><a class="icon reorder" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/reorder/region/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchLang::get('Reorder'); ?></a></li>
    </ul>





    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">
    <?php
        $Alert->set('notice', PerchLang::get('Drag and drop the items to reorder them.').' '. $Form->submit('reorder', 'Save Changes', 'button action'));
        $Alert->output();



        if (PerchUtil::count($items)) {
            
            /*
            echo '<ul class="reorder">';
                $i = 1;
                foreach($items as $item) {
                    echo '<li class="icon">';
                            if (isset($item['_title'])) {
                                $title = $item['_title'];
                            }else{
                                $title = PerchLang::get('Item').' '.$i;
                            }
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="">'.PerchUtil::html($title).'</a>';
                            echo $Form->text('item_'.$item['itemID'], $item['itemOrder'], 's');
                    echo '</li>';
                    $i++;
                }
            echo '</ul>';
            */

            /* ---------------------------- */


            echo '<ul class="reorder">';
                
                $i = 1;
                $first = true;
                $Template = new PerchTemplate;
                foreach($items as $item) {
                    echo '<li class="icon">';
                        
                            foreach($cols as $col) {

                                if ($first) { 
                                    echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="col">';
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

                                if ($col['Tag'] && $col['Tag']->type()) {

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
