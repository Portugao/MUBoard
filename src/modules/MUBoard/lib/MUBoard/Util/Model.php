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
 * Utility implementation class for model helper methods.
 */
class MUBoard_Util_Model extends MUBoard_Util_Base_Model
{

	/**
	 * This function closes a posting
	 * @param id   id of the posting to close
	 */

	public static function closePosting($id) {

		// Security ckeck
		if (SecurityUtil::checkPermission('MUBoard::', '::', ACCESS_ADMIN)) {

			// build posting repository
			$repository = MUBoard_Util_Model::getPostingRepository();

			$entity = $repository->selectById($id);

			$serviceManager = ServiceUtil::getManager();
			$entityManager = $serviceManager->getService('doctrine.entitymanager');

			$entity->setState(0);

			$entityManager->flush();

			return true;
		}
	}

	/**
	 * This function reopens a posting
	 * @param id   id of the posting to open
	 */

	public static function openPosting($id) {

		if (SecurityUtil::checkPermission('MUBoard::', '::', ACCESS_ADMIN)) {

			// build posting repository
			$repository = MUBoard_Util_Model::getPostingRepository();

			$entity = $repository->selectById($id);

			$serviceManager = ServiceUtil::getManager();
			$entityManager = $serviceManager->getService('doctrine.entitymanager');

			$entity->setState(1);

			$entityManager->flush();

			return true;
		}
	}

	/**
	 * This function counts the call of a posting
	 * @param id   id of the ticket to close
	 */

	public static function addView($id) {

		// build posting repository
		$repository = MUBoard_Util_Model::getPostingRepository();

		$entity = $repository->selectById($id);

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');

		$invocation = $entity->getInvocations();
		$entity->setInvocations($invocation + 1);

		$entityManager->flush();

		return true;

	}

	/**
	 * This function counts the call of a posting
	 * @param posting   id of the posting to take abo
	 * @param forum     id of the forum to take abo
	 * @param category  id of the category to take abo
	 */

	public static function takeAbo($posting = 0, $forum = 0, $category = 0) {

		// Security ckeck
		if (SecurityUtil::checkPermission('MUBoard::', '::', ACCESS_COMMENT)) {

			// get actual user id
			$userid = UserUtil::getVar('uid');
			// get new Abo object
			$entity = new MUBoard_Entity_Abo();
			// take abo of posting if posting is taken
			if ($posting > 0) {
				$entity->setPostingid($posting);
			}
			// take abo of forum if forum is taken
			if ($forum > 0) {
				$entity->setForumid($forum);
			}
			// take abo of category if category is taken
			if ($category > 0) {
				$entity->setCatid($category);
			}

			$entity->setUserid($userid);

			$serviceManager = ServiceUtil::getManager();
			$entityManager = $serviceManager->getService('doctrine.entitymanager');

			$entityManager->persist($entity);
			$entityManager->flush();


			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * This function counts the call of a posting
	 * @param posting   id of the posting to quit abo
	 * @param forum     id of the forum to quit abo
	 * @param category  id of the category to quit abo
	 */

	public static function quitAbo($posting = 0, $forum = 0, $category = 0) {

		// Security ckeck
		if (SecurityUtil::checkPermission('MUBoard::', '::', ACCESS_COMMENT)) {

			// build posting repository
			$repository = MUBoard_Util_Model::getAboRepository();
			// get actual user id
			$userid = UserUtil::getVar('uid');
			// quit abo of posting if posting is taken
			if ($posting > 0) {
				// look for abo
				$where = 'tbl.postingid = \'' . DataUtil::formatForStore($posting) . '\'';
				$where .= ' AND ';
				$where .= 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
				$abos = $repository->selectWhere($where);
				$serviceManager = ServiceUtil::getManager();
				$entityManager = $serviceManager->getService('doctrine.entitymanager');
				foreach ($abos as $abo) {
					$entityManager->remove($abo);
					$entityManager->flush();
				}
			}

			if ($forum > 0) {
				// look for abo
				$where = 'tbl.forumid = \'' . DataUtil::formatForStore($forum) . '\'';
				$where .= ' AND ';
				$where .= 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
				$abos = $repository->selectWhere($where);
				$serviceManager = ServiceUtil::getManager();
				$entityManager = $serviceManager->getService('doctrine.entitymanager');
				foreach ($abos as $abo) {
					$entityManager->remove($abo);
					$entityManager->flush();
				}
			}

			return true;
		}
	}

	/**
	 *
	 This method is for getting a repository for abo
	 *
	 */

	public static function getAboRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUBoard_Entity_Abo');

		return $repository;
	}

	/**
	 *
	 This method is for getting a repository for category
	 *
	 */

	public static function getCategoryRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUBoard_Entity_Category');

		return $repository;
	}

	/**
	 *
	 This method is for getting a repository for forum
	 *
	 */

	public static function getForumRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUBoard_Entity_Forum');

		return $repository;
	}

	/**
	 *
	 This method is for getting a repository for posting
	 *
	 */

	public static function getPostingRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUBoard_Entity_Posting');

		return $repository;
	}
}
