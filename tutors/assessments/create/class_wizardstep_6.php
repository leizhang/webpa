<?php
/**
 * 
 * Class : WizardStep6  (Create new assessment wizard)
 *
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.5
 * 
 */

require_once("../../../include/inc_global.php");

class WizardStep6 {

	// Public
	public $wizard = null;
	public $step = 6;

	
	/*
	* CONSTRUCTOR
	*/
	function WizardStep6(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = null;
		$this->wizard->next_button = null;
		$this->wizard->cancel_button = null;
	}// /WizardStep5()


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
		$user =& $this->wizard->get_var('user');

		require_once(DOC__ROOT . '/include/classes/class_assessment.php');
		require_once(DOC__ROOT .'/include/classes/class_form.php');
		require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
		require_once(DOC__ROOT . '/tutors/assessments/email/new_assessment.php');
		
		$errors = null;
		
		// Create the assessment object
		$assessment =& new Assessment($DB);
		$assessment->create();
		$assessment->owner_id = $user->id;
		$assessment->name = $this->wizard->get_field('assessment_name');
		$assessment->open_date = $this->wizard->get_field('open_date');
		$assessment->close_date = $this->wizard->get_field('close_date');
		$assessment->introduction = $this->wizard->get_field('introduction');
		$assessment->allow_feedback = $this->wizard->get_field('allow_feedback')==1;
		$assessment->assessment_type = $this->wizard->get_field('assessment_type', 1);
		$assessment->allow_assessment_feedback = $this->wizard->get_field('allow_student_input')==1;
		$assessment->email_opening = $this->wizard->get_field('email_opening') == 1;
		$assessment->email_closing = $this->wizard->get_field('email_closing') == 1;
		$assessment->feedback_name = $this->wizard->get_field('feedback_name');
		
		
		// Set the form to use for assessment
		$form =& new Form($DB);
		$form->load($this->wizard->get_field('form_id'));
		$assessment->set_form_xml( $form->get_xml() );
		$assessment->save();
		
		// Set the collection of groups to assess
		$group_handler =& new GroupHandler();
		$coll_id = $this->wizard->get_field('collection_id');
		$collection = $group_handler->clone_collection($coll_id);
		
		if (!$collection) {
			$errors[] = 'There was an error when trying to set the groups to be assessed - please use the contact system to report the error!';
		} else {
			$collection->set_owner_info($assessment->id, $config['app_id'], 'assessment');
			$collection->save();

			$curr_coll = $DB->fetch_row("SELECT * FROM collection_creation_method WHERE collection_id='$coll_id' ");
			
			if ($curr_coll) {
				$fields = array (
					'collection_id'    => $collection->id ,
					'creation_dt'      => date('Y-m-d H:i:s', mktime() ) ,
					'username'         => $curr_coll['username'] ,
					'user_id'          => $curr_coll['user_id'] ,
					'creation_method'  => $curr_coll['creation_method'] ,
					'other_text'       => $curr_coll['other_text'] ,
				);
				$DB->do_insert("REPLACE INTO collection_creation_method ({fields}) VALUES ({values}) ", $fields);
			}
			
			$assessment->set_collection_id($collection->id);
			$assessment->save();
		}
		
		//process the emails if the option is set.
		$send_email = $this->wizard->get_field('email');
		if($send_email == '1'){
			$_user_id = fetch_SESSION('_user_id', null);
			$subjectLn = 'Your Tutor has set a WebPA assessment';
			$body = "Your tutor has set a WebPA assessment for your group. The details are as below;" .
					"\n Assessment Name:  " . $this->wizard->get_field('assessment_name') . 
					"\n Open from:  " . date('G:i \o\n l, jS F Y', $this->wizard->get_field('open_date')) .
					"\n Closes on:  " . date('G:i \o\n l, jS F Y', $this->wizard->get_field('close_date')) .
					"\n To complete your assessment please go to: " . APP__WWW .
					"\n \n -------------------------------------------------------------------------------" .
					"\n This is an automated email sent by the WebPA tool \n\n";
			$returned = mail_assessment_notification ($coll_id, $subjectLn,$body, $_user_id);
			
			//deal will any errors that are returned.
			
			if($returned=='1'){
				
			}else{
				$errors[] = $returned; 
			}
		}


		// If errors, show them
		if (is_array($errors)) {
			$this->wizard->back_button = '&lt; Back';
			$this->wizard->cancel_button = 'Cancel';
			echo('<p><strong>Unable to create your new assessment.</strong></p>');
			echo('<p>To correct the problem, click <em>back</em> and amend the details entered.</p>');
		} else {// Else.. create the form!
			if ($assessment) {
				$assessment_qs = "a={$assessment->id}";
				?>
				<p><strong>Your new assessment has been created.</strong></p>
				<p style="margin-top: 20px;">To view or amend the details of your new assessment, you can use the <a href="../edit/edit_assessment.php?<?php echo($assessment_qs); ?>">assessment editor</a>.</p>
				<p style="margin-top: 20px;">To send an email alert to the students due to take this new assessment, use the <a href="../email/index.php?<?php echo($assessment_qs); ?>">email wizard</a>.</p>
				<p style="margin-top: 20px;">Alternatively, you can return to <a href="../index.php">my assessments</a>, or to the <a href="../../../">Web-PA home page</a>.</p>
				<?php	
			} else {
				?>
				<p><strong>An error occurred while trying to create your new assessment form.</strong></p>
				<p>You may be able to correct the problem by clicking <em>back</em>, and then <em>next</em> again.</p>
				<?php
			}
		}
	}// /->form()


	function process_form() {
		$this->wizard->_fields = array();	// kill the wizard's stored fields
		return null;
	}// /->process_form()
	
}// /class: WizardStep6


?>
