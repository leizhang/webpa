<?php

/**
 * 
 * Class : WizardStep5  (Create new assessment wizard)
 * 			
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.1
 * 
 */

require_once("../../../include/inc_global.php");

class WizardStep5 {

	// Public
	public $wizard = null;
	public $step = 5;


	/*
	* CONSTRUCTOR
	*/
	function WizardStep5(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = '&lt; Back';
		$this->wizard->next_button = 'Finish';
		$this->wizard->cancel_button = 'Cancel';
	}// /WizardStep4()


	function head() {
		?>
<script language="JavaScript" type="text/javascript">
<!--

	function body_onload() {
	}// /body_onload()

//-->
</script>
<?php
	}// /->head()
	
	
	function form() {
		$DB =& $this->wizard->get_var('db');
		$config =& $this->wizard->get_var('config');
		
		$now = mktime();
		
		require_once(DOC__ROOT . '/include/classes/class_form.php');
		require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
		?>
		<p>Your assessment is now ready for creation.</p>
		<p>Please review the details below. When you're are satisfied, click <em>Finish</em> to create your peer assessment.</p>

		<h2>Assessment Details</h2>
		<div class="form_section">
			<table class="form" cellpadding="2" cellspacing="2">
			<tr>
				<th width="100">Name:</th>
				<td><?php echo($this->wizard->get_field('assessment_name'));?></td>
			</tr>
			<tr>
				<th>Opens at:</th>
				<td><?php echo(  date('G:i \o\n l, jS F Y',$this->wizard->get_field('open_date') )  );?></td>
			</tr>
			<tr>
				<th>Closes at:</th>
				<td><?php echo(  date('G:i \o\n l, jS F Y',$this->wizard->get_field('close_date') )  );?></td>
			</tr>
			<?php
				if ( ($this->wizard->get_field('open_date')<$now) && ($this->wizard->get_field('close_date')>$now) ) {
				?>
					<tr>
						<td>&nbsp;</td>
						<td>
							<div class="warning_box" style="width: 60%">
								Using these scheduled times, this assessment will be open for students to use immediately.
							</div>
						</td>
					</tr>
				<?php
				}

				if (!is_empty($this->wizard->get_field('introduction'))) {
					echo('<tr><td colspan="2" style="font-weight: bold;">Includes an introduction</td></tr>');
				}
			
				if ($this->wizard->get_field('email')== '1') {
					echo('<tr><td colspan="2" style="font-weight: bold;">All students for the assessment will be emailed when you finish.</td></tr>');
				}else{
					echo('<tr><td colspan="2" style="font-weight: bold;">All students for the assessment will <b>NOT</b> be emailed when you finish.</td></tr>');
				}
			?>
			</table>
		</div>
		
		
  		<h2>Assessment Type</h2>
  		<div class="form_section">
  			<?php if ($this->wizard->get_field('assessment_type')=='1'){
  				echo "Self and peer assessment";
  			}else{
  				echo "Peer assessment only";
  			} ?>
  		</div>
  

		<h2>Assessment Form</h2>
		<div class="form_section">
			<?php
        $form =& new Form(&$DB);
        if (!$form->load($this->wizard->get_field('form_id'))) {
        	$form = null;
			$this->wizard->next_button = null;
			
			?>
					<div class="error_box"><p><strong>ERROR : </strong>unable to load the selected form</p></div>
					<?php
        } else {
					echo("<p><strong>Form : </strong>{$form->name}</p>");
					$question_count = (int) $form->get_question_count();
					if ($question_count==0) {
						$this->wizard->next_button = null;
				
						?>
						<div class="error_box"><p><strong>ERROR : </strong>this form contains no questions</p></div>
						<?php	
					} else {
						echo('<div style="margin-left: 10px;">');
						for($i=0; $i<$question_count; $i++) {
							$question = $form->get_question($i);
							?>
								<div style="padding: 0px 2px 2px 10px;">- <?php echo( $question['text']['_data']); ?> (<?php echo( $question['range']['_data'] );?>)</div>
							<?php
						}
						echo('</div>');
					}
				}
			?>
		</div>

		<h2>Groups</h2>
		<div class="form_section">
			<?php
				$group_handler =& new GroupHandler();
				$collection = $group_handler->get_collection($this->wizard->get_field('collection_id'));
				
				if (!$collection) {
					$this->wizard->next_button = null;
					
					?>
					<div class="error_box"><p><strong>ERROR : </strong>unable to load the selected collection of groups</p></div>
					<?php
				} else {
					echo("<p><strong>Collection : </strong>{$collection->name}</p>");
					$groups = $collection->get_groups_array();
					if (count((array) $groups)==0) {
						$this->wizard->next_button = null;

						?>
						<div class="error_box"><p><strong>ERROR : </strong>this collection does not contain any groups</p></div>
						<?php
					} else {
						
						echo('<div style="margin-left: 10px;">');

						$num_groups = count($groups);
						if ($num_groups<=5) {
							foreach($groups as $group) {
								echo("<div>- {$group['group_name']}</div>");
							}
						} else {
							echo("<div>- {$groups[0]['group_name']}</div>");
							echo("<div>- {$groups[1]['group_name']}</div>");
							echo("<div>&nbsp; &nbsp;...</div>");
							echo('<div>- '. $groups[$num_groups-2]['group_name'] .'</div>');
							echo('<div>- '. $groups[$num_groups-1]['group_name'] .'</div>');
						}

						echo('</div>');
					}
				}
			?>
		</div>
		
		
		<?php
	}// /->form()

	
	function process_form() {
		$errors = null;
		
		return $errors;
	}// /->process_form()
	
}// /class: WizardStep4


?>
