<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p><?php echo PerchLang::get('Colours can be set to any colour value acceptable in CSS, such as <code>#FFFFFF</code> or <code>white</code>.'); ?></p>
    
    <p><?php echo PerchLang::get('The maximum recommended width for logos is 235px.'); ?></p>
    
    <h3><p><?php echo PerchLang::get('Dashboard'); ?></p></h3>
    
    <p><?php echo PerchLang::get('If the Dashboard is disabled then editors will be taken directly to the page listing on login.'); ?></p>
    
    <h3><p><?php echo PerchLang::get('Hiding Perch branding'); ?></p></h3>
    
    <p><?php echo PerchLang::get('Hiding Perch branding will remove all visible mention of Perch from the admin - including the favicon, Perch logos and link to edgeofmyseat.com'); ?></p>
    
    <h3><p><?php echo PerchLang::get('Pages'); ?></p></h3>
    
    <p><?php echo PerchLang::get('These settings give you control over the editing environment. By default, regions will be displayed in a single page editing mode, this can then be changed in the region setting per region if list/detail is more effective for that region. You can also choose whether the content list should be collapsed or not.'); ?></p>

    <p><?php echo PerchLang::get('The %sCtrl-E to edit%s option enables the Ctrl-E keyboard shortcut on your site pages to jump directly into editing. This requires the use of the %sperch_get_javascript()%s call on your pages.','<strong>', '</strong>', '<code>', '</code>'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>

<?php include ('_subnav.php'); ?>

	<h1><?php echo PerchLang::get('Editing General Settings'); ?></h1>
    
    <?php
        if (!$image_folder_writable) {
            $Alert->set('notice', PerchLang::get('Your resources folder is not writable. Make this folder (<code>') . PerchUtil::html(PERCH_RESPATH) . PerchLang::get('</code>) writable if you want to upload a custom logo.'));
        }
    ?>
    
    <?php echo $Alert->output(); ?>
    
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" enctype="multipart/form-data" class="magnetic-save-bar">
	
		<h2><?php echo PerchLang::get('Branding'); ?></h2>
	    
        <div class="field">
            <?php echo $Form->label('lang', 'Language'); ?>
            <?php 
                $langs = PerchLang::get_lang_options();
                $opts = array();
                if (PerchUtil::count($langs)) {
                    foreach($langs as $lang) {
                        $opts[] = array('label'=>$lang, 'value'=>$lang);
                    }
                }
                echo $Form->select('lang', $opts, $Form->get(@$details, 'lang', 'en-gb'));
            ?>
        </div>
        
        <div class="field">
            <?php echo $Form->label('customlogo', 'Upload a logo'); ?>
            <?php echo $Form->image('customlogo');

                $logo = $Settings->get('logoPath')->settingValue();
                if ($logo) {
                    echo '<img src="'.PerchUtil::html($logo).'"  class="preview" alt="" width="150" />';

                    echo '<div class="remove">';
                    echo $Form->checkbox('logo_remove', '1', 0).' '.$Form->label('logo_remove', PerchLang::get('Remove image'), 'inline');
                    echo '</div>';
                }
            ?>
        </div>
        
        <div class="field <?php echo $Form->error('headerColour', false);?>">
            <?php echo $Form->label('headerColour', 'Header colour'); ?>
            <?php echo $Form->color('headerColour', $Form->get(@$details, 'headerColour', '#FFFFFF'), 'colour'); ?>
        </div>
        
        <div class="field <?php echo $Form->error('headerScheme', false);?>">
            <?php echo $Form->label('headerScheme', 'Header colour scheme'); ?>
            <?php 
				$opts = array();
				$opts[] = array('label'=>PerchLang::get('Dark text for light background colours'), 'value'=>'light');
				$opts[] = array('label'=>PerchLang::get('Light text for dark background colours'), 'value'=>'dark');

				echo $Form->select('headerScheme', $opts, $Form->get(@$details, 'headerScheme', 'light')); 
			?>
        </div>

        
        <div class="field <?php echo $Form->error('siteURL', false);?>">
            <?php echo $Form->label('siteURL', 'Website URL'); ?>
            <?php echo $Form->text('siteURL', $Form->get(@$details, 'siteURL', '/')); ?>
        </div>

        
        <div class="field <?php echo $Form->error('helpURL', false);?>">
            <?php echo $Form->label('helpURL', 'Help button URL'); ?>
            <?php echo $Form->text('helpURL', $Form->get(@$details, 'helpURL')); ?>
        </div>
        
        <div class="field <?php echo $Form->error('dashboard', false);?>">
            <?php echo $Form->label('dashboard', 'Enable dashboard'); ?>
            <?php echo $Form->checkbox('dashboard', '1', $Form->get(@$details, 'dashboard')); ?>
        </div>

        <div class="field <?php echo $Form->error('hide_pwd_reset', false);?>">
            <?php echo $Form->label('hide_pwd_reset', 'Hide password reset'); ?>
            <?php echo $Form->checkbox('hide_pwd_reset', '1', $Form->get(@$details, 'hide_pwd_reset')); ?>
        </div>

        <?php
            $app_settings   = $Perch->get_settings();
            if (PerchUtil::count($app_settings)) {
                $c = ' last';
            }else{
                $c = '';
            }
        ?>
        
        <div class="field<?php echo $c; ?>">
            <?php echo $Form->label('hideBranding', 'Hide Perch branding'); ?>
            <?php echo $Form->checkbox('hideBranding', '1',  $Form->get(@$details, 'hideBranding', '0')); ?>
        </div>
        
        <?php include('_app_settings.post.php'); ?>
        
        <p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Save changes', 'button');
			
			    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'">' . PerchLang::get('Cancel'). '</a>'; 
			?>
		</p>
		
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
