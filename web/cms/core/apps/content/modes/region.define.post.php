<?php  

    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ region', PerchUtil::html($Region->regionKey())),
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

    echo $Smartbar->render();

    echo $HTML->heading2('Choose a template');

?>

    <form method="post" action="<?php echo PerchUtil::html($fTemplate->action()); ?>" class="form-simple">
			
        
            <div class="field-wrap">
                <?php echo $fTemplate->label('regionTemplate', 'Template'); ?>
                <div class="form-entry">
                <?php         
                    echo $fTemplate->grouped_select('regionTemplate', $Regions->get_templates(), $fTemplate->get('contentTemplate', false));                    
                ?>
                </div>
            </div>
    
            <div class="field-wrap checkbox-single">
                <?php echo $fTemplate->label('regionMultiple', 'Allow multiple items'); ?>
                <div class="form-entry">
                <?php echo $fTemplate->checkbox('regionMultiple', '1', '0'); ?>
                </div>
            </div>
    
<?php
    echo $HTML->submit_bar([
        'button' => $fTemplate->submit('btnsubmit', 'Submit', 'button')
        ]);

?>      
    </form>
