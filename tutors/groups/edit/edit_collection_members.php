<?php
/**
 * 
 * Edit Collection members : Edit all the students in all the groups in one go
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
require_once("../../../include/inc_global.php");
require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
require_once(DOC__ROOT . '/library/functions/lib_form_functions.php');

if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}

// --------------------------------------------------------------------------------
// Process GET/POST

$collection_id = fetch_GET('c');

$command = fetch_POST('command');

$collection_qs = "c={$collection_id}";
$collection_url = "edit_collection.php?$collection_qs";


// --------------------------------------------------------------------------------

global $group_handler;
$group_handler = new GroupHandler();
$collection = $group_handler->get_collection($collection_id);

$allow_edit = false;

if ($collection) {
	// Check if the user can edit this group
	$allow_edit = ( (!$collection->is_locked()) && ($collection->is_owner($_user->id, $_config['app_id'])) );
}


// --------------------------------------------------------------------------------
// Process Form

$errors = null;

if ($allow_edit) {
	switch ($command) {
		case 'save':
					// Delete all the members with the 'member' role
					$collection->purge_members('member');
					
					// Add the members who should be in a group
					foreach ($_POST as $k => $v) {
						if ( (strpos($k,'student_')!==false) && (strlen($v)==36) ) {
							$group =& $collection->get_group_object($v);
							$group->add_member(str_replace('student_', '', $k), 'member');
						}
					}
					$collection->save_groups();
	
					break;
		// --------------------
		case 'purge':
					$collection->purge_members('member');
					$collection->save();
					break;
		// --------------------
	}// /switch
}


// --------------------------------------------------------------------------------
// Begin Page

$collection_name = ($collection) ? $collection->name : 'Unknown Collection';
$collection_title = "Editing: $collection_name";
$page_title = ($collection) ? "Members: {$collection->name}" : 'Members';


$UI->page_title = APP__NAME . ' ' . $page_title;
$UI->menu_selected = 'my groups';
$UI->help_link = '?q=node/253';
$UI->breadcrumbs = array	(
	'home' => '../../' ,
	'my groups' => '../' ,
	"Editing: $collection_title"	=> "../edit/edit_collection.php?gs={$collection->id}" ,
	$page_title						=> null ,
);
													
$UI->set_page_bar_button('List Groups', '../../../../images/buttons/button_group_list.gif', '../');
$UI->set_page_bar_button('Create Groups', '../../../../images/buttons/button_group_create.gif', '../create/');
$UI->set_page_bar_button('Clone Groups', '../../../../images/buttons/button_group_clone.gif', '../clone/');


$UI->head();
?>
<script language="JavaScript" type="text/javascript">
<!--

	function do_command(com) {
		document.collection_members_form.command.value = com;
		document.collection_members_form.submit();
	}// /do_command()

//-->
</script>
<?php
$UI->content_start();

$UI->draw_boxed_list($errors, 'error_box', 'The following errors were found:', 'No changes have been saved. Please check the details in the form, and try again.');
?>

<p>On this page you can set the group membership for every student associated with this collection.</p>

<div class="content_box">

<div class="nav_button_bar">
	<a href="<?php echo($collection_url); ?>"><img src="../../../images/buttons/arrow_green_left.gif" alt="back -"> back to <?php echo($collection_name); ?></a>
</div>

<?php
if (!$collection) {
	?>
	<p>The collection you selected could not be loaded for some reason - please go back and try again.</p>
	<?php
} else {
	?>

	<form action="edit_collection_members.php?<?php echo($collection_qs); ?>" method="post" name="collection_members_form">
	<input type="hidden" name="command" value="none" />
	
	<h2>Available Students</h2>
	<div class="form_section">
		<p>Below are all the students from the modules associated with this collection of groups.</p>
		<p>Use the drop-down box next to each student to set which group they should belong to.</p>
		<p>When you have made all your selections, click a <em>save changes</em> button.</p>
		
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td rowspan="2" valign="top">

			<table class="grid" cellpadding="2" cellspacing="1">
			<tr>
				<th>Student</th>
				<th>Assigned Group</th>
			</tr>
			<?php
				$groups = $collection->get_groups_array();
				$options[''] = ' - - - - ';
				foreach($groups as $group) {
					$options["{$group['group_id']}"] = $group['group_name'];
				}
			
				// Get all the possible student members
				$module_students = $CIS->get_module_students($collection->get_modules(),'name');

				// Get collection members
				$collection_member_rows = $collection->get_member_rows();

				if (is_array($module_students)) {
					foreach($module_students as $i => $member) {
						$assigned_group = array_searchvalue($member['user_id'], $collection_member_rows, 'user_id', 'group_id');
						echo('<tr>');
						echo("<td>{$member['surname']}, {$member['forename']} ({$member['student_id']})</td>");
						echo("<td><select name=\"student_{$member['user_id']}\">");
						render_options(&$options, $assigned_group);
						echo('</select></td>');
						echo('</tr>');
					}
				} else {
					echo('<tr><td colspan="2">No students available</td></tr>');
				}
			?>
			</table>
	
			</td>
			<td valign="top">
				<?php if ($allow_edit) { ?>
				<div class="button_bar">
					<input type="button" name="savebutton1" id="savebutton1" value="save changes" onclick="do_command('save');" />
				</div>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td valign="bottom">
				<?php if ($allow_edit) { ?>
				<div class="button_bar">
					<input type="button" name="savebutton2" id="savebutton2" value="save changes" onclick="do_command('save');" />
				</div>
				<?php } ?>
			</td>
		</tr>
		</table>	
	</div>
	
	</form>
<?php
}
?>
</div>


<?php
$UI->content_end();
?>