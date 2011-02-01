<?php
/**
 * 
 * Groups Index - List the user's collections
 *
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
require_once("../../include/inc_global.php");
require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
require_once(DOC__ROOT . '/library/classes/class_simple_object_iterator.php');

require_once('../../lang/en/generic.php');
require_once("../../lang/en/tutors/tutors.php");

if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}

// --------------------------------------------------------------------------------

global $group_handler;
$group_handler = new GroupHandler();
$collections = $group_handler->get_user_collections($_user->id, APP__ID);


// --------------------------------------------------------------------------------
// Begin Page


$UI->page_title = APP__NAME. ' ' . MY__GROUPS;
$UI->menu_selected = MY__GROUPS;
$UI->help_link = '?q=node/253';
$UI->breadcrumbs = array	(
	'home' 				=> '../../' ,
	'my groups'		=> '../' ,
);
													
$UI->set_page_bar_button(BTN__LIST_GROUPS, '../../../images/buttons/button_group_list.gif', '');
$UI->set_page_bar_button(BTN__CREATE_GROUPS, '../../../images/buttons/button_group_create.gif', 'create/');
$UI->set_page_bar_button(BTN__CLONE_GROUPS, '../../../images/buttons/button_group_clone.gif', 'clone/');

$UI->head();
$UI->content_start();
?>

<p><?php echo GROUPS__WELCOME; ?></p>

<div class="content_box">

<h2><?php echo GROUPS__TITLE; ?></h2>
<div class="form_section">
	<?php
	if (!$collections) {
		echo('<p>'.NO__GROUPS__DESC.'</p>');
	} else {
		$collection_iterator =& new SimpleObjectIterator($collections, 'GroupCollection', "&\$GLOBALS['group_handler']->_DAO");
		?>
		<p><?php echo GROUPS__INSTRUCT__1; ?><img src="../../images/buttons/edit.gif" width="16" height="16" alt="<?php echo EDIT_QUESTION; ?>" title="edit" /> <?php echo GROUPS__INSTRUCT__2; ?></p>
		<div class="info_box">
			<p><?php echo PLEASE__NOTE; ?></p>
			<p><?php echo GROUPS__NOTE; ?></p>
		</div>
		<div class="obj_list">
			<?php
			for($collection_iterator->reset(); $collection_iterator->is_valid(); $collection_iterator->next() ) {
				$collection = $collection_iterator->current();

				$group_count = count($collection->get_groups_array());
				$modules = (is_array($collection->get_modules())) ? implode(', ',$collection->get_modules()) : 'none' ;

				$edit_url = "edit/edit_collection.php?c={$collection->id}";
				?>
				<div class="obj">
					<table class="obj" cellpadding="2" cellspacing="2">
					<tr>
						<td class="obj_icon" width="24"><a class="text" href="<?php echo($edit_url); ?>"><img src="../../images/icons/groups.gif" alt="<?php GROUPS; ?>" height="24" width="24" /></a></td>
						<td class="obj_info">
							<div class="obj_name"><a class="text" href="<?php echo($edit_url); ?>"><?php echo($collection->name); ?></a></div>
							<div class="obj_info_text"><?php echo ASSOCIATED__MODULES; ?> : <?php echo($modules); ?></div>
							<div class="obj_info_text"><?php echo NO__GROUPS; ?> : <?php echo($group_count); ?></div>
						</td>
						<td class="buttons">
							<a href="<?php echo($edit_url); ?>"><img src="../../images/buttons/edit.gif" width="16" height="16" alt="<?php echo EDIT__GROUP; ?>" title="edit" /></a>
						</td>
					</tr>
					</table>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
</div>

</div>

<?php
$UI->content_end();
?>