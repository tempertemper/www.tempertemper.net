<?php
    
        echo $HTML->title_panel([
            'heading' => sprintf(PerchLang::get('Delete master page ‘%s’'), PerchUtil::html($Template->templateTitle()))
        ]);


        /* ---- FORM ---- */
        echo $Form->form_start('edit');

            echo $HTML->warning_message('Are you sure you wish to delete the %s Master Page?', '<strong>'. PerchUtil::html($Template->templateTitle()). '</strong>');
            echo $Form->submit_field('btnSubmit', 'Delete', PERCH_LOGINPATH . '/core/apps/content/page/templates/');

        echo $Form->form_end();
        /* ---- /FORM ---- */
