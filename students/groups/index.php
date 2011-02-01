<?php
/**
 * 
 * List Groups for student
 * 			
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
require_once("../../include/inc_global.php");
require_once("../../library/classes/class_group_handler.php");
require_once("../../library/classes/class_simple_object_iterator.php");

if (!check_user($_user, 'student')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}

// --------------------------------------------------------------------------------

global $group_handler;
$group_handler = new GroupHandler();
$collections = $group_handler->get_member_collections($_user->id, APP__ID, 'user');


// --------------------------------------------------------------------------------
// Begin Page

$UI->page_title = APP__NAME . ' my groups';
$UI->menu_selected = 'my groups';
$UI->help_link = '?q=node/329';
$UI->breadcrumbs = array	('home' 				=> '/' ,
							 'my groups'		=> null );
													


$UI->head();
?>
<style type="text/css">
<!--

div.collection {
	margin-bottom: 10px;
	padding: 2px 2px 2px 20px;
	background: url(/images/icons/spot_black.gif) no-repeat top left;
}

div.collection_name { font-weight: bold; }

div.group { margin-left: 30px; padding: 2px; }

div.group_name { }

div.members { margin-left: 10px; font-size: 0.8em; }
div.member_name {  }
div.own_member_name { font-style: italic; }

-->
</style>

<?php
$UI->content_start();
?>

<p>Here you can view which groups you are a member of, and who the other members are.</p>

<div class="content_box">

<h2>Your Groups</h2>
<div class="form_section">
	<?php
	if (!$collections) {
		?>
		<p>You are not listed as a member of any group.</p>
		<p>Only groups that have been scheduled an assessment will appear in this list.</p>
		<?php
	} else {
		$collection_iterator =& new SimpleObjectIterator($collections, 'GroupCollection', "&\$GLOBALS['group_handler']->_DAO");
		?>
		<p>You belong to the following groups.</p>
		<?php
		for($collection_iterator->reset(); $collection_iterator->is_valid(); $collection_iterator->next() ) {
			$collection =& $collection_iterator->current();
			$groups = $collection->get_member_groups($_user->id);
			

			echo('<div class="collection">');
			echo("	<div class=\"collection_name\">{$collection->name}</div>");

			foreach($groups as $i => $group) {
				$member_ids = array_keys( $group->get_members() );
				$members = $CIS->get_user($member_ids);
				?>
				<div class="group">
					<table cellpadding="2" cellspacing="2">
					<tr>
						<td valign="top"><div class="group_name"><?php echo($group->name); ?></div></td>
						<td valign="top">
							<div class="members">
							<?php
								foreach($members as $i => $member) {
									if ($_user->id==$member['user_id']) {
										echo("			<div class=\"own_member_name\">{$member['forename']} {$member['surname']}</div>");
									} else {
										echo("			<div class=\"member_name\">{$member['forename']} {$member['surname']}</div>");
									}
								}
							?>
							</div>
						</td>
					</tr>
					</table>
				</div>
				<?php		
			}			
			echo('</div>');
		}
	}
	?>
</div>
</div>


<?php
$UI->content_end();
?>