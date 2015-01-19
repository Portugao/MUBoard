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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Sep 25 14:18:39 CEST 2012.
 */

/**
 * Installer implementation class
 */
class MUBoard_Installer extends MUBoard_Base_Installer
{
    /**
     * Install the MUBoard application.
     *
     * @return boolean True on success, or false.
     */
    public function install()
    {
        parent::install();
        
        $uid = UserUtil::getVar('uid');
        MUBoard_Util_View::actualUser($uid, 1);
        
        // update successful
        return true;
        
    }
    /**
     * Upgrade the MUBoard application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldversion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldversion)
    {
        // Upgrade dependent on old version number
        switch ($oldversion) {
            case '1.0.0':
                // nothing to do
                // update the database schema
                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (Exception $e) {
                    if (System::isDevelopmentMode()) {
                        LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s module.', array($this->getName())));
                }
                $this->setVar('showStatisticInDetails', true);
                $this->setVar('showStatisticOnBottom', false);

            case '1.1.0':
                // for later updates
        }

        // update successful
        return true;
    }
}
