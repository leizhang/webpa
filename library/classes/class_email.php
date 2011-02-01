<?php
/**
 * 
 * Class : Email
 * 	
 * Doesn't check for SMTP header injections
 * (see http://securephp.damonkohler.com/index.php/Email_Injection)
 * 			
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * @since 14-09-2005
 * 
 */

class Email {
	// Public Vars


	// Private Vars
	private $_to = null;
	private $_cc = null;
	private $_bcc = null;
	
	private $_from = null;

	private $_subject = null;
	private $_body = null;

	private $_message_type = 'text';
	private $_headers = null;


  /**
	* CONSTRUCTOR for the email class
	*/
	function Email() {
		$this->_init();
	} // /->Email()

  
/*
* ================================================================================
* Public Methods
* ================================================================================
*/
/**
 * Function to set who the email will go to
 * @param string $to
 */
	function set_to($to) {
		if (!is_array($to)) { $this->_to[] = $to; }
		else {$this->_to = $to; }
	}// ->set_to()

/** 
 * function to set the cc person for an email
 * @param string $cc
 */
	function set_cc($cc) {
		if (is_null($cc)) {
			unset($this->_headers['Cc']);
		}	else {
			if (!is_array($cc)) { $this->_cc[] = $cc; }
			else {$this->_cc = $cc; }
			$this->_headers['Cc'] = implode(',',$cc);
		}
	}// /->set_cc()

/**
 * function to set the bcc for the email
 * @param string $bcc
 */
	function set_bcc($bcc) {
		if (is_null($bcc)) {
			unset($this->_headers['Bcc']);
		}	else {
			if (!is_array($bcc)) { $this->_bcc[] = $bcc; }
			else {$this->_bcc = $bcc; }
			$this->_headers['Bcc'] = implode(',',$bcc);
		}
	}// /->set_bcc()

/**
 * function to set who the email is from
 * @param string $from
 */
	function set_from($from) {
		$this->_from = $from;
		$this->_headers['From'] = $this->_from;
		$this->_headers['Reply-To'] = $this->_from;
	}// /->set_from()

/**
 * function to set the message type
 * @param string  $type
 */	
	function set_message_type($type) {
		if ($type == 'html') {
			$this->_message_type = 'html';
			$this->_headers['MIME-Version'] = '1.0';
			$this->_headers['Content-Type'] = 'text/html;charset=iso-8859-1';
		} else {
			$this->_message_type = 'text';
			unset($this->_headers['MIME-Version']);
			unset($this->_headers['Content-Type']);
		}
	}// /->set_message_type()
	
/**
 * function to set the subject from the email
 * @param string $subject
 */
	function set_subject($subject) {
		$this->_subject = $subject;
	}// /->set_subject()
	
/**
 * Function to set the message body
 * @param string $body
 */
	function set_body($body) {
		$this->_body = $body;
	}// /->set_body()
	
/**
 * Function to send the email
 */
	function send() {
		$this->_send();
	}// /->send()

	
/*
* ================================================================================
* Private Methods
* ================================================================================
*/
/**
 * function to initalise 
 */
	function _init() {
		$this->_to = null;
		$this->_cc = null;
		$this->_bcc = null;

		$this->_from = null;

		$this->_subject = null;
		$this->_body = null;

		$this->_message_type = 'text';
		$this->_headers['X-Mailer'] = 'PHP/'. phpversion();
	}// /->_init()
	
/**
 * Function to send the email
 * @return array
 */
	function _send() {
		$to = ($this->_to) ? implode(',',$this->_to) : null;
		$cc = (is_array($this->_cc)) ? implode(',',$this->_cc) : null ;
		$bcc = (is_array($this->_bcc)) ? implode(',',$this->_bcc) : null ;

		$subject = $this->_subject;
		$message = str_replace("\n.", "\n..", $this->_body);	// single '.' as first character fix for Windows SMTP servers

		$headers = '';
		foreach($this->_headers as $header_name => $header_content) {
			$headers .= "{$header_name}: {$header_content}\r\n";
		}

		return mail($to, $subject, $message, $headers);
	}// /->_send()


} // /class: Email

?>
