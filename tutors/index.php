<?php
/**
 * 
 * INDEX - Tutor index
 *			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
 
require_once("../include/inc_global.php");
require_once(DOC__ROOT . "lang/en/generic.php");
require_once(DOC__ROOT . "lang/en/tutors/tutors.php");

if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}


// --------------------------------------------------------------------------------
// Begin Page

$UI->page_title = APP__NAME;
$UI->menu_selected = 'home';
$UI->help_link = '?q=node/233';
$UI->breadcrumbs = array	(
	'home' 			=> null ,
);
$UI->head();
$UI->body();

$UI->content_start();
?>


<?php
echo '<p>' . WELCOME . '</p>';
echo '<p>' . SECTIONS__INTRO . '</p>';
?>


<table class="option_list" style="width: 500px;">
<tr>
	<td><a href="forms/"><img src="../images/icons/form.gif" width="32" height="32" alt="<?php echo MY__FORMS; ?>" /></a></td>
	<td>
		<div class="option_list">
			<div class="option_list_title"><a class="hidden" href="forms/"><?php echo MY__FORMS; ?></a></div>
			<p><?php echo OPT__FORMS__DESC; ?></p>
		</div>
	</td>
</tr>
<tr>
	<td><a href="groups/"><img src="../images/icons/groups.gif" width="32" height="32" alt="<?php echo MY__GROUPS; ?>" /></a></td>
	<td>
		<div class="option_list">
			<div class="option_list_title"><a class="hidden" href="groups/"><?php echo MY__GROUPS; ?></a></div>
			<p><?php echo OPT__GROUPS__DESC; ?></p>
		</div>
	</td>
</tr>
<tr>
	<td><a href="assessments/"><img src="../images/icons/assessments.gif" width="32" height="32" alt="<?php echo MY__ASSESSMENTS; ?>" /></a></td>
	<td>
		<div class="option_list">
			<div class="option_list_title"><a class="hidden" href="assessments/"><?php echo MY__ASSESSMENTS; ?></a></div>
			<p><?php echo OPT__ASSESSMENTS__DESC ?></p>
		</div>
	</td>
</tr>
</table>

<h2><?php echo GETTING__STARTED__TITLE ; ?></h2>
<p><?php echo GETTING__STARTED__DESC ; ?></p>


<?php
$UI->content_end();
?>