<?php
/**
 * Board.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BoardModule\Helper;

use MU\BoardModule\Helper\Base\AbstractControllerHelper;

use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\RouteUrl;


/**
 * Helper implementation class for controller layer methods.
 */
class ControllerHelper extends AbstractControllerHelper
{
	    /**
     * Processes the parameters for a view action.
     * This includes handling pagination, quick navigation forms and other aspects.
     *
     * @param string          $objectType         Name of treated entity type
     * @param SortableColumns $sortableColumns    Used SortableColumns instance
     * @param array           $templateParameters Template data
     * @param boolean         $hasHookSubscriber  Whether hook subscribers are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processViewActionParameters($objectType, SortableColumns $sortableColumns, array $templateParameters = [], $hasHookSubscriber = false)
    {
    	$templateParameters = parent::processViewActionParameters($objectType, $sortableColumns, $templateParameters);
    	if ($objectType == 'category' && $this->variableApi->get('MUBoardModule', 'showStatisticInDetails') == 1) {
    	$entries = $templateParameters['items'];
    	unset($templateParameters['items']);
    	$postingsRepository = $this->entityFactory->getRepository('posting');
    	$countIssues = 0;
    	$countPostings = 0;
    	foreach ($entries as $entry) {
    		if ($objectType == 'category') {
    			$forums = $entry['forum'];
    			//unset($entry['forum']);
    			if ($forums != NULL) {
    			foreach ($forums as $forum) {
    				// where clause for issues
    				$where = 'tbl.parent_id is NULL';
    				$where .= ' AND ';
    				$where .= 'tbl.forum = ' . $forum['id'];
    				// where clause for postings
    				$where2 = 'tbl.forum = ' . $forum['id'];
    				// count issues for categories
    				$countIssues = $countIssues + $postingsRepository->selectCount($where);
    				// count postings for categories
    				$countPostings = $countPostings + count($forum['posting']);
    				// count issues for forums
    				$countIssuesForum = $postingsRepository->selectCount($where);
    				// count postings for forums    				
    				$countPostingsForum = $postingsRepository->selectCount($where2);
    				// get last posting of forum
    				$last = $postingsRepository->getLastPost($forum['id']);
    				$forum['last'] = $postingsRepository->find($last[0]['id']);
    				$forum['countIssues'] = $countIssuesForum;
    				$forum['countPostings'] = $countPostingsForum;
    				$countIssuesForum = 0;
    				$countPostingsForum = 0;
    				$newForums[] = $forum;
    				unset($forum);
    			}
    			unset($forums);
    			}
    		}

    		$entry['forum'] = $newForums;
    		unset($newForums);
    		$entry['countIssues'] = $countIssues;
    		$entry['countPostings'] = $countPostings;
    		$countIssues = 0;
    		$countPostings = 0;
    		$newEntries[] = $entry;    		
    	}
    	
    	$templateParameters['items'] = $newEntries;
        }
    	
    	return $templateParameters;    
    }
    
    /**
     * Processes the parameters for a display action.
     *
     * @param string  $objectType         Name of treated entity type
     * @param array   $templateParameters Template data
     * @param boolean $hasHookSubscriber  Whether hook subscribers are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processDisplayActionParameters($objectType, array $templateParameters = [], $hasHookSubscriber = false)
    {
    	$contextArgs = ['controller' => $objectType, 'action' => 'display'];
    	if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
    		throw new \Exception($this->__('Error! Invalid object type received.'));
    	}
    
    	if (true === $hasHookSubscriber) {
    		// build RouteUrl instance for display hooks
    		$entity = $templateParameters[$objectType];
    		$urlParameters = $entity->createUrlArgs();
    		$urlParameters['_locale'] = $this->request->getLocale();
    		$templateParameters['currentUrlObject'] = new RouteUrl('muboardmodule_' . strtolower($objectType) . '_display', $urlParameters);
    	}
    	if ($objectType == 'category' && $this->variableApi->get('MUBoardModule', 'showStatisticInDetails') == 1) {
    		$forums = $templateParameters[$objectType]['forum'];
    		$postingsRepository = $this->entityFactory->getRepository('posting');
   		    $countIssues = 0;
   		    $countPostings = 0;
    		if ($forums != NULL) {
    		    foreach ($forums as $forum) {
    		    	// where clause for issues
    		    	$where = 'tbl.parent_id is NULL';
    		    	$where .= ' AND ';
    		    	$where .= 'tbl.forum = ' . $forum['id'];
    		    	// where clause for postings
    		    	$where2 = 'tbl.forum = ' . $forum['id'];
    		    	// count issues for categories
    		    	$countIssues = $countIssues + $postingsRepository->selectCount($where);
    		    	// count postings for categories
    		    	$countPostings = $countPostings + count($forum['posting']);
    		    	// get last for forum
    		    	$last = $postingsRepository->getLastPost($forum['id']);
    		    	$forum['last'] = $postingsRepository->find($last[0]['id']);
    		    	$newForums[] = $forum;
    		    	
    		    	
    		    }
    		//unset($templateParameters[$objectType]['forum']);
    		$templateParameters['category']['countIssues'] = $countIssues;
    		$templateParameters['category']['countPostings'] = $countPostings;
    		$templateParameters['category']['forum'] = $newForums;
    		}

    	}
    	
    	if ($objectType == 'forum' && $this->variableApi->get('MUBoardModule', 'showStatisticInDetails') == 1) {
    		
    		$countIssues = 0;
    		$countPostings = 0;
    		
    		$postings = $templateParameters[$objectType]['posting'];
    		$postingsRepository = $this->entityFactory->getRepository('posting');    		
    		
    		// where clause for issues
    		$where = 'tbl.parent is NULL';
    		$where .= ' AND ';
    		$where .= 'tbl.forum = ' . $entity['id'];
    		
    		// where clause for postings
    		$where2 = 'tbl.forum = ' . $entity['id'];
    		// count issues for forum
    		$countIssues = $countIssues + $postingsRepository->selectCount($where);
    		// count postings for forum
    		$countPostings = $countPostings + count($entity['posting']);
    		
    		// get issues for forum
    		$issues = $postingsRepository->selectWhere($where);		

    		if ($issues) {
    			foreach ($issues as $posting) {
    				if ($posting['parent'] === NULL) {
    					
    					$posting['countAnswers'] = count($posting['children']);

    				    // get last for issue
    				    $last = $postingsRepository->getLastAnswer($posting['id']);
    				    if ($last) {
    				        $posting['last'] = $postingsRepository->find($last[0]['id']);
    				    }
    				
    				}	
    				$newPostings[] = $posting;	
    			}
    			//unset($templateParameters[$objectType]['posting']);

    			$templateParameters['forum']['posting'] = $newPostings;
    		}
    		$templateParameters['forum']['countIssues'] = $countIssues;
    		$templateParameters['forum']['countPostings'] = $countPostings;
    	
    	}
    	

    
    	return $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
    }
}
