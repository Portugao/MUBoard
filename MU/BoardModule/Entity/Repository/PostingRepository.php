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

namespace MU\BoardModule\Entity\Repository;

use MU\BoardModule\Entity\Repository\Base\AbstractPostingRepository;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the concrete repository class for posting entities.
 */
class PostingRepository extends AbstractPostingRepository
{
	/**
	 * 
	 * @param integer $forumid
	 */
    public function getLastPost($forumid) {
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('tbl');
    	$qb->from($this->mainEntityClass, 'tbl');
    	$qb->where('tbl.forum = :forum')->setParameter('forum', $forumid);
    	$qb->setMaxResults(1);
    	$qb->orderBy('tbl.createdDate', 'DESC');
    	
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }
    
    /**
     * @param integer $issueid
     */
    public function getLastAnswer($issueid) {
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('tbl');
    	$qb->from($this->mainEntityClass, 'tbl');
    	$qb->where('tbl.parent = :issue')->setParameter('issue', $issueid);
    	$qb->setMaxResults(1);
    	$qb->orderBy('tbl.createdDate', 'DESC');
    	 
    	$query = $qb->getQuery();
    	$result = $query->getResult();
    	return $result;    	
    }
}
