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

namespace MU\BoardModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use MU\BoardModule\Traits\StandardFieldsTrait;
use MU\BoardModule\Validator\Constraints as BoardAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for forum entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractForumEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'forum';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @BoardAssert\ListEntry(entityName="forum", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $title
     */
    protected $title = '';
    
    /**
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="2000")
     * @var text $description
     */
    protected $description = '';
    
    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=1000)
     * @var integer $pos
     */
    protected $pos = 0;
    
    
    /**
     * Bidirectional - Many forum [forums] are linked by one category [category] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="MU\BoardModule\Entity\CategoryEntity", inversedBy="forum")
     * @ORM\JoinTable(name="mu_board_category")
     * @Assert\Type(type="MU\BoardModule\Entity\CategoryEntity")
     * @var \MU\BoardModule\Entity\CategoryEntity $category
     */
    protected $category;
    
    /**
     * Bidirectional - One forum [forum] has many posting [postings] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="MU\BoardModule\Entity\PostingEntity", mappedBy="forum", cascade={"remove"})
     * @ORM\JoinTable(name="mu_board_forumposting")
     * @ORM\OrderBy({"createdDate" = "ASC"})
     * @var \MU\BoardModule\Entity\PostingEntity[] $posting
     */
    protected $posting = null;
    
    
    /**
     * ForumEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->posting = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        if ($this->title !== $title) {
            $this->title = isset($title) ? $title : '';
        }
    }
    
    /**
     * Returns the description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description.
     *
     * @param text $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        if ($this->description !== $description) {
            $this->description = isset($description) ? $description : '';
        }
    }
    
    /**
     * Returns the pos.
     *
     * @return integer
     */
    public function getPos()
    {
        return $this->pos;
    }
    
    /**
     * Sets the pos.
     *
     * @param integer $pos
     *
     * @return void
     */
    public function setPos($pos)
    {
        if (intval($this->pos) !== intval($pos)) {
            $this->pos = intval($pos);
        }
    }
    
    
    /**
     * Returns the category.
     *
     * @return \MU\BoardModule\Entity\CategoryEntity
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Sets the category.
     *
     * @param \MU\BoardModule\Entity\CategoryEntity $category
     *
     * @return void
     */
    public function setCategory($category = null)
    {
        $this->category = $category;
    }
    
    /**
     * Returns the posting.
     *
     * @return \MU\BoardModule\Entity\PostingEntity[]
     */
    public function getPosting()
    {
        return $this->posting;
    }
    
    /**
     * Sets the posting.
     *
     * @param \MU\BoardModule\Entity\PostingEntity[] $posting
     *
     * @return void
     */
    public function setPosting($posting)
    {
        foreach ($this->posting as $postingSingle) {
            $this->removePosting($postingSingle);
        }
        foreach ($posting as $postingSingle) {
            $this->addPosting($postingSingle);
        }
    }
    
    /**
     * Adds an instance of \MU\BoardModule\Entity\PostingEntity to the list of posting.
     *
     * @param \MU\BoardModule\Entity\PostingEntity $posting The instance to be added to the collection
     *
     * @return void
     */
    public function addPosting(\MU\BoardModule\Entity\PostingEntity $posting)
    {
        $this->posting->add($posting);
        $posting->setForum($this);
    }
    
    /**
     * Removes an instance of \MU\BoardModule\Entity\PostingEntity from the list of posting.
     *
     * @param \MU\BoardModule\Entity\PostingEntity $posting The instance to be removed from the collection
     *
     * @return void
     */
    public function removePosting(\MU\BoardModule\Entity\PostingEntity $posting)
    {
        $this->posting->removeElement($posting);
        $posting->setForum(null);
    }
    
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'muboardmodule.ui_hooks.forums';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Forum ' . $this->getKey() . ': ' . $this->getTitle();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
