<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include (PERCH_PATH.'/core/apps/content/modes/_subnav.php'); ?>


	    <h1><?php echo PerchLang::get('Editing Item Options'); ?></h1>
	   <?php echo $Alert->output(); ?>

		<ul class="smartbar">
            <li>
                <span class="set">
                <a class="sub" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
                <span class="sep icon"></span> 
                <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($Collection->id()).'&amp;itm='.$details[0]['itemID'];?>"><?php 
                        
                        $item = $details[0];
                        $id = $item['itemID'];                   
                
                        if (isset($item['perch_'.$id.'__title'])) {
                            echo PerchUtil::html(PerchUtil::excerpt($item['perch_'.$id.'__title'], 10));
                        }else{
                            if (isset($item['itemOrder'])) {
                                echo PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999);
                            }else{
                                echo PerchLang::get('New Item');
                            }
                        }
                ?></a>


                </span>
            </li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li class="selected"><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/options/?id='.PerchUtil::html($id).'">' . PerchLang::get('Options') . '</a></li>';
		        }

                echo '<li class="fin">';
                echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id()).'" class="icon reorder">'.PerchLang::get('Reorder').'</a>';
                echo '</li>';

			?>
        </ul>
		
    
        
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">
           
        <h2><?php echo PerchLang::get('Search'); ?></h2>

        <div class="field last">
            <?php echo $Form->label('itemSearchable', 'Include in search results'); ?>
            <?php
                $tmp = $options->to_array();
                echo $Form->checkbox('itemSearchable', '1', $Form->get($tmp, 'itemSearchable', 1)); ?>
        </div>

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>