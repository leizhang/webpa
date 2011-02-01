<?php
/**
 * 
 * Class : WizardStep1  (add new criterion wizard)
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
class WizardStep1 {

	// Public
	public $wizard = null;
	public $step = 1;


	/*
	* CONSTRUCTOR
	*/
	function WizardStep1(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = null;
		$this->wizard->next_button = 'Finish';
		$this->wizard->cancel_button = 'Cancel';
	}// /WizardStep1()


	function head() {
		?>
<script language="JavaScript" type="text/javascript">
<!--

	function body_onload() {
		document.getElementById('question_text').focus();
	}// /body_onload()

//-->
</script>
<?php
	}// /->head()
	
	
	function form() {
		$config = $this->wizard->get_var('config');
		require_once('../../../../library/functions/lib_form_functions.php');
		if (!$this->wizard->get_field('question_range_start')) { $this->wizard->set_field('question_range_start',1); }
		if (!$this->wizard->get_field('question_range_end')) { $this->wizard->set_field('question_range_end',5); }
		?>
		<p>To create a new marking question, you firstly need to enter the text of the question.</p>

		<div class="form_section">
			<p>You can phrase the assessment criteria however you like, either as statements or questions. The important thing is that it's clear what criteria the student is assessing.</p>
			
			<p>Use the description box to further clarify what you want the student's to assess when scoring this criterion.</p>
			<table cellpadding="2" cellspacing="2">
			<tr>
				<th><label for="question_text">Criterion Text</label></th>
				<td><input type="text" name="question_text" id="question_text" maxlength="255" size="50" value="<?php echo( $this->wizard->get_field('question_text') ); ?>" /></td>
			</tr>
			<tr>
				<th valign="top" width="100"><label for="question_desc">Description</label><br /><span style="font-size: 0.8em; font-weight: normal;">(optional)</span></th>
				<td><textarea name="question_desc" id="question_desc" cols="60" rows="3" style="width: 90%;"><?php echo( $this->wizard->get_field('question_desc') ); ?></textarea></td>
			</tr>
			</table>
			
			<p>You are using a Split 100 assessment form, so students will automatically be given 100 marks to distribute between all the team members.</p>
			
		</div>
		<?php
	}// /->form()

	
	function process_form() {
		$errors = null;
		
		$this->wizard->set_field('question_text',fetch_POST('question_text'));
		if (is_empty($this->wizard->get_field('question_text'))) { $errors[] = 'You must provide some text for your new criterion'; }

		$this->wizard->set_field('question_desc',fetch_POST('question_desc'));
		
		return $errors;
	}// /->process_form()
	
}// /class: WizardStep1


?>
