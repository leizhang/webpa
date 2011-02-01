<?php
/**
 * 
 * Class : WizardStep1  (Clone a form wizard)
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
				document.getElementById('form_name').focus();
			}// /body_onload()
		
		//-->
		</script>
		<?php
	}// /->head()
	
	
	function form() {
		?>
		<p>To create a new form you just need to give it a name. To avoid confusion, the name should be unique, but you can create forms using the same name if you wish.</p>
		<p>Your form will be reusable with any number of assessments, so if you intend to use it again you should give the form a more generic name and not name it after a module in a particular academic year.</p>
		<p>For example, <em>"Teamwork Assessment"</em> or <em>"ABC123 Group Coursework"</em>.</p>
		<table class="form" cellpadding="2" cellspacing="2">
		<tr>
			<th><label for="form_name">Name for this new form</label></th>
			<td><input type="text" name="form_name" id="form_name" maxlength="100" size="40" value="<?php echo( $this->wizard->get_field('form_name') ); ?>" /></td>
		</tr>
		</table>
		
		<br />
		<br />
		
		<label>What type scoring will your criteria use?</label>
		<p>Web-PA offers two different ways that your students can score each other.</p>
		<?php
		$form_type = $this->wizard->get_field('form_type', 'likert');
		?>
		<table>
		<tr>
			<td style="vertical-align: top;"><input type="radio" name="form_type" id="type_likert" value="likert" <?php echo( ( ($form_type=='likert') ? 'checked="checked"' : '') ); ?> /></td>
			<td>
				<label for="type_likert">Likert Scale <span style="font-weight: normal;">(default)</span></label>
				<p>The standard Web-PA scoring.  Students rate each other against a small likert scale, typically 1-5 or 1-10, by simply clicking the appropriate radio button.</p>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;"><input type="radio" name="form_type" id="type_split100" value="split100" <?php echo( ( ($form_type=='split100') ? 'checked="checked"' : '') ); ?> /></td>
			<td>
				<label for="type_split100">Split 100</label>
				<p>Students must split 100 marks between their teammates for each criterion, with each score being entered manually into the appropriate box.  The score for each criterion must total 100, so students using this method will be made more aware of the effects of their peer assessment scores, as giving more marks to one team mate means another must get less.</p>
			</td>
		</tr>
		</table>
		<?php			
	}// /->form()

	
	function process_form() {
		$errors = null;
		
		$this->wizard->set_field('form_name',fetch_POST('form_name'));
		if (is_empty($this->wizard->get_field('form_name'))) { $errors[] = 'You must provide a name for your new assessment form'; }

		$this->wizard->set_field('form_type',fetch_POST('form_type'));
		
		return $errors;
	}// /->process_form()
	
}// /class: WizardStep1


?>
