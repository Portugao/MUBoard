<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */

/**
 * The muboardGetNumberOfIssuesOfCategory return the number of issues in a category.
 *
 * @param  int       $id      cat id
 *
 * @return id the number of issues
 */
function smarty_modifier_muboardGetNumberOfIssuesOfCategory($id)
{

	$out = MUBoard_Util_View::getIssuesOfCategory($id);

	return $out;
}
