<?php
/**
 * 
 *  Datetime handling functions
 * 		
 * @copyright 2007 Loughborough University
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version 1.0.0.0
 * 
 */

/*
 * Get the difference between two datetimes as hours, minutes and seconds  (hh:mm:ss)
 *
 * If the time difference is less than 1 hour then only minutes and secconds will be shown
 * If the time difference is negative (start date is after end date) then the result will be prefixed with '-'.
 *
 * @param  datetime  $start_date  The starting date
 * @param  datetime  $end_date  The end date 
 *
 * @return  string  String representation of the time difference
 */
function time_diff($start_date, $end_date) {

	if ($end_date>$start_date) {	// If the start after the end, the result will be a minus
		$prefix = '';
		$remainder = $end_date - $start_date;
	} else {
		$prefix = '-';
		$remainder = $start_date - $end_date;
	}

	// Calculate hours difference
	$hours = floor($remainder / 3600);
	$remainder = $remainder - ($hours * 3600);

	// Calculate minutes difference
	$minutes = floor($remainder / 60);
	$remainder = $remainder - ($minutes * 60);

	// Calculate seconds difference
	$seconds = $remainder;
    
	// Put leading zeros on minutes and seconds (if required)
	if ($minutes<=9) { $minutes = '0' . $minutes; }
	if ($seconds<=9) { $seconds = '0' . $seconds; }
  
	// Return difference
	if ($hours>0) {
		return "$hours:$minutes:$seconds";
	} else {
		return "$minutes:$seconds";
	}
}// /time_diff()



?>