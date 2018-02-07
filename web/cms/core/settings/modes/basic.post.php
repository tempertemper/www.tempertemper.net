<?php

        if (!$image_folder_writable) {
            $Alert->set('warning', PerchLang::get('Your resources folder is not writable. Make this folder (%s) writable if you want to upload a custom logo.', '<code>'.PerchUtil::html($DefaultBucket->get_file_path()).'</code>'));
        }

    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing General Settings'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title'  => 'Settings',
        'link'   => '/core/settings/',
        'icon'   => 'core/gear',
    ]);

    echo $Smartbar->render();

?>
    
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" enctype="multipart/form-data" class="form-simple">


        <div class="field-wrap">
            <?php echo $Form->label('lang', 'Language'); ?>
            <div class="form-entry">
            <?php 
                $langs = PerchLang::get_lang_options();
                $opts = array();
                if (PerchUtil::count($langs)) {
                    foreach($langs as $lang) {
                        $opts[] = array('label'=>$lang, 'value'=>$lang);
                    }
                }
                echo $Form->select('lang', $opts, $Form->get($details, 'lang', 'en-gb'));
            ?>
            </div>
        </div>
        
        <div class="field-wrap">
            <?php echo $Form->label('customlogo', 'Upload a logo'); ?>
            <div class="form-entry">
            <?php echo $Form->image('customlogo', PerchLang::get('Select a file'));

                $logo = $Settings->get('logoPath')->settingValue();
                if ($logo) {
                    echo '<img src="'.PerchUtil::html($logo).'"  class="preview" alt="" width="150" />';

                    echo '<div class="remove">';
                    echo $Form->checkbox('logo_remove', '1', 0).' '.$Form->label('logo_remove', PerchLang::get('Remove image'), 'inline');
                    echo '</div>';
                }
            ?>
            </div>
        </div>
        
        <div class="field-wrap <?php echo $Form->error('headerColour', false);?>">
            <?php echo $Form->label('headerColour', 'Header colour'); ?>
            <div class="form-entry">
            <?php echo $Form->color('headerColour', $Form->get($details, 'headerColour', '#FFFFFF'), 'colour'); ?>
            </div>
        </div>
        
        <div class="field-wrap <?php echo $Form->error('headerScheme', false);?>">
            <?php echo $Form->label('headerScheme', 'Header colour scheme'); ?>
            <div class="form-entry">
            <?php 
				$opts = array();
				$opts[] = array('label'=>PerchLang::get('Dark text for light background colours'), 'value'=>'light');
				$opts[] = array('label'=>PerchLang::get('Light text for dark background colours'), 'value'=>'dark');

				echo $Form->select('headerScheme', $opts, $Form->get($details, 'headerScheme', 'light')); 
			?>
            </div>
        </div>

        
        <div class="field-wrap <?php echo $Form->error('siteURL', false);?>">
            <?php echo $Form->label('siteURL', 'Website URL'); ?>
            <div class="form-entry">
            <?php echo $Form->text('siteURL', $Form->get($details, 'siteURL', '/')); ?>
            </div>
        </div>

        
        <div class="field-wrap <?php echo $Form->error('helpURL', false);?>">
            <?php echo $Form->label('helpURL', 'Help button URL'); ?>
            <div class="form-entry">
            <?php echo $Form->text('helpURL', $Form->get($details, 'helpURL')); ?>
            </div>
        </div>
        
        <div class="field-wrap checkbox-single <?php echo $Form->error('dashboard', false);?>">
            <?php echo $Form->label('dashboard', 'Enable dashboard'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('dashboard', '1', $Form->get($details, 'dashboard')); ?>
            </div>
        </div>

        <div class="field-wrap checkbox-single <?php echo $Form->error('sidebar_back_link', false);?>">
            <?php echo $Form->label('sidebar_back_link', 'Show dedicated back link in sidebar'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('sidebar_back_link', '1', $Form->get($details, 'sidebar_back_link')); ?>
            </div>
        </div>

        <div class="field-wrap checkbox-single <?php echo $Form->error('hide_pwd_reset', false);?>">
            <?php echo $Form->label('hide_pwd_reset', 'Hide password reset'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('hide_pwd_reset', '1', $Form->get($details, 'hide_pwd_reset')); ?>
            </div>
        </div>

        <div class="field-wrap checkbox-single <?php echo $Form->error('keyboardShortcuts', false);?>">
            <?php echo $Form->label('keyboardShortcuts', 'Enable keyboard shortcuts'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('keyboardShortcuts', '1', $Form->get($details, 'keyboardShortcuts')); ?>
            </div>
        </div>

        <?php
            $app_settings   = $Perch->get_settings();
            if (PerchUtil::count($app_settings)) {
                $c = ' last';
            }else{
                $c = '';
            }
        ?>
        
        <div class="field-wrap checkbox-single<?php echo $c; ?>">
            <?php echo $Form->label('hideBranding', 'Hide Perch branding'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('hideBranding', '1',  $Form->get($details, 'hideBranding', '0')); ?>
            </div>
        </div>

<?php
    if (PERCH_RUNWAY) {
?>
        <div class="field-wrap checkbox-single <?php echo $Form->error('siteOffline', false);?>">
            <?php echo $Form->label('siteOffline', 'Make site offline'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('siteOffline', '1', $Form->get($details, 'siteOffline', '0')); ?>
            </div>
        </div>
<?php        
    }
?>
        
        <?php include('_app_settings.post.php'); ?>
    
        <div class="submit-bar">
            <div class="submit-bar-actions">
                <?php       
                    echo $Form->submit('submit', 'Save changes', 'button button-simple');
                
                    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'">' . PerchLang::get('Cancel'). '</a>'; 
                ?>
            </div>
        </div>
	</form>
