<?php
/**
 * 
 * Class : WizardStep4  (Create new form wizard)
 *
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
class WizardStep4 {

	// Public
	public $wizard = null;
	public $step = 4;

	
	/*
	* CONSTRUCTOR
	*/
	function WizardStep4(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = null;
		$this->wizard->next_button = null;
		$this->wizard->cancel_button = null;

		ob_start();
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
		$config =& $this->wizard->get_var('config');
		$DB =& $this->wizard->get_var('db');
		$user =& $this->wizard->get_var('user');
		$CIS =& $this->wizard->get_var('cis');
	
		$send_email_to = $this->wizard->get_field('send_to');
		
		$assessment =& $this->wizard->get_var('assessment');

		$group_handler =& new GroupHandler();
		$collection =& $group_handler->get_collection($assessment->get_collection_id());

		$errors = null;
		
		
		// get lists of users to email
		$users_to_email = null;

		switch ($send_email_to) {
			case 'all':
						$member_arr = $collection->get_members();
						$users_to_email = array_keys($member_arr);
						break;
			// --------------------
			case 'groups':
						$email_groups = explode('|',$this->wizard->get_field('email_groups'));
						$member_arr = $collection->get_member_rows();

						foreach($member_arr as $i => $member) {
							if (in_array($member['group_id'],$email_groups)) {	$users_to_email[] = $member['user_id']; }
						}
						break;
			// --------------------
			case 'have':
						$result_handler =& new ResultHandler($this->wizard->get_var('db'));
						$result_handler->set_assessment($assessment);
						$users_to_email	= (array) $result_handler->get_responded_users();
						break;
			// --------------------
			case 'havenot':
						$result_handler =& new ResultHandler($this->wizard->get_var('db'));
						$result_handler->set_assessment($assessment);
						$responded_users = $result_handler->get_responded_users();
						
						$member_arr = (array) $collection->get_members();
						$all_users = array_keys($member_arr);
						
						$users_to_email = array_diff((array) $all_users, (array) $responded_users);
						break;
		}


		// create bcc list of recipients
		$bcc_list = null;
		
		if (is_array($users_to_email)) {
			$users_arr = $CIS->get_user($users_to_email);
			$bcc_list = array_extract_column($users_arr, 'email');
			$bcc_list[] = $user->email;
		} else {
			$errors[] = 'Unable to build email list - no students to email.';
		}
	
		if (is_array($bcc_list)) {		
			// Send the email
			$email = new Email();
			$email->set_bcc($bcc_list);
			$email->set_from($user->email);
			$email->set_subject($this->wizard->get_field('email_subject'));
			$email->set_body($this->wizard->get_field('email_text'));
			$email->send();
		} else {
			$errors[] = 'No list of students to email.';
		}

		// If errors, show them
		if (is_array($errors)) {
			$this->wizard->back_button = '&lt; Back';
			$this->wizard->cancel_button = 'Cancel';
			echo('<p><strong>Unable to send email.</strong></p>');
			echo('<p>To try correcting the problem, click <em>back</em> and amend the details entered.</p>');
		} else {
			?>
			<p><strong>Your email has been sent.</strong></p>
			<p style="margin-top: 20px;">You can now return to <a href="<?php echo($this->wizard->get_field('list_url')); ?>">your assessment list</a>, or to the <a href="/">Web-PA home page</a>.</p>
			<?php
		}
	}// /->form()
	
	
	function process_form() {
		$this->wizard->_fields = array();	// kill the wizard's stored fields
		return null;
	}// /->process_form()
	
	
}// /class: WizardStep4


?>
