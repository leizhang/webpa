<?php
/**
 * 
 * Short Description of the file
 * 
 * Long Description of the file (if any)...
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 0.0.0.1
 * @since 5 Aug 2008
 * 
 */
 require_once("../../include/inc_global.php");
 
//write to screen the page information
//set the page information
$UI->page_title = APP__NAME ." Upload templates";
$UI->menu_selected = 'upload data';
$UI->breadcrumbs = array ('home' => '../../',
						  'Upload'=>'../');
$UI->help_link = '?q=node/237';

$UI->head();
$UI->body();
$UI->content_start();
?>
<div class="content_box">
	<p>The following are the template files for the uploading of information to the WebPA database.</p>
	<p>To download the files right mouse button click on the link and 'Save link as...'</p>
	<div class="obj_list">
		<div class="obj">
		<tableclass="obj" cellpadding="2" cellspacing="2">
			<tr><td class="obj_info"><div class="obj_name"><a href="user.csv">Staff or student data</a></div><p>The information for the user is vital. The first five columns of information are the most important aspects of information. If not all of the columns are filled then please delete the column titles before uploading the file.</p></td></tr>
		</table>
		</div>
		<div class="obj">
		<tableclass="obj" cellpadding="2" cellspacing="2">
			<tr><td class="obj_info"><div class="obj_name"><a href="modules.csv">Module data</a></div><p>All elements of information for the module data is required. The module information can not be loaded unless fully complete.</p></td></tr>
		</table>
		</div>
	</div>
</div>
<?php
$UI->content_end();
?>