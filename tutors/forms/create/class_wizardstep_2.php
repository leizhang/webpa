<?php

/**
 * 
 * Class : WizardStep2  (Clone a form wizard)
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
class WizardStep2 {

	// Public
	public $wizard = null;
	public $step = 2;

	
	/*
	* CONSTRUCTOR
	*/
	function WizardStep2(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = null;
		$this->wizard->next_button = null;
		$this->wizard->cancel_button = null;

		ob_start();
	}// /WizardStep2()


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
		$config =& $this->wizard->get_var('config');
		$DB =& $this->wizard->get_var('db');
		$user =& $this->wizard->get_var('user');
	
		$errors = null;
		
		$form =& new Form(&$DB);
		$form->create();
		$form->name = $this->wizard->get_field('form_name');
		$form->owner_id = $user->id;
		$form->type = $this->wizard->get_field('form_type');

		// If errors, show them
		if (is_array($errors)) {
			$this->wizard->back_button = '&lt; Back';
			$this->wizard->cancel_button = 'Cancel';
			echo('<p><strong>Unable to create your new form.</strong></p>');
			echo('<p>To correct the problem, click <em>back</em> and amend the details entered.</p>');
		} else {// Else.. create the form!
			
			$saved = $form->save();
			
			if (!$saved) {
				?>
				<p><strong>An error occurred while trying to create your new assessment form.</strong></p>
				<p>You may be able to correct the problem by clicking <em>back</em>, and then <em>next</em> again.</p>
				<?php	
			} else {
				ob_end_clean();
				header('Location: '. APP__WWW ."/tutors/forms/edit/edit_form.php?f={$form->id}");
				exit;
				?>
				<p><strong>Your new assessment form has been created.</strong></p>
				<p style="margin-top: 20px;">To add question and marking information to your form, you can use the <a href="../index.php?f=<?php echo($form->id); ?>">form editor</a>.</p>
				<p style="margin-top: 20px;">Alternatively, you can return to <a href="/tutors/forms/">my forms</a>, or to the <a href="/">Web-PA home page</a>.</p>
				<?php
			}
		}
	}// /->form()
	
	function process_form() {
		$this->wizard->_fields = array();	// kill the wizard's stored fields
		return null;
	}// /->process_form()
	
}// /class: WizardStep2


?>
