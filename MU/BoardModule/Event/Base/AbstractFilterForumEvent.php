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

namespace MU\BoardModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use MU\BoardModule\Entity\ForumEntity;

/**
 * Event base class for filtering forum processing.
 */
class AbstractFilterForumEvent extends Event
{
    /**
     * @var ForumEntity Reference to treated entity instance.
     */
    protected $forum;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    /**
     * FilterForumEvent constructor.
     *
     * @param ForumEntity $forum Processed entity
     * @param array $entityChangeSet Change set for preUpdate events
     */
    public function __construct(ForumEntity $forum, array $entityChangeSet = [])
    {
        $this->forum = $forum;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * Returns the entity.
     *
     * @return ForumEntity
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Returns the change set.
     *
     * @return array Entity change set
     */
    public function getEntityChangeSet()
    {
        return $this->entityChangeSet;
    }
}
