<?php

    if ($Region->regionTemplate() != '') {
        $view_page_url = false;

        if ($Region->regionPage() != '*') {

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }
        }

    }  

    if ($Region->regionPage()=='*') {
        $heading = PerchLang::get('Editing Shared Regions'); 
    }else{
        $heading = PerchLang::get('Editing %s Page',' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; ');     
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        'notifications' => true,
        ]);

    

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];
    if ($Region->regionPage()=='*') {
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id=-1',
        ];
    }else{
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id='.$Region->pageID(),
        ];
    }

    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];

        $item = $details[0];
        $id = $item['itemID'];                   

        if (isset($item['perch_'.$id.'__title'])) {
            $t = (PerchUtil::excerpt($item['perch_'.$id.'__title'], 10));
        }else{
            if (isset($item['itemOrder'])) {
                $t = PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999);
            }else{
                $t = PerchLang::get('New Item');
            }
        }

        $links[] = [
            'title' => $t,
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id().'&amp;itm='.$details[0]['itemID'],
        ];
    } else {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];
    }

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Region Options buttons
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Region Options',
            'link'   => '/core/apps/content/options/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-toggles',
        ]);

    // View Page button
    if (isset($view_page_url) && $view_page_url) {
        $Smartbar->add_item([
                'active'        => false,
                'title'         => 'View Page',
                'link'          => $view_page_url,
                'link-absolute' => true,
                'position'      => 'end',
                'icon'          => 'core/o-world',
            ]);
    }

    // Reorder button
    if ($Region->regionMultiple()) {
        $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/reorder/region/?id='.$Region->id(),
                'position' => 'end',
                'icon'     => 'core/menu',
            ]);
    } 

    // Undo button
    if ($Region->is_undoable()) {
        $Smartbar->add_item([
                'type'     => 'submit',
                'form'     => $fUndo,
                'fieldID'  => 'btnUndo',
                'active'   => false,
                'title'    => 'Undo',
                'position' => 'end',
                'icon'     => 'core/o-undo',
            ]);
    }

    echo $Smartbar->render();

?>
<form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" <?php echo $Form->enctype(); ?> id="content-edit" class="form-simple"<?php

if (PERCH_RUNWAY) {
    echo ' data-lock="'.PerchUtil::html($lock_key, true).'"';
}


?>>
    <div id="main-panel"<?php if ($place_token_on_main) echo 'data-token="'.PerchUtil::html($place_token_on_main->get_token()).'"'; ?>>
<?php
    /*  ------------------------------------ EDIT CONTENT ----------------------------------  */

 
    if ($template_help_html) {
        echo '<h2 class="divider"><div>' . PerchLang::get('Help') .'</div></h2>';
        echo '<div class="template-help">' . $template_help_html . '</div>';
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
                    echo '<h2 class="divider" id="item'.($id).'">';
                        echo '<div>';
                        if (isset($item['perch_'.$id.'__title'])) {
                            echo PerchUtil::html($item['perch_'.$id.'__title']);
                        }else{
                            if (isset($item['itemOrder'])) {
                                echo PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999);
                            }else{
                                //PerchUtil::debug($item);
                                echo PerchLang::get('New Item');
                            }
                            
                        }
                        echo '</div>';    
                        echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/delete/item/?id='.PerchUtil::html($Region->id()).'&amp;itm='.$id.'" class="button button-small action-alert" data-delete="confirm">'.PerchLang::get('Delete').'</a>';
                    echo '</h2>';
                }else{
                    echo '<h2 class="divider"><div>'. PerchUtil::html($Region->regionKey()).'</div></h2>';
                }
                
                PerchContent_Util::display_item_fields($tags, $id, $item, $Page, $Form, $Template);
              
                echo '</div>';
                
                $i++; // item count
            }
        } 
?>        
        </div>
        <div class="submit-bar <?php if ($Form->error) echo ' error'; ?>">
            <div class="field-wrap checkbox-single">
            <?php
                echo '<label class="save-as-draft" for="save_as_draft">'.PerchUtil::html(PerchLang::get('Save as draft')).'</label>';
                echo '<div class="form-entry">';
                echo '<input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" '.($draft?'checked="checked"':'').'  />';
                echo '</div>';
            ?>
            </div>
            <div class="submit-bar-actions">
            <?php 
                echo $Form->submit('btnsubmit', 'Save changes'); 
                
                if ($Region->regionMultiple()=='1') {
                    echo ' <input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & add another')).'" id="add_another" class="button button-simple" />';
                }
                
                
                

                if ($Region->regionMultiple()=='1' && $Region->get_option('edit_mode')=='listdetail') {
                    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'">' . PerchLang::get('cancel'). '</a>'; 
                }else{
                    if ($Region->regionPage() == '*') {
                        $pageID = '-1';
                    }else{
                        $pageID = $Page->id();
                    }

                    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/page/?id='.$pageID.'">' . PerchLang::get('Cancel'). '</a>'; 
                }
                
            ?>
            </div>
        </div>
    </div>
        <?php
            if ($Region->regionMultiple()=='1') {
                //echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & add another')).'" class="button button-simple to-the-top" />';

                echo '<button type="submit" name="add_another" class="button button-icon icon-left to-the-top">
                        <div>
                            '.PerchUI::icon('core/plus', 10, PerchLang::get('Save & add another')).'
                            <span>'.PerchUtil::html(PerchLang::get('Save & add another')).'</span>
                        </div>
                    </button>';
            }
        ?>
    
</form>