<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ collection', PerchUtil::html($Collection->collectionKey())),
        'notifications' => true,
        ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];

    $links[] = [
        'title' => 'Collections',
        'link'  => '/core/apps/content/manage/collections/',
    ];

    $links[] = [
        'title' => $Collection->collectionKey(),
        'translate' => false,
        'link'  => '/core/apps/content/collections/?id='.$Collection->id(),
    ];


    $item = $details[0];
    $id = $item['itemID'];                   

    if (isset($item['perch_'.$id.'__title'])) {
        $title = PerchUtil::html(PerchUtil::excerpt($item['perch_'.$id.'__title'], 10));
    }else{
        if (isset($item['itemOrder'])) {
            $title = PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999);
        }else{
            $title = PerchLang::get('New Item');
        }
    }

    $links[] = [
        'title' => $title,
        'translate' => false,
        'link'  => '/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$details[0]['itemID']
    ];

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Item Options',
            'link'   => '/core/apps/content/collections/edit/options/?id='.$Collection->id().'&itm='.$details[0]['itemID'],
            'priv'   => 'content.collections.options',
            'icon'   => 'core/o-toggles',
        ]);


    if ($PrevItem) {
        $Smartbar->add_item([
            'title'    => 'Previous',
            'position' => 'end',
            'icon'     => 'core/o-navigate-left',
            'icon-size'=> 10,
            'link'     => '/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$PrevItem->itemID(),
        ]);
    }

    if ($NextItem) {
        $Smartbar->add_item([
            'title'         => 'Next',
            'position'      => 'end',
            'icon'          => 'core/o-navigate-right',
            'icon-position' => 'end',
            'icon-size'     => 10,
            'link'          => '/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$NextItem->itemID(),
        ]);
    }

    // Undo button
    if ($Item->is_undoable()) {
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


    // Reorder button    
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/reorder/collection/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);




    echo $Smartbar->render();

?>
<form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" <?php echo $Form->enctype(); ?> id="content-edit"  class="form-simple"<?php

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

                echo '<h2 class="divider" id="item'.($id).'"><div>';
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
                    echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/delete/collection/item/?id='.PerchUtil::html($Collection->id()).'&amp;itm='.$id.'" class="button button-small action-alert inline-delete">'.PerchLang::get('Delete').'</a>';
                echo '</h2>';

                
                //display_item_fields($tags, $id, $item, false, $Form);
                PerchContent_Util::display_item_fields($tags, $id, $item, false, $Form, $Template);
                        
                
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

                if ($Collection->role_may_publish($CurrentUser)) {
                    echo '<input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" '.($draft?'checked="checked"':'').'  />';
                } else {
                    echo '<input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" checked disabled />';
                }



                echo '</div>';
            ?>
            </div>
            <div class="submit-bar-actions">
            <?php
                echo $Form->submit('btnsubmit', 'Save changes'); 
                echo ' <input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & add another')).'" id="add_another" class="button button-simple" />';
                echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/collections/?id='.$Collection->id().'">' . PerchLang::get('Cancel'). '</a>'; 
            ?>
            </div>
        </div>
        <?php

            echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & add another')).'" class="button button-simple to-the-top" />';
        ?>
    </div>
</form>