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
 * This handler class handles the page events of the Form called by the MUBoard_user_edit() function.
 * It aims on the posting object type.
 *
 * More documentation is provided in the parent class.
 */
class MUBoard_Form_Handler_User_Posting_Base_Edit extends MUBoard_Form_Handler_User_Edit
{
    /**
     * Persistent member vars
     */

    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
        parent::preInitialize();

        $this->objectType = 'posting';
        $this->objectTypeCapital = 'Posting';
        $this->objectTypeLower = 'posting';
        $this->objectTypeLowerMultiple = 'postings';

        $this->hasPageLockSupport = true;
        // array with upload fields and mandatory flags
        $this->uploadFields = array('firstImage' => false, 'secondImage' => false, 'thirdImage' => false, 'firstFile' => false, 'secondFile' => false, 'thirdFile' => false);
    }

    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        parent::initialize($view);

        $entity = $this->entityRef;

        if ($this->mode == 'edit') {
        } else {
            if ($this->hasTemplateId !== true) {
                $entity['parent'] = $this->retrieveRelatedObjects('posting', 'parent', false);
                $entity['forum'] = $this->retrieveRelatedObjects('forum', 'forum', false);
                $entity['children'] = $this->retrieveRelatedObjects('posting', 'children', true);
            }
        }

        // save entity reference for later reuse
        $this->entityRef = $entity;

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        parent::postInitialize();
    }

    /**
     * Get list of allowed redirect codes.
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
        // admin list of forums
        $codes[] = 'adminViewForum';
        // admin display page of treated forum
        $codes[] = 'adminDisplayForum';
        // user list of forums
        $codes[] = 'userViewForum';
        // user display page of treated forum
        $codes[] = 'userDisplayForum';
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args, $obj)
    {
        // redirect to the list of postings
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, 'user', 'view', $viewArgs);

        if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
            // redirect to the detail page of treated posting
            $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'posting', 'id' => $this->idValues['id']));
        }
        return $url;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $result = parent::handleCommand($view, $args);
        if ($result === false) {
            return $result;
        }

        return $this->view->redirect($this->getRedirectUrl($args, $entity, $repeatCreateAction));
    }

    /**
     * Get success or error message for default operations.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     * @return String desired status or error message.
     */
    protected function getDefaultMessage($args, $success = false)
    {
        if ($success !== true) {
            return parent::getDefaultMessage($args, $success);
        }

        $message = '';
        switch ($args['commandName']) {
            case 'create':
                $message = $this->__('Done! Posting created.');
                break;
            case 'update':
                $message = $this->__('Done! Posting updated.');
                break;
            case 'update':
                $message = $this->__('Done! Posting deleted.');
                break;
        }
        return $message;
    }
    /**
     * Input data processing called by handleCommand method.
     */
    public function fetchInputData(Zikula_Form_View $view, &$args)
    {
        parent::fetchInputData($view, $args);

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        $entityData = array();

        $this->reassignRelatedObjects();
        $entityData['Parent'] = ((isset($selectedRelations['parent'])) ? $selectedRelations['parent'] : $this->retrieveRelatedObjects('posting', 'muboardPosting_ParentItemList', false, 'POST'));
        $entityData['Forum'] = ((isset($selectedRelations['forum'])) ? $selectedRelations['forum'] : $this->retrieveRelatedObjects('forum', 'muboardForum_ForumItemList', false, 'POST'));

        // assign fetched data
        if (count($entityData) > 0) {
            $entity->merge($entityData);
        }

        // save updated entity
        $this->entityRef = $entity;
    }
    /**
     * Executing insert and update statements
     *
     * @param Array   $args    arguments from handleCommand method.
     */
    public function performUpdate($args)
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        $this->updateRelationLinks($entity);
        //$this->entityManager->transactional(function($entityManager) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        //});
        }

    /**
     * Get url to redirect to.
     */
    protected function getRedirectUrl($args, $obj, $repeatCreateAction = false)
    {
        if ($this->inlineUsage == true) {
            $urlArgs = array('idp' => $this->idPrefix,
                'com' => $args['commandName']);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
            // inline usage, return to special function for closing the Zikula.UI.Window instance
            return ModUtil::url($this->name, 'user', 'handleInlineRedirect', $urlArgs);
        }

        if ($repeatCreateAction) {
            return $this->repeatReturnUrl;
        }

        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args, $obj);
        }

        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'admin':
                return ModUtil::url($this->name, 'admin', 'main');
            case 'adminView':
                return ModUtil::url($this->name, 'admin', 'view',
                    array('ot' => $this->objectType));
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    return ModUtil::url($this->name, 'admin', $this->addIdentifiersToUrlArgs());
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'user':
                return ModUtil::url($this->name, 'user', 'main');
            case 'userView':
                return ModUtil::url($this->name, 'user', 'view',
                    array('ot' => $this->objectType));
            case 'userDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    return ModUtil::url($this->name, 'user', $this->addIdentifiersToUrlArgs());
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'search':
                return ModUtil::url($this->name, 'search', 'main');
            case 'adminViewForum':
                return ModUtil::url($this->name, 'admin', 'view',
                    array('ot' => 'forum'));
            case 'adminDisplayForum':
                if (!empty($this->forum)) {
                    return ModUtil::url($this->name, 'admin', 'display', array('ot' => 'forum', 'id' => $this->forum));
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'userViewForum':
                return ModUtil::url($this->name, 'user', 'view',
                    array('ot' => 'forum'));
            case 'userDisplayForum':
                if (!empty($this->forum)) {
                    return ModUtil::url($this->name, 'user', 'display', array('ot' => 'forum', 'id' => $this->forum));
                }
                return $this->getDefaultReturnUrl($args, $obj);
            default:
                return $this->getDefaultReturnUrl($args, $obj);
        }
    }

    /**
     * Reassign options chosen by the user to avoid unwanted form state resets.
     * Necessary until issue #23 is solved.
     */
    public function reassignRelatedObjects()
    {
        $selectedRelations = array();
        // reassign the posting eventually chosen by the user
        $selectedRelations['parent'] = $this->retrieveRelatedObjects('posting', 'muboardPosting_ParentItemList', false, 'POST');
        // reassign the forum eventually chosen by the user
        $selectedRelations['forum'] = $this->retrieveRelatedObjects('forum', 'muboardForum_ForumItemList', false, 'POST');
        // reassign the postings eventually chosen by the user
        $selectedRelations['children'] = $this->retrieveRelatedObjects('posting', 'muboardPosting_ChildrenItemList', true, 'POST');
        $this->view->assign('selectedRelations', $selectedRelations);
    }
    /**
     * Helper method for updating links to related records.
     */
    protected function updateRelationLinks($entity)
    {
    }
}