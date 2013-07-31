<?php
/**
 * MUBoard.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUBoard
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */

/**
 * The muboardGetImportantOfPosting return the an icon for important issues.
 *
 * @param  int       $id      posting id
 *
 * @return id the number of postings
 */
function smarty_modifier_muboardGetImportantOfPosting($id)
{
	$out = MUBoard_Util_View::getImportantOfPosting($id);

	return $out;
}
