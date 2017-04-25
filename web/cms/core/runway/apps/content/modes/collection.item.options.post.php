<?php 
    
    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing item options'),
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
            'active' => false,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => true,
            'title'  => 'Item Options',
            'link'   => '/core/apps/content/collections/options/?id='.$Collection->id(),
            'priv'   => 'content.collections.options',
            'icon'   => 'core/o-toggles',
        ]);


    // Reorder button    
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/reorder/collection/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);




    echo $Smartbar->render();

    echo $Form->form_start();
    echo $HTML->heading2('Search');
?>
        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('itemSearchable', 'Include in search results'); ?>
            <div class="form-entry">
            <?php
                $tmp = $options->to_array();
                echo $Form->checkbox('itemSearchable', '1', $Form->get($tmp, 'itemSearchable', 1)); ?>
            </div>
        </div>
<?php
    echo $HTML->submit_bar([
        'button' => $Form->submit('btnsubmit', 'Save', 'button'),
        'cancel_button' => 'core/apps/content/collections/edit/?id='.PerchUtil::html($id)
        ]);
    echo $Form->form_end();
