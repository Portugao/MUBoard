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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Fri Jun 15 09:09:36 CEST 2012.
 */

/**
 * This is the Admin api helper class.
 */
class MUBoard_Api_Admin extends MUBoard_Api_Base_Admin
{
    /**
     * get available Admin panel links
     *
     * @return array Array of admin links
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'main'),
                             'text' => $this->__('Frontend'),
                             'title' => $this->__('Switch to user area.'),
                             'class' => 'z-icon-es-home');
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'category')),
                             'text' => $this->__('Categories'),
                             'title' => $this->__('Category list'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'forum')),
                             'text' => $this->__('Forums'),
                             'title' => $this->__('Forum list'));
        }
        /*if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'posting')),
                             'text' => $this->__('Postings'),
                             'title' => $this->__('Posting list'));
        }*/
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'config'),
                             'text' => $this->__('Configuration'),
                             'title' => $this->__('Manage settings for this application'));
        }
        return $links;
    }
}
