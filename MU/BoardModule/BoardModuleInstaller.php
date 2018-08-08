<?php
/**
 * Board.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BoardModule;

use MU\BoardModule\Base\AbstractBoardModuleInstaller;

/**
 * Installer implementation class.
 */
class BoardModuleInstaller extends AbstractBoardModuleInstaller
{
    /**
     * Upgrade the MUBoardModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
        /*
         $logger = $this->container->get('logger');
         
         // Upgrade dependent on old version number
         switch ($oldVersion) {
         case '1.0.0':
         // do something
         // ...
         // update the database schema
         try {
         $this->schemaTool->update($this->listEntityClasses());
         } catch (\Exception $exception) {
         $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
         $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUBoardModule', 'errorMessage' => $exception->getMessage()]);
         
         return false;
         }
         }
         */
        
        // update successful
        return true;
    }
}
