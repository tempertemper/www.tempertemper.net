<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    

	$Roles   = new PerchUserRoles; 
    $Buckets = new PerchResourceBuckets;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Role = $Roles->find($id);
    }else{
        $id = false;
        $Role = false;
    }

    if (!is_object($Role)) {
    	PerchUtil::redirect(PERCH_LOGINPATH.'/core/users/roles/');
    }

    $buckets = $Buckets->get_all_unfiltered($Role);

    $role_bucket = [PerchResourceBuckets::factory([
        'name'      => $Role->roleSlug(),
        'label'     => $Role->roleTitle(),
        'file_path' => PerchUtil::file_path(PERCH_RESFILEPATH.'/'.$Role->roleSlug()),
        'web_path'  => PERCH_RESPATH.'/'.$Role->roleSlug(),
    ])];

    $buckets = array_merge($role_bucket, $buckets);




    $Form = $API->get('Form');

    if ($Form->posted() && $Form->validate()) {

        $Role->clear_bucket_privs();

        foreach($buckets as $Bucket) {

            $b = 'privs-'.$Bucket->get_name();

            if (PerchUtil::post($b)) {
                $privs = PerchUtil::post($b);

                $opts = [
                    'roleSelect'  => '0', 
                    'roleInsert'  => '0', 
                    'roleUpdate'  => '0', 
                    'roleDelete'  => '0', 
                    'roleDefault' => '0', 
                ];

                if (in_array('select', $privs)) {
                    $opts['roleSelect'] = '1';
                }

                if (in_array('insert', $privs)) {
                    $opts['roleInsert'] = '1';
                    $opts['roleUpdate'] = '1';
                }

                if (in_array('delete', $privs)) {
                    $opts['roleDelete'] = '1';
                }

                if (in_array('default', $privs)) {
                    $opts['roleDefault'] = '1';
                }

                $Role->set_bucket_privs($Bucket->get_name(), $opts);
            }

        }

   	}



