<?php
/**
 * 
 * Email new assessment
 * 
 * Email the collection that the students are associated with when the 
 * new assessment is created.
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 0.0.0.3
 * @since 5 Nov 2007
 * 
 */
 
 require_once("../../../include/inc_global.php");
 require_once('../../../library/classes/class_email.php');
 require_once('../../../library/classes/class_group_handler.php');
 require_once('../../../library/classes/class_engcis.php');
 
 /***************************************************************
  * Function mail_assessment_notification
  * 
  * Sends an email to the complete collection when called
  * This function was primarily designed to be called when a
  * new assessment is created and schedualed.
  * 
  * @param mixed $collectionId The ID value for the collection that is to be emailed
  * @param string $subjectLn The subject line that is to be sent for the email
  * @param string $body_content Body content of the email to be sent
  * @return mixed Either a true if successful or an error message is failed
  */
 
  
 function mail_assessment_notification ($collectionId, $subjectLn,$body_content, $_user_id){
	 //get the collection to whom the email is to be sent 
	 $group_handler =& new GroupHandler();
	 $collection = $group_handler->clone_collection($collectionId);
	 
	 //get an array of the collection members to send the email to
	 $member_arr = array();
	 $member_arr = $collection->get_members();
	 
	 $users_to_email = array_keys($member_arr);
	 
	 // create bcc list of recipients
	 $bcc_list = null;
	 
	 $CISa = new EngCIS();
			
	 if (is_array($users_to_email)) {	
	 	$users_arr = $CISa->get_user($users_to_email);
		$bcc_list = array_extract_column($users_arr, 'email');
		
		//get the current userID
		$this_user = $CISa->get_user($_user_id);		
		$bcc_list[] = $this_user['email'];
		
	 } else {
		$errors[] = 'Unable to build email list - no students to email.';
		return $errors;
	 }
			
	 if (is_array($bcc_list)) {		
		// Send the email
		$email = new Email();
		$email->set_bcc($bcc_list);
		$email->set_from($this_user['email']);
		$email->set_subject($subjectLn);
		$email->set_body($body_content);
		$email->send();
	 } else {
	 	$errors[] = 'No list of students to email.';
	 	return $errors;
	 }
	
	//sucessful
	return true;
 }
	
?>