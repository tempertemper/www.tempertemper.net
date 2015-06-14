<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Update your asset with a new title, and add tags to help with searching.'); ?></p>
<p><?php echo PerchLang::get('Unused assets are automatically removed when fall out of use. If you wish to always keep this asset, check the box to make it a %slibrary asset%s', '<em>', '</em>'); ?>.</p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    <h1><?php 
            if ($Asset) {
                printf(PerchLang::get('Editing ‘%s’ Asset'), PerchUtil::html($Asset->resourceTitle()));     
            }else{
                echo PerchLang::get('Adding a New Asset'); 
            }
            
        ?></h1>
    
    <?php echo $Alert->output(); ?>

    <h2 class="h2"><?php echo PerchLang::get('Details'); ?>

        <?php
            if (is_object($Asset) && $CurrentUser->has_priv('assets.delete')) {
        ?>
            <a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/assets/delete/?id=<?php echo $Asset->id(); ?>" class="delete action <?php  echo ($Asset->in_use() ? '' : 'inline-delete'); ?>" data-msg="<?php echo PerchLang::get('Delete this asset?'); ?>"><?php echo PerchLang::get('Delete'); ?></a>
        <?php
            } // assets.delete
        ?>
    </h2>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned" <?php echo $Form->enctype(); ?>>

        <div class="field">
            <?php echo $Form->label('resourceTitle', 'Title'); ?>
            <?php echo $Form->text('resourceTitle', $Form->get($details, 'resourceTitle')); ?>
        </div>

<?php if ($CurrentUser->has_priv('assets.create')) { ?>
        <div class="field">
        <?php
            echo $Form->label('image', 'File');
            $FieldType = PerchFieldTypes::get($FieldTag->type(), $Form, $FieldTag);

            $item = false;

            if ($Asset) {
                $item = array('image'=> $Asset->get_fieldtype_profile());
            }

            echo '<div class="field-wrap">';
            echo $FieldType->render_inputs($item);
            echo '</div>';
        ?>
        </div>

        <?php if (!$Asset) { ?>
        <div class="field">
        <?php
            echo $Form->label('resourceBucket', 'Save in bucket');
            $opts = array();
            $buckets = $Assets->get_available_buckets();
            if (PerchUtil::count($buckets)) {
                foreach ($buckets as $bucket) {
                    $opts[] = array('label' => ucfirst($bucket), 'value' => $bucket);
                }
            }else{
                $opts[] = array('label' => PerchLang::get('Default'), 'value' => 'default');
            }
            echo $Form->select('resourceBucket', $opts, $Form->get($details, 'resourceBucket'));
        ?>
        </div>
        <?php } // if !Asset ?>

<?php } // assets.create ?>

        <div class="field">
            <?php echo $Form->label('tags', 'Tags'); ?>
            <?php 
                $edit_str = '';
                if ($Asset) {
                    $edit_str = $Tags->get_for_asset_as_edit_string($Asset->id());
                }
                
                echo $Form->text('tags', $Form->get(array('tags'=>$edit_str), 'tags'), '', false, 'text', 'data-tags="/core/apps/assets/async/tags.php"'); 
            ?>
            <?php echo $Form->hint(PerchLang::get('Complete each tag with a comma.')); ?>
        </div>
        <div class="field checkboxes">
            <?php echo $Form->label('resourceInLibrary', 'Mark as a library asset'); ?>
            
            <?php echo $Form->checkbox('resourceInLibrary', '1', $Form->get($details, 'resourceInLibrary')); ?>
            <?php echo $Form->hint(PerchLang::get('Library assets are kept, even if unused.')); ?>
        </div>



        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
	</form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
