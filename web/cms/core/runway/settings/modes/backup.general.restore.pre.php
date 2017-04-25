<?php
	$Plans  = new PerchBackupPlans;
	$Runs   = new PerchBackupRuns;

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Form = $API->get('Form');
    
	$db_files = [];
	$buckets  = [];
	$message  = false;
	$confirm  = false;

	set_error_handler(['PerchUtil', 'debug_error_handler']);


	if (PerchUtil::get('bucket')) {

		$Bucket = PerchResourceBuckets::get(PerchUtil::get('bucket'));

		if ($Bucket) {

			if (PerchUtiL::get('file')) {

				// Do the restore?

				$confirm = true;

				if ($Form->posted() && $Form->validate()) {

					if ($Runs->restore_from_file($Bucket, PerchUtil::get('file'))) {
						$message = $HTML->success_message('Your backup has been successfully restored. You will now be asked to  %sreauthenticate%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/backup/">', '</a>');
					}else{
						$message = $HTML->failure_message('Sorry, that backup could not be restored.');
					}

				}


			}else{

				// Get the list of files in the bucket
				
				$db_files = $Bucket->get_files_with_prefix('db_');
				
				if (PerchUtil::count($db_files)) {
					sort($db_files, SORT_NATURAL);
					$db_files = array_reverse($db_files);	
				}else{
					$message = $HTML->warning_message('The selected bucket contains no database backups');
				}

			}



		}

	}else{
		$buckets = PerchResourceBuckets::get_all_remote();
	}