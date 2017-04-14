<?php 
    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing Tag ‘%s’', PerchUtil::html($Tag->tagTitle())),
        ]);

    echo $HTML->heading2('Details');
    echo $Form->form_start();
?>
        <div class="field-wrap">
            <?php echo $Form->label('tagTitle', 'Display title'); ?>
            <div class="form-entry">
                <?php echo $Form->text('tagTitle', $Form->get($details, 'tagTitle')); ?>
            </div>
        </div>
        
        <div class="field-wrap">
            <?php echo $Form->label('tagSlug', 'Tag'); ?>
            <div class="form-entry">
            <?php echo $Form->text('tagSlug', $Form->get($details, 'tagSlug')); ?>
            </div>
        </div>
<?php
    echo $HTML->submit_bar([
            'button' => $Form->submit('btnsubmit', 'Submit', 'button'),
        ]);
    echo $Form->form_end();