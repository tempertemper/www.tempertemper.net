<?php

    if ($Asset) {
        $heading = sprintf(PerchLang::get('Editing ‘%s’ Asset'), PerchUtil::html($Asset->resourceTitle()));     
    }else{
        $heading = PerchLang::get('Adding a New Asset'); 
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        ], $CurrentUser);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $links = [
        [
            'title' => 'Assets',
            'link'  => '/core/apps/assets/',
        ],
    ];

    if ($Asset) {
        $links[] = [
            'title' => $HTML->encode($Asset->resourceTitle()),
            'link'  => '/core/apps/assets/edit/?id='.$Asset->id(),
            'translate' => false,
        ];
    } else {
        [
            'title' => 'Upload',
            'link'  => '/core/apps/assets/edit/',
        ];
    }

    $Smartbar->add_item([
    'active' => true,
    'type' => 'breadcrumb',
    'links' => $links,
    ]);

    echo $Smartbar->render();

?>
    <h2 class="divider"><div><?php echo PerchLang::get('Details'); ?></div>
        <?php
            if (is_object($Asset) && $CurrentUser->has_priv('assets.delete')) {
        ?>
            <a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/assets/delete/?id=<?php echo $Asset->id(); ?>" class="button button-small action-alert <?php  echo ($Asset->in_use() ? '' : 'inline-delete'); ?>" data-msg="<?php echo PerchLang::get('Delete this asset?'); ?>"><?php echo PerchLang::get('Delete'); ?></a>
        <?php
            } // assets.delete
        ?>
    </h2>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple" <?php echo $Form->enctype(); ?>>

        <div class="field-wrap">
            <?php echo $Form->label('resourceTitle', 'Title'); ?>
            <div class="form-entry">
            <?php echo $Form->text('resourceTitle', $Form->get($details, 'resourceTitle')); ?>
            </div>
        </div>


<?php if ($CurrentUser->has_priv('assets.create')) { ?>
        <div class="field-wrap">
            <?php
                echo $Form->label('image', 'File');
            ?>
            <div class="form-entry">
            <?php
                $FieldType = PerchFieldTypes::get($FieldTag->type(), $Form, $FieldTag);

                $item = false;

                if ($Asset) {
                    $item = array('image'=> $Asset->get_fieldtype_profile());
                }
                
                echo $FieldType->render_inputs($item);
            ?>
            </div>
        </div>

        <?php if (!$Asset) { ?>
        <div class="field-wrap">
        <?php
            echo $Form->label('resourceBucket', 'Save in bucket');
            echo '<div class="form-entry">';
            $opts = array();
            $buckets = $Assets->get_available_buckets($CurrentUser, ['backup'], ['insert']);
            if (PerchUtil::count($buckets)) {
                foreach ($buckets as $bucket) {
                    $opts[] = array('label' => ucfirst($bucket), 'value' => $bucket);
                }
            }else{
                $opts[] = array('label' => PerchLang::get('Default'), 'value' => 'default');
            }
            echo $Form->select('resourceBucket', $opts, $Form->get($details, 'resourceBucket'));
            echo '</div>';
        ?>
        </div>
        <?php } // if !Asset ?>

<?php } // assets.create ?>





        <div class="field-wrap">
            <?php echo $Form->label('tags', 'Tags'); ?>
            <div class="form-entry">
            <?php 
                $edit_str = '';
                if ($Asset) {
                    $edit_str = $Tags->get_for_asset_as_edit_string($Asset->id());
                }
                
                echo $Form->text('tags', $Form->get(array('tags'=>$edit_str), 'tags'), '', false, 'input-simple m', 'data-tags="/core/apps/assets/async/tags/"'); 
            ?>
            <?php echo $Form->hint(PerchLang::get('Complete each tag with a comma.')); ?>
            </div>
        </div>
        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('resourceInLibrary', 'Mark as a library asset'); ?>
            
            <div class="form-entry">
                <?php echo $Form->checkbox('resourceInLibrary', '1', $Form->get($details, 'resourceInLibrary')); ?>           
            </div>
            <?php echo $Form->hint(PerchLang::get('Library assets are kept, even if unused.')); ?> 
        </div>



        <div class="submit-bar">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </div>
        
	</form>