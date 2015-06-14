<?php
	include('PerchForms_Forms.class.php');
	include('PerchForms_Form.class.php');
	include('PerchForms_Responses.class.php');
	include('PerchForms_Response.class.php');

    $API   = new PerchAPI(1.0, 'perch_forms');
    $Lang  = $API->get('Lang');
    $Forms = new PerchForms_Forms($API);
    $forms = $Forms->all();

 
?>
<div class="widget">
	<h2>
		<?php echo $Lang->get('Forms'); ?>
	</h2>
	<div class="bd">
		<?php
			if (PerchUtil::count($forms)) {
				echo '<ul>';
				foreach($forms as $Form) {
					echo '<li>';
						echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/addons/apps/perch_forms/responses/?id='.$Form->id()).'">';
							echo PerchUtil::html($Form->formTitle());
						echo '</a>';
						echo '<a class="action" href="'.PerchUtil::html(PERCH_LOGINPATH.'/addons/apps/perch_forms/responses/export/?id='.$Form->id()).'">';
							echo $Lang->get('CSV');
						echo '</a>';
						echo '<span class="note">'.$Lang->get('%s responses', $Form->number_of_responses()).'</span>';
					echo '</li>';
				}
				echo '</ul>';
			}
		?>
	</div>
</div>