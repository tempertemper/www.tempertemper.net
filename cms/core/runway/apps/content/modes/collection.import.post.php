<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include (PERCH_PATH.'/core/apps/content/modes/_subnav.php'); ?>


	    <h1><?php echo PerchLang::get('Importing into Collection'); ?></h1>
	   <?php echo $Alert->output(); ?>

		<ul class="smartbar">
            <li>
				<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/?id='.PerchUtil::html($id);?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
			</li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li class=""><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/options/?id='.PerchUtil::html($id).'">' . PerchLang::get('Options') . '</a></li>';
		        }

                echo '<li class="fin">';
                echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id()).'" class="icon reorder">'.PerchLang::get('Reorder').'</a>';
                echo '</li>';
                echo '<li class="selected fin"><a class="icon import" href="'.PERCH_LOGINPATH . '/core/apps/content/collections/import/?id='.PerchUtil::html($Collection->id()).'">'.PerchLang::get('Import').'</a></li>';
			?>
        </ul>
		

    
        
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">
        
        <h2><?php echo PerchLang::get('Import'); ?></h2>
        

        <div class="field">
            <?php echo $Form->label('pageID', 'Page'); ?>
            <?php 
                $opts = array();
        
                $pages = $Pages->get_page_tree();
                if (PerchUtil::count($pages)) {
                    foreach($pages as $Item) {
                        $opts[] = array('label'=>str_repeat('-', ($Item->pageDepth()-1)).' '.$Item->pageNavText(), 'value'=>$Item->id());
                    }
                }
                    
                echo $Form->select('pageID', $opts, $Form->get($details, 'pageID')); 
            ?>
        </div>

        <?php if ($pageID) { ?>
        <div class="field last">
            <?php echo $Form->label('regionID', 'Region'); ?>
            <?php 
                $opts = array();
        
                $regions = $Regions->get_for_page($pageID, false);
                if (PerchUtil::count($regions)) {
                    foreach($regions as $RegionX) {
                        $opts[] = array('label'=>$RegionX->regionKey(), 'value'=>$RegionX->id());
                    }
                }
                    
                echo $Form->select('regionID', $opts, $Form->get($details, 'regionID')); 
            ?>
        </div>
        <?php } // if pageID ?>

        <?php

        if ($pageID && $regionID) {

            echo $HTML->heading2('About the source');

            echo '<div class="info">';
            echo $HTML->para('The source region %s uses template %s with the these fields:', $Region->regionKey(), '<code>'.$Region->regionTemplate().'</code>');

            $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');
            echo '<p><code>'.implode(', ', $Template->find_all_tag_ids()).'</code></p>';
            echo '</div>';

            echo $HTML->heading2('About the target');

            echo '<div class="info">';
            echo $HTML->para('The target collection %s uses template %s with the these fields:', $Collection->collectionKey(), '<code>'.$Collection->collectionTemplate().'</code>');

            $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');
            echo '<p><code>'.implode(', ', $Template->find_all_tag_ids()).'</code></p>';

            echo '</div>';

            echo $HTML->warning_message('If you are happy that the source and the target have compatible data structures, click Import to proceed.');
          

            echo $Form->hidden('go', 'go');

        }


        ?>


        <p class="submit">
            <?php 
                $label = 'Next';

                if ($pageID && $regionID) {
                    $label = 'Import';
                }
                

                echo $Form->submit('btnsubmit', $label, 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; 

            ?>
        </p>
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>