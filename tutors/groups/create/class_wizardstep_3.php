<?php

/**
 * 
 * Class : WizardStep3  (Create new groups wizard)
 *
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
class WizardStep3 {

	// Public
	public $wizard = null;
	public $step = 3;
	

	/*
	* CONSTRUCTOR
	*/
	function WizardStep3(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = '&lt; Back';
		$this->wizard->next_button = 'Next &gt;';
		$this->wizard->cancel_button = 'Cancel';
	}// /WizardStep3()


	function head() {
		$html = <<<HTMLEnd
<script language="JavaScript" type="text/javascript">
<!--

	function body_onload() {
		document.getElementById('num_groups').focus();
	}// /body_onload()

//-->
</script>
HTMLEnd;

		echo($html);
	}// /->head()
	
	
	function form() {
		$CIS = $this->wizard->get_var('CIS');
		$config = $this->wizard->get_var('config');
		
		require_once("../../../library/functions/lib_form_functions.php");
		
		$arr_module_id = (array) $this->wizard->get_field('module_id');	// always an array, even if there's only 1

		$module_count = count($arr_module_id);
		$module_plural = ($module_count==1) ? 'module' : 'modules';
		$total_students = $CIS->get_module_students_count($arr_module_id);

		$students_plural = ($total_students==1) ? 'student' : 'students';

		if ($total_students==0) {
			echo("<div class=\"warning_box\"><p><strong>Warning!</strong></p><p>There are no students associated with the $module_plural you have selected.</p><p>You can continue to create your groups if you wish but there are no students available, so your groups cannot be populated at this time.</p><p>To choose a different module, click <em>back</em> to view the list of modules available.</p></div>");
		} else {
			echo("<p>You have chosen <strong>$module_count $module_plural</strong>, containing <strong>$total_students $students_plural</strong> in total.</p>");
		}
		?>
		<p>Now you can set how the new groups will be created. To save time, the system can automatically create sequentially named groups for you. If you do not want to use sequential names, or if you just want to create all your groups yourself, select <em>0</em> in the <em>Number of groups to create</em> box below.</p>		

		<h2>Auto-create groups</h2>
		<div class="form_section">
			<p>Select how many groups you want to create.</p>
			
			<table class="form" cellpadding="1" cellspacing="1">
			<tr>
				<th><label for="num_groups">Number of groups to create</label></th>
				<td>
					<select name="num_groups" id="num_groups">
					<?php render_options_range(0,100,1,(int) $this->wizard->get_field('num_groups')); ?>
					</select>
				</td>
			</tr>
			</table>
			
			<br />
			<p>If you are auto-creating groups, decide how the groups will be named, e.g.  <em>Group X</em> or <em>Team X</em>.</p>
			<table class="form" cellpadding="1" cellspacing="1">
			<tr>
				<th><label for="group_name_stub">Group names begin with</label></th>
				<td><input type="text" name="group_name_stub" id="group_name_stub" maxlength="40" size="25" value="<?php echo($this->wizard->get_field('group_name_stub')); ?>" /></td>
			</tr>
			</table>

			<br />
			<p>Select the style of numbering to use for your new groups.</p>
			<table class="form" cellpadding="1" cellspacing="1">
			<tr>
				<th><label for="group_numbering">Numbering Style</label></th>
				<td>
					<select name="group_numbering" id="group_numbering">
						<?php
						$options = array	('alphabetic'	=> 'Alphabetic (Group A, Group B, ..)' ,
											 'numeric'		=> 'Numeric (Group 1, Group 2, ..)' ,
											 'hashed'		=> 'Hashed-Numeric (Group #1, Group #2, ..)' ,
											);
						render_options($options, $this->wizard->get_field('group_numbering'));
						?>
					</select>
				</td>
			</tr>
			</table>
		</div>		
		<?php
	}// /->form()
	
	
	function process_form() {
		$errors = null;
		
		$this->wizard->set_field('num_groups', fetch_POST('num_groups',null));
		if (is_null($this->wizard->get_field('num_groups'))) { $errors[] = 'You must choose how many groups to create'; }

		if ($this->wizard->get_field('num_groups')>0) {
			$this->wizard->set_field('group_name_stub', trim( fetch_POST('group_name_stub') ) );
			if (is_empty($this->wizard->get_field('group_name_stub'))) { $errors[] = 'You must provide a name for your new groups'; }

			$this->wizard->set_field('group_numbering', fetch_POST('group_numbering'));
			if (is_empty($this->wizard->get_field('group_numbering'))) { $errors[] = 'You must choose how to number your groups'; }
		}
		
		return $errors;
	}// /->process_form()
	
}// /class: WizardStep3


?>
