<?php
/**
 * 
 * WIZARD : Email students taking an assessment
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
require_once("../../../include/inc_global.php");
require_once(DOC__ROOT .'/library/functions/lib_form_functions.php');
require_once(DOC__ROOT . '/include/classes/class_assessment.php');
require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
require_once(DOC__ROOT . '/include/classes/class_result_handler.php');
require_once(DOC__ROOT . '/library/classes/class_email.php');
require_once(DOC__ROOT . '/library/classes/class_wizard.php');


if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}


// --------------------------------------------------------------------------------

$assessment_id = fetch_GET('a');

$tab = fetch_GET('tab');
$year = fetch_GET('y', date('Y'));

$list_url = "../index.php?tab={$tab}&y={$year}";


// --------------------------------------------------------------------------------

$assessment =& new Assessment($DB);
if ($assessment->load($assessment_id)) {
	$assessment_qs = "a={$assessment->id}&tab={$tab}&y={$year}";

} else {
	$assessment = null;
}


// --------------------------------------------------------------------------------
// Initialise wizard

if ($assessment) {
	$wizard = new Wizard('email your students wizard');
	$wizard->set_wizard_url("index.php?a={$assessment->id}&tab={$tab}&y={$year}");

	$wizard->set_field('list_url',$list_url);
	$wizard->cancel_url = $wizard->get_field('list_url');

	$wizard->add_step(1,'class_wizardstep_1.php');
	$wizard->add_step(2,'class_wizardstep_2.php');
	$wizard->add_step(3,'class_wizardstep_3.php');
	$wizard->add_step(4,'class_wizardstep_4.php');

	$wizard->show_steps(3);	// Hide the last step from the user

	$wizard->set_var('db',$DB);
	$wizard->set_var('config',$_config);
	$wizard->set_var('user',$_user);
	$wizard->set_var('cis',$CIS);
	$wizard->set_var('assessment',$assessment);
	
	$wizard->prepare();

	$wiz_step = $wizard->get_step();
}

// --------------------------------------------------------------------------------
// Start the wizard



// --------------------------------------------------------------------------------
// Begin Page

$UI->page_title = APP__NAME . ' email your students';
$UI->menu_selected = 'my assessments';
$UI->help_link = '?q=node/235';
$UI->breadcrumbs = array	(
	'home' 				=> '../../' ,
	'my assessments'	=> '../' ,
	'email students'	=> null ,
);

$UI->set_page_bar_button('List Assessments', '../../../../images/buttons/button_assessment_list.gif', '../');
$UI->set_page_bar_button('Create Assessments', '../../../../images/buttons/button_assessment_create.gif', '../create/');


$UI->head();
if ($assessment) {
	$wizard->head();
	$UI->body('onload="body_onload()"');
} else {
	$UI->body();
}
$UI->content_start();
?>

<p>This wizard takes you through the process of sending an email to the students taking this assessment.</p>

<?php
if ($assessment) {
	$wizard->title();
	$wizard->draw_errors();
}
?>

<div class="content_box">

<?php
if ($assessment) {
	$wizard->draw_wizard();
} else {
	echo("<p>The given assessment failed to load so this wizard cannot be started.</p>");
}
?>

</div>



<?php
$UI->content_end();
?>