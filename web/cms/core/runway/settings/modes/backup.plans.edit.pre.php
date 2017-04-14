<?php

	$API  = new PerchAPI(1.0, 'core');
	$HTML = $API->get('HTML');
	$Lang = $API->get('Lang');

	$l1 = $Lang->get('Database and assets');
	$l2 = $Lang->get('Database only');
	$l3 = $Lang->get('Title');
	$l4 = $Lang->get('Backup');
	$l5 = $Lang->get('Active');
	$l6 = $Lang->get('Backup database every (hours)');
	$l7 = $Lang->get('Assets are backed up continuously');
	$l8 = $Lang->get('Target bucket');

	$bucket_opts = '';

	$buckets = PerchResourceBuckets::get_all_remote();

	if (PerchUtil::count($buckets)) {
		$opts = array();
		foreach($buckets as $Bucket) {
			if ($Bucket) $opts[] = ucwords($Bucket->get_name()).'|'.$Bucket->get_name();
		}
		$bucket_opts = implode(',',	$opts);
	}



	$default_fields = '<perch:backup id="planTitle" type="text" label="'.$l3.'" required="true" />
					   <perch:backup id="planRole" type="select" label="'.$l4.'" options="'.$l1.'|all,'.$l2.'|db" />
					   <perch:backup id="planFrequency" type="number" label="'.$l6.'" default="24" help="'.$l7.'" size="s" />
					   <perch:backup id="planBucket" type="select" label="'.$l8.'" options="'.$bucket_opts.'" required="true" />	   
					   <perch:backup id="planActive" type="checkbox" label="'.$l5.'" value="1" />
					   ';



	$Plans = new PerchBackupPlans;

	$planID   = false;
	$Plan     = false;
	$message = false;
	$details = array();
	$template = 'plan.html';

	if (PerchUtil::get('id')) {
		$planID    = (int) PerchUtil::get('id');
		$Plan      = $Plans->find($planID);
		$details   = $Plan->to_array();
		//$template  = $Plan->setTemplate();
	}

	if (!$CurrentUser->has_priv('categories.manage')) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/settings/backup/');
	}

	$Template   = $API->get('Template');
	$Template->set_from_string($default_fields, 'backup');


	$Form = $API->get('Form');
	$Form->handle_empty_block_generation($Template);
    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {		
    	
    	$data = $Form->get_posted_content($Template, $Plans, $Plan, false);

        if (!is_object($Plan)) {

            $Plan = $Plans->create($data);

            if (is_object($Plan)) {
            	PerchUtil::redirect(PERCH_LOGINPATH .'/core/settings/backup/edit/?id='.$Plan->id().'&created=1');	
            }
            
        }

        if (is_object($Plan)) {
        	$Plan->update($data);
        }
    	

        if (is_object($Plan)) {
            $message = $HTML->success_message('Your backup plan has been successfully edited. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/backup/">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that backup plan could not be edited.');
        }
        
    } 

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your backup plan has been successfully created. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/backup/">', '</a>');
    }
