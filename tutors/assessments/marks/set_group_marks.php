<?php

/**
 *
 *  Set marks for the assessment's groups
 *
 *
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 *
 */
require_once("../../../include/inc_global.php");
require_once(DOC__ROOT . '/include/classes/class_assessment.php');
require_once(DOC__ROOT . '/library/classes/class_group_handler.php');
require_once(DOC__ROOT . '/library/classes/class_xml_parser.php');
require_once(DOC__ROOT . '/library/functions/lib_form_functions.php');

if (!check_user($_user, 'staff')){
	header('Location:'. APP__WWW .'/logout.php?msg=denied');
	exit;
}



// --------------------------------------------------------------------------------
// Process GET/POST

$assessment_id = fetch_GET('a');

$tab = fetch_GET('tab');
$year = fetch_GET('y', date('Y'));

$command = fetch_POST('command');


$list_url = "../index.php?tab={$tab}&y={$year}";

$prev_url = $list_url;

// --------------------------------------------------------------------------------



$group_marks = array();

$assessment =& new Assessment($DB);
if ($assessment->load($assessment_id)) {
	$assessment_qs = "a={$assessment->id}&tab={$tab}&y={$year}";

	$group_handler =& new GroupHandler();
	$collection =& $group_handler->get_collection($assessment->get_collection_id());

	$group_marks_xml = $DB->fetch_value("SELECT group_mark_xml
										FROM assessment_group_marks
										WHERE assessment_id='{$assessment->id}';");

	$xml_parser = null;

	if ($group_marks_xml) {
		$xml_parser =& new XMLParser();
		$xml_array = $xml_parser->parse($group_marks_xml);

		if (is_array($xml_array['groups']['group'])) {
			foreach($xml_array['groups']['group'] as $i => $group) {
				if (is_array($group)) {
					if (array_key_exists('_attributes', $group)) {
						$group_marks[$group['_attributes']['id']] = $group['_attributes']['mark'];
					} else {
						$group_marks[$group['id']] = $group['mark'];
					}
				}
			}
		}
	} else {
	}
} else {
	$assessment = null;
}


// --------------------------------------------------------------------------------
// Process Form

$errors = null;

if ( ($command) && ($assessment) ) {
	switch ($command) {
		case 'save':
					$xml_array = null;
					$bad_group_ids = null;

					foreach($_POST as $k => $v) {
						if (strpos($k, 'group_mark_')===0) {
							$group_id = str_replace('group_mark_','',$k);
							$mark = trim( str_replace('%','',$v) );
							if (!is_numeric($mark)) {
								if (empty($mark)) {
									$errors[] = "You must enter a score for each group.";
								} else {
									$errors[] = "You must enter a score for each group. $v is not a valid score.";
								}

								$bad_group_ids[] = $group_id;	// used to highlight the row later
							}

							// Add mark to XML we will save
							$xml_array['groups']['group'][] = array ('_attributes'	=> array	('id'	=> $group_id ,
																								 'mark'	=> $mark ,));

							// Add mark to the array we're gonna check
							$group_marks[$group_id] = $mark;
						}
					}

					// If there were no errors, save the changes
					if (!$errors) {

						if (!$xml_parser) { $xml_parser =& new XMLParser(); }

						$xml = $xml_parser->generate_xml($xml_array);

						$fields = array ('assessment_id'	=> $assessment->id ,
										 'group_mark_xml'	=> $xml ,);

						$DB->do_insert('REPLACE INTO assessment_group_marks ({fields}) VALUES ({values})',$fields);
					}
					break;
		// --------------------
	}// /switch
}


// --------------------------------------------------------------------------------
// Begin Page

$page_title = 'set group marks';


$UI->page_title = APP__NAME . ' ' . $page_title;
$UI->menu_selected = 'my assessments';
$UI->help_link = '?q=node/235';
$UI->breadcrumbs = array	('home' 			=> '../../' ,
							 'my assessments'	=> '../' ,
							 'set group marks'	=> null ,);

$UI->set_page_bar_button('List Assessments', '../../../../images/buttons/button_assessment_list.gif', '../');
$UI->set_page_bar_button('Create Assessments', '../../../../images/buttons/button_assessment_create.gif', '../create/');

$UI->head();
?>
<style type="text/css">
<!--

table.grid th { text-align: center; }
table.grid td { text-align: left; }

span.id_number { color: #666; }

<?php
	if (is_array($bad_group_ids)) {
		foreach($bad_group_ids as $i => $group_id) {
			echo("tr#group_$group_id td { background-color: #fcc; }");
		}
	}
?>

-->
</style>
<script language="JavaScript" type="text/javascript">
<!--

	function do_command(com) {
		switch (com) {
			default :
						document.groupmark_form.command.value = com;
						document.groupmark_form.submit();
		}
	}// /do_command()

//-->
</script>
<?php
$UI->content_start();

$UI->draw_boxed_list($errors, 'error_box', 'The following errors were found:', 'No changes have been saved. Please check the details in the form, and try again.');

?>

<p>On this page you can enter the overall marks each group has achieved. These marks are used in the Web-PA scoring algorithm, and will form the basis of the final student marks.</p>

<div class="content_box">

<?php
if (!$assessment) {
	?>
	<div class="nav_button_bar">
		<a href="<?php echo($list_url) ?>"><img src="../../../images/buttons/arrow_green_left.gif" alt="back -"> back to assessments list</a>
	</div>

	<p>The assessment you selected could not be loaded for some reason - please go back and try again.</p>
	<?php
} else {
	?>

	<form action="set_group_marks.php?<?php echo($assessment_qs); ?>" method="post" name="groupmark_form">
	<input type="hidden" name="command" value="none" />

	<div class="nav_button_bar">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><a href="<?php echo($list_url); ?>"><img src="../../../images/buttons/arrow_green_left.gif" alt="back -"> back to assessment list</a></td>
		</tr>
		</table>
	</div>

	<h2>Group Marks</h2>
	<div class="form_section">
		<?php
		echo("<p><label>This assessment is using collection: </label><em>{$collection->name}</em></p>");

		$groups = $collection->get_groups_iterator();

		if ($groups->size()==0) {
			?>
			<p>This collection does not contain any groups</p>
			<?php
		} else {
			?>
			<table class="grid" cellpadding="5" cellspacing="1">
			<tr>
				<th>Group</th>
				<th>Members</th>
				<th>Group Mark</th>
			</tr>
			<?php
			for($groups->reset(); $groups->is_valid(); $groups->next()) {
				$group =& $groups->current();
				$group_members = $group->get_members();
				$members = ($group_members) ? $CIS->get_user(array_keys($group_members)) : null;

				$group_mark = (array_key_exists($group->id, $group_marks)) ? $group_marks["$group->id"] : '';

				echo("<tr id=\"group_{$group->id}\">");
				echo("<td><label for=\"group_mark_{$group->id}\">{$group->name}</label></td>");
				echo('<td style="text-align: left">');
				if (!$members) {
					echo('-');
				} else {
					foreach($members as $i => $member) {
						echo("{$member['surname']}, {$member['forename']} &nbsp; <span class=\"id_number\">({$member['institutional_reference']})</span><br />");
					}
				}
				echo('</td>');
				echo("<td><input type=\"text\" name=\"group_mark_{$group->id}\" id=\"group_mark_{$group->id}\" size=\"4\" maxlength=\"5\" value=\"$group_mark\" /> %</td>");
				echo('</tr>');
			}
			echo('</table>');
		}
		?>
	</div>

	<div style="text-align: center">
			<input type="button" name="savebutton1" id="savebutton1" value="save changes" onclick="do_command('save');" />
		</div>

	</form>
<?php
}
?>
</div>


<?php
$UI->content_end();
?>