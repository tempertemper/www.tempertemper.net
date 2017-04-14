<?php 
    $heading = sprintf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; '); 

    echo $HTML->title_panel([
            'heading' => $heading,
            'button'  => [
                        'text' => $Lang->get('Add subpage'),
                        'link' => '/core/apps/content/page/add/?pid='.$Page->id(),
                        'icon' => 'core/plus',
                        'priv' => 'content.pages.create',
                    ]
        ], $CurrentUser);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
            
            'title'  => 'Regions',
            'link'   => '/core/apps/content/page/?id='.$Page->id(),
            'icon'   => 'core/o-grid',
        ]);

    if ($Page->pagePath()!='*') {
        $Smartbar->add_item([
            'active' => true,
            'title'  => 'Details',
            'link'   => '/core/apps/content/page/details/?id='.$Page->id(),
            'priv'   => 'content.pages.attributes',
            'icon'   => 'core/o-toggles',
        ]); 

        $Smartbar->add_item([
                'title'  => 'Location',
                'link'   => '/core/apps/content/page/url/?id='.$Page->id(),
                'priv'   => 'content.pages.manage_urls',
                'icon'   => 'core/o-signs',
            ]); 

        $Smartbar->add_item([
            'title'    => 'View Page',
            'link'     => rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath(),
            'icon'     => 'core/document',
            'position' => 'end',
            'new-tab'  => true,
            'link-absolute' => true,
            ]); 

        $Smartbar->add_item([
            'title'    => 'Settings',
            'link'     => '/core/apps/content/page/edit/?id='.$Page->id(),
            'priv'     => 'content.pages.edit',
            'icon'     => 'core/gear',
            'position' => 'end',
            ]);             
    }

    echo $Smartbar->render();

    echo $Form->form_start('editattr'); ?>

        <h2 class="divider"><div><?php echo PerchLang::get('Details'); ?></div></h2>

        <div class="field-wrap">
        	<?php echo $Form->label('pageTitle', 'Page title'); ?>
            <div class="form-entry">
        	<?php echo $Form->text('pageTitle', $Form->get($details, 'pageTitle')); ?>
            </div>
        </div>

        <div class="field-wrap">
            <?php echo $Form->label('pageNavText', 'Navigation text'); ?>
            <div class="form-entry">
            <?php echo $Form->text('pageNavText', $Form->get($details, 'pageNavText')); ?>
            </div>
        </div>
        <?php
            echo $Form->fields_from_template($Template, $details, array('pageTitle', 'pageNavText'));
        ?>
        <div class="submit-bar">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </div>
        
    <?php echo $Form->form_end(); 



        if (isset($created) && $created!==false) {
            echo '<img src="'.PerchUtil::html($Page->pagePath()).'" width="1" height="1" class="off-screen">';
        }
