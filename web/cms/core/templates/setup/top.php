<?php
	$steps = [
				[
					'id'	=> 'server',
					'title' => 'Server checks',
				],
				[
					'id'	=> 'db',
					'title' => 'Database',
				],
				[
					'id'	=> 'license',
					'title' => 'License',
				],
				[
					'id'	=> 'account',
					'title' => 'User account',
				],
			];

	if (PERCH_RUNWAY) {
		$steps[] = [
			'id' => 'rewrites',
			'title' => 'URL rewriting',
		];
	}


	$steps[] = [
			'id' => 'done',
			'title' => 'Done',
		];
	

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link media="all" rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/styles.css">
    <!--[if IE]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie9.css"><![endif]-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php
    	if (PERCH_RUNWAY) {
    		echo 'Perch Runway Setup';
    	} else {
    		echo 'Perch Setup';
    	}
  
  ?></title>
  <script src="<?php echo PERCH_LOGINPATH; ?>/core/assets/js/setup.js"></script>
</head>
<body>
<div class="page-setup">

<div class="setup-box">
    <div class="logo">
    	<?php
    		if (PERCH_RUNWAY) {
    			echo '<img src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/runway/assets/img/logo.png" alt="Perch" width="110">';
    		} else {
    			echo '<img src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/img/logo.png" alt="Perch" width="110">';
    		}
    	?>
    </div>

    <div class="bd">
    	<ul class="setup-steps">
<?php
		$step_found = false;

		foreach($steps as $step) {
			if ($step['id'] == $current_step) {
				$step_found = true;
				$class = '.setup-step-active';
				$icon  = PerchUI::icon('core/o-navigate-right', 8);
			} else {
				if (!$step_found) {
					$class = '.setup-step-complete';
					$icon  = PerchUI::icon('core/circle-check', 14);
				} else {
					$class = '';
					$icon  = '';
				}
			}

			echo $HTML->wrap('li.setup-step'.$class, $icon.' '.$step['title']);
		}
?>
    	</ul>