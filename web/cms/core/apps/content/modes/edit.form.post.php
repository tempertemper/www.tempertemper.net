<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this region'); ?></span></h3>
    <p>
        <?php 
            if ($Region->regionMultiple()=='1') {
                echo PerchLang::get("This region may contain one or more items.");
            }else{
                echo PerchLang::get("This region only has a single item.");
            }
            
            echo ' '. PerchLang::get("Required fields are marked with an asterisk.");
        ?>
    </p>
<?php

    if ($Region->regionTemplate() != '') {

        echo '<h3>' . PerchLang::get('Page assignment') . '</h3>';

        $view_page_url = false;

        if ($Region->regionPage() == '*') {
            echo '<p>' . PerchLang::get('This region is shared across all pages.') . '</p>';
        }else{

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }
           
            echo '<p>' . PerchLang::get('This region is only available within') . ':</p><p><code><a href="' . PerchUtil::html($view_page_url) . '">' . PerchUtil::html($Region->regionPage()) . '</a></code></p>';
        }

    }  

?>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    <h1><?php 
            if ($Region->regionPage()=='*') {
                printf(PerchLang::get('Editing Shared Regions')); 
            }else{
                printf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; ');     
            }

            
        ?></h1>
   <?php echo $Alert->output(); ?>
        <ul class="smartbar">
            <li class="selected">
                <span class="set">
                <a class="sub" href="<?php 
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>"><?php echo PerchLang::get('Regions'); ?></a> 
                <span class="sep icon"></span> 
                

                <?php
                    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
                ?>
                <a class="sub" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
                <span class="sep icon"></span> 
                <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id()).'&amp;itm='.$details[0]['itemID'];?>"><?php 
                        
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
                <?php
                    }else{
                ?>
                    <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
                <?php
                    }
                ?>

                </span>
            </li>
            <?php
                if ($CurrentUser->has_priv('content.regions.options')) {
                    echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Region Options') . '</a></li>';
                }
            ?>
            <?php
                if ($Region->regionMultiple()) {
                    echo '<li class="fin">';
                    echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/region/?id='.PerchUtil::html($Region->id()).'" class="icon reorder">' . PerchLang::get('Reorder') . '</a>';
                    echo '</li>';
                }

                if (isset($view_page_url) && $view_page_url) {
                    echo '<li class="fin">';
                    echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                    echo '</li>';
                }

            
                if ($Region->is_undoable()) {
                    echo '<li class="fin">';
                    echo '<form method="post" action="'.PerchUtil::html($fUndo->action()).'">';
                    echo '<div>'.$fUndo->submit('btnUndo', 'Undo', 'unbutton icon undo', true, true).'</div>';
                    echo '</form>';
                    echo '</li>';
                }


            
            ?>
        </ul>
<form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" <?php echo $Form->enctype(); ?> id="content-edit" class="magnetic-save-bar">
    <div id="main-panel"<?php if ($place_token_on_main) echo 'data-token="'.PerchUtil::html($place_token_on_main->get_token()).'"'; ?>>
<?php
    /*  ------------------------------------ EDIT CONTENT ----------------------------------  */

 
    if ($template_help_html) {
        echo '<h2><span>' . PerchLang::get('Help') .'</span></h2>';
        echo '<div id="template-help">' . $template_help_html . '</div>';
    }
    
?>
        <div class="items">
<?php

        if (is_array($tags)) {
            
            // loop through each item (usually one, sometimes more)
            $i = 0;
            foreach($details as $item) {

                $id = $item['itemID'];
                
                echo '<div class="edititem">';
                if ($Region->regionMultiple()) {
                    echo '<div class="h2" id="item'.($id).'">';
                        if (isset($item['perch_'.$id.'__title'])) {
                            echo '<h2>'. PerchUtil::html($item['perch_'.$id.'__title']) .'</h2>';
                        }else{
                            if (isset($item['itemOrder'])) {
                                echo '<h2>'. PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999).'</h2>';
                            }else{
                                //PerchUtil::debug($item);
                                echo '<h2>'. PerchLang::get('New Item'). '</h2>';
                            }
                            
                        }
                        
                        echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/delete/item/?id='.PerchUtil::html($Region->id()).'&amp;itm='.$id.'" class="delete action inline-delete">'.PerchLang::get('Delete').'</a>';
                    echo '</div>';
                }else{
                    echo '<h2 class="em">'. PerchUtil::html($Region->regionKey()).'</h2>';
                }
                
                PerchContent_Util::display_item_fields($tags, $id, $item, $Page, $Form, $Template);
              
                echo '</div>';
                
                $i++; // item count
            }
        } 
?>        
        </div>
        <p class="submit<?php if (defined('PERCH_NONSTICK_BUTTONS') && PERCH_NONSTICK_BUTTONS) echo ' nonstick'; ?><?php if ($Form->error) echo ' error'; ?>">
            <?php 
                echo $Form->submit('btnsubmit', 'Save Changes', 'button'); 
                
                if ($Region->regionMultiple()=='1') {
                    echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" id="add_another" class="button" />';
                }
                
                echo '<label class="save-as-draft" for="save_as_draft"><input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" '.($draft?'checked="checked"':'').'  /> '.PerchUtil::html(PerchLang::get('Save as Draft')).'</label>';
                

                if ($Region->regionMultiple()=='1' && $Region->get_option('edit_mode')=='listdetail') {
                    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'">' . PerchLang::get('Cancel'). '</a>'; 
                }else{
                    if ($Region->regionPage() == '*') {
                        $pageID = '-1';
                    }else{
                        $pageID = $Page->id();
                    }

                    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/page/?id='.$pageID.'">' . PerchLang::get('Cancel'). '</a>'; 
                }
                
            ?>
        </p>
        <?php
            if ($Region->regionMultiple()=='1') {
                echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" class="add button topadd" />';
            }
        ?>
    </div>
</form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>