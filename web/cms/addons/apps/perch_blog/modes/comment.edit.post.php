<?php

        echo $HTML->title_panel([
            'heading' => $Lang->get('Editing a comment'),
            ], $CurrentUser);
        
        echo $Form->form_start('content-edit');

            echo $HTML->heading2('Comment on ‘%s’', $Post->postTitle());

            if ($Comment->webmention()) {
                echo '<div class="field-wrap">'.$Lang->get('This comment is a webmention (%s)', $Comment->webmentionType()).'</div>';
            }

            echo $Form->fields_from_template($Template, $details);

            if ($Comment->commentIP()) {
                echo '<div class="field-wrap"><label class="label">IP Address</label><span class="input">'.long2ip($Comment->commentIP()).'</span></div>';
		    }

    		$opts = array();
    		$opts[] = array('label'=>'', 'value'=>'');
    		$opts[] = array('label'=>$Lang->get('Live'), 'value'=>'LIVE');
    		$opts[] = array('label'=>$Lang->get('Spam'), 'value'=>'SPAM');
    		$opts[] = array('label'=>$Lang->get('Rejected'), 'value'=>'REJECTED');
    		$opts[] = array('label'=>$Lang->get('Pending'), 'value'=>'PENDING');
    		    		
    		echo $Form->select_field('commentStatus', 'Status', $opts, isset($details['commentStatus'])?$details['commentStatus']:false);
		
            $Form->add_another = true;
            echo $Form->submit_field('btnSubmit', 'Save', $API->app_path());

        echo $Form->form_end();
    
    