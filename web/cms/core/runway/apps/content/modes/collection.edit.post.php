<?php 
    echo $HTML->title_panel([
        'heading' => $Lang->get('Creating a new collection'),
        ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
            'active' => true,
            'type'   => 'breadcrumb',
            'links'  => [
                [
                    'title'  => 'Collections',
                    'link'   => '/core/apps/content/manage/collections/',
                ],
                [
                    'title' => 'New',
                    'link'  => '/core/apps/content/manage/collections/edit/',
                ]
            ]
            
        ]);
    echo $Smartbar->render();

    echo $Form->form_start();
?>
    <div class="field-wrap">
        <?php echo $Form->label('collectionKey', 'Collection Key'); ?>
        <div class="form-entry">
        <?php
            echo $Form->text('collectionKey', $Form->get($details, 'collectionKey'));
            echo $Form->hint(PerchLang::get('Examples: Articles, Events, Locations, Departments'));
        ?>
        </div>
    </div>

    <div class="field-wrap">
        <?php echo $Form->label('collectionTemplate', 'Template'); ?>
        <div class="form-entry">
        <?php         
            echo $Form->grouped_select('collectionTemplate', $Regions->get_templates(false, false), $Form->get(array('collectionTemplate'=>''), 'collectionTemplate', 0));               
        ?>
        </div>
    </div>
<?php
    echo $HTML->submit_bar([
        'button' => $Form->submit('btnsubmit', 'Save', 'button'),
        'cancel_link' => '/core/apps/content/manage/collections/'
        ]);
    echo $Form->form_end();
