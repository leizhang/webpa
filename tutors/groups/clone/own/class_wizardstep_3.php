<?php
/**
 * 
 * Class : WizardStep3  (Create new groups wizard)
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
	
		$this->wizard->back_button = null;
		$this->wizard->next_button = null;
		$this->wizard->cancel_button = null;
	}// /WizardStep3()


	function head() {
		$html = <<<HTMLEnd
<script language="JavaScript" type="text/javascript">
<!--

	function body_onload() {
	}// /body_onload()

//-->
</script>
HTMLEnd;

		echo($html);
	}// /->head()
	
	
	function form() {
		$user = $this->wizard->get_var('user');
		$config = $this->wizard->get_var('config');
		$group_handler = $this->wizard->get_var('group_handler');
		
		// Clone the collection
		$collection = $group_handler->clone_collection($this->wizard->get_field('collection_id'));
		
		$errors = null;
		
		if (!$collection) {
			$errors[] = 'There was an error when trying to clone these groups - please use the contact system to report the error!';
		} else {
			$collection->name = $this->wizard->get_field('collection_name');
			$collection->set_owner_info($user->id, $config['app_id'], 'user');
			$collection->save();
		}

		// If errors, show them
		if (is_array($errors)) {
			$this->wizard->back_button = '&lt; Back';
			$this->wizard->cancel_button = 'Cancel';
			echo('<p><strong>Unable to create your new cloned groups.</strong></p>');
			echo('<p>To correct the problem, try clicking <em>back</em> and amend the details entered.</p>');
		} else {// Else.. create the groups!
			?>
			<p><strong>Your new cloned groups have been created.</strong></p>
			<p style="margin-top: 20px;">To re-allocate students to your new groups, you can use the <a href="../../edit/edit_collection.php?c=<?php echo($collection->id); ?>">group editor</a>.</p>
			<p style="margin-top: 20px;">Alternatively, you can return to <a href="../../">my groups</a>, or to the <a href="../../">Web-PA home page</a>.</p>
			<?php	
		}
	}// /->form()
	
	function process_form() {
		$this->wizard->_fields = array();	// kill the wizard's stored fields
		return null;
	}// /->process_form()
	
}// /class: WizardStep3


?>
