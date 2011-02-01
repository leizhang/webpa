<?php
/**
 * 
 * Class : WizardStep2  (add new criterion wizard)
 *
 * 
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */
class WizardStep2 {

	// Public
	public $wizard = null;
	public $step = 2;


	/*
	* CONSTRUCTOR
	*/
	function WizardStep2(&$wizard) {
		$this->wizard =& $wizard;
	
		$this->wizard->back_button = '&lt; Back';
		$this->wizard->next_button = 'Finish';
		$this->wizard->cancel_button = 'Cancel';
	}// /WizardStep2()


	function head() {
		?>
<script language="JavaScript" type="text/javascript">
<!--

	function body_onload() {
	}// /body_onload()

//-->
</script>
<?php
	}// /->head()
	
	
	function form() {
		$range_start = $this->wizard->get_field('question_range_start');
		$range_end = $this->wizard->get_field('question_range_end');
		?>
		<p>Your new assessment criterion allows scores from <?php echo("$range_start to $range_end"); ?>. You can use the boxes below to provide a description what those scores should mean.</p>
		<p>It's good practice to describe the meaning of at least the top and bottom scores, but you are free to provide as many, or as few, descriptions as you like. Leave a description blank and it will not be displayed on the form.</p>

		<p><strong>Score descriptions</strong></p>
		<div class="form_section">
			<p><?php echo($this->wizard->get_field('question_text')); ?></p>
			<table class="form" cellpadding="2" cellspacing="2">
			<?php
				for($i=$range_start; $i<=$range_end; $i++) {
					echo('<tr>');
					echo("<th><label for=\"scorelabel{$i}\">Score $i</label></th>");
					echo("<td><input type=\"text\" name=\"scorelabel{$i}\" id=\"scorelabel{$i}\" maxlength=\"255\" size=\"50\" value=\"". $this->wizard->get_field("scorelabel{$i}") ."\" /></td>");
					if ($i==$range_start) {
						echo('<td style="font-size: 0.9em; font-style: italic;">Lowest</td>');
					} else {
						if ($i==$range_end) {
							echo('<td style="font-size: 0.9em; font-style: italic;">Highest</td>');
						} else {
							echo('<td>&nbsp;</td>');
						}
					}
					echo('</tr>');
				}
			?>				
			</table>
		</div>
		<?php
	}// /->form()

	
	function process_form() {
		$errors = null;

		$range_start = $this->wizard->get_field('question_range_start');
		$range_end = $this->wizard->get_field('question_range_end');

		for($i=$range_start; $i<=$range_end; $i++) {
			$scorelabel = trim( fetch_POST("scorelabel{$i}") );
			if (!empty($scorelabel)) { $this->wizard->set_field("scorelabel{$i}",$scorelabel); }
		}

		return $errors;
	}// /->process_form()
	
}// /class: WizardStep2


?>
