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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Mon Jun 18 14:46:37 CEST 2012.
 */

/**
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class MUBoard_Controller_Admin extends MUBoard_Controller_Base_Admin
{
	
    /**
     * Post initialise.
     *
     * Run after construction.
     *
     * @return void
     */
    protected function postInitialize()
    {
        // Set caching to true by default.
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    } 
    
	/**
	 * Controller method to close a posting ( issue )
	 */

	public function close()
	{

		$request = new Zikula_Request_Http();
		$id = $request->getGet()->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
			
		MUBoard_Util_Model::closePosting($id);
			
		return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $id)));
			
	}

	/**
	 * Controller method to reopen a posting ( issue )
	 */

	public function open()
	{

		$request = new Zikula_Request_Http();
		$id = $request->getGet()->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
			
		MUBoard_Util_Model::openPosting($id);
			
		return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $id)));
			
	}

	/**
	 * Controller method to take an abo
	 */

	public function take()
	{
		$request = new Zikula_Request_Http();
		$ot = $request->getGet()->filter('object', 'category', FILTER_SANITIZE_STRING);
		$posting = $request->getGet()->filter('posting', 0, FILTER_SANITIZE_NUMBER_INT);
		$forum = $request->getGet()->filter('forum', 0, FILTER_SANITIZE_NUMBER_INT);
		$category = $request->getGet()->filter('category', 0, FILTER_SANITIZE_NUMBER_INT);
		$view = $request->getGet()->filter('view', 'view', FILTER_SANITIZE_STRING);
		$cat = $request->getGet()->filter('cat', 0, FILTER_SANITIZE_NUMBER_INT);

		MUBoard_Util_Model::takeAbo($posting, $forum, $category);
		if ($posting > 0) {
			if ($ot == 'posting') {
				return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $posting)));
			}
			if ($ot == 'forum') {
				return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'forum', 'id' => $forum)));
			}
		}
		if ($forum > 0 && $view == 'display') {
			return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'category', 'id' => $cat)));
		}

		if ($forum > 0 && $view == 'view') {
			return System::redirect(ModUtil::url($this->name, 'user', 'view' , array('ot' => 'category')));
		}
	}

	/**
	 * Controller method to quit an abo
	 */

	public function quit()
	{
		$request = new Zikula_Request_Http();
		$ot = $request->getGet()->filter('object', 'category', FILTER_SANITIZE_STRING);
		$posting = $request->getGet()->filter('posting', 0, FILTER_SANITIZE_NUMBER_INT);
		$forum = $request->getGet()->filter('forum', 0, FILTER_SANITIZE_NUMBER_INT);
		$category = $request->getGet()->filter('category', 0, FILTER_SANITIZE_NUMBER_INT);
		$view = $request->getGet()->filter('view', 'view', FILTER_SANITIZE_STRING);
		$cat = $request->getGet()->filter('cat', 0, FILTER_SANITIZE_NUMBER_INT);

		MUBoard_Util_Model::quitAbo($posting, $forum, $category);
		if ($posting > 0) {
			if ($ot == 'posting') {
				return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $posting)));
			}
			if ($ot == 'forum') {
				return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'forum', 'id' => $forum)));
			}	}
			if ($forum > 0 && $view == 'display') {
				return System::redirect(ModUtil::url($this->name, 'user', 'display' , array('ot' => 'category', 'id' => $cat)));
			}

			if ($forum > 0 && $view == 'view') {
				return System::redirect(ModUtil::url($this->name, 'user', 'view' , array('ot' => 'category')));
			}
	}
}
