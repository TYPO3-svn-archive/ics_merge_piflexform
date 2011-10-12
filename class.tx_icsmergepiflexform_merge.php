<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 In Cité Solution <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */
 
 /**
 * Lib to merge two plugins flexforms
 *
 * @author	Emilie Prud'homme <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	ics_merge_piflexform
 */
class tx_icsmergepiflexform_merge {
	
	static function merge ($file1, $file2) {
		$base1 = $file1;
		
		// remove FILE:
		if (substr($file1, 0, 5) == 'FILE:')
			$file1 = substr($file1, 5);
		if (substr($file2, 0, 5) == 'FILE:')
			$file2 = substr($file2, 5);
		
		// extract xml to file if EXT:
		if (substr($file1, 0, 4) == 'EXT:') 
			$file1 = file_get_contents(t3lib_div::getFileAbsFileName($file1));
			
		if (substr($file2, 0, 4) == 'EXT:') 
			$file2 = file_get_contents(t3lib_div::getFileAbsFileName($file2));
			
		if (!$file1 || !$file2)
			return $base1;
		
		// convert to array
		$flex1 = t3lib_div::xml2array($file1);
		$flex2 = t3lib_div::xml2array($file2);
					
		if (!is_array($flex1) ||!is_array($flex2))
			return $base1;
			
		$final = tx_icsmergepiflexform_merge::array_merge_recursive($flex1, $flex2);
		$flex = t3lib_div::array2xml($final);
		
		return $flex;
	}
	
	/**
	 * based of cv_merge_flexform extension
	*/

	static function array_merge_recursive($array, $array2) {
		$tab_out=$array;

		if (!is_array($array2)) return $array2;
		if (!is_array($array)) return $array;

		foreach ($array2 as $key => $value) {

			// The process repeats recursively:
			if (is_array($value)) {
				if (!isset($array[$key])) {
					$array[$key] = array();
				}
				$tab_out[$key] = tx_icsmergepiflexform_merge::array_merge_recursive($array[$key], $value);
			// Else, it is a value
			} else {
				$tab_out[$key] = $value;
			}
		}
		return $tab_out;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_merge_piflexform/class.tx_icsmergepiflexform_merge.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_merge_piflexform/class.tx_icsmergepiflexform_merge.php']);
}

?>