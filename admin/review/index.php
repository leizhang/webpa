<?php
/**
 * 
 * UI landing page for the review of information in the database
 * 
 *
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 0.0.0.1
 * @since 21 May 2007
 * 
 */
 
 //get the include file required
require_once("../../include/inc_global.php");


if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}

$filecontenttype = array(3);
$filecontenttype[1] = array('link'=>'student','screen'=>'Student', 'def'=>'View the student data currently in the system', 'icon'=>'../../../images/icons/user.png',);
$filecontenttype[2] = array('link'=>'staff','screen'=>'Staff', 'def'=>'View the staff data currently in the system', 'icon'=>'../../../images/icons/user_suit.png',);
$filecontenttype[3] = array('link'=>'module','screen'=>'Module', 'def'=>'View the module information currently in the system','icon'=>'../../../images/icons/modules.png',);
$filecontenttype[4] = array('link'=>'../search', 'screen'=>'Search', 'def'=>'Search for a student or staff user of the system','icon'=>'../../../images/icons/page_find.png',);




 //set the page information     
$UI->page_title = APP__NAME . " view data";
$UI->menu_selected = 'view data';
$UI->breadcrumbs = array ('home' => null);
$UI->help_link = '?q=node/237';
$UI->set_page_bar_button('View Student Data', '../../../images/buttons/button_student_user.png', 'student/index.php');
$UI->set_page_bar_button('View Staff Data', '../../../images/buttons/button_staff_user.png', 'staff/index.php');
$UI->set_page_bar_button('View Module Data', '../../../images/buttons/button_view_modules.png', 'module/index.php');
$UI->set_page_bar_button('Search for a user', '../../../images/buttons/button_search_user.png', '../search/index.php');
$UI->head();
$UI->body();
$UI->content_start();

?>
<div class="content_box">
<table class="option_list" style="width: 500px;">
<?php
	for($checkbox = 1; $checkbox<= count($filecontenttype)-1; $checkbox++){ 
?>
<tr>
	<td><a href="<?php echo $filecontenttype[$checkbox]['link']; ?>/index.php"><img src="../../images/icons/form.gif" width="32" height="32" alt="" /></a></td><td>
		<div class="option_list"> <div class="option_list_title"><a class="hidden" href="<?php echo $filecontenttype[$checkbox]['link']; ?>/index.php"><?php echo $filecontenttype[$checkbox]['screen']; ?></a></div>
			<p><?php echo $filecontenttype[$checkbox]['def']; ?></p>
		</div>
	</td>
</tr>
<?php
	}
?>
</table>
</div>
<?php
$UI->content_end();
?>