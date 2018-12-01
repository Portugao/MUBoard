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

namespace MU\BoardModule\Form\Handler\Posting\Base;

use MU\BoardModule\Form\Handler\Common\EditHandler;
use MU\BoardModule\Form\Type\PostingType;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use RuntimeException;

/**
 * This handler class handles the page events of editing forms.
 * It aims on the posting object type.
 */
abstract class AbstractEditHandler extends EditHandler
{
    /**
     * @inheritDoc
     */
    public function processForm(array $templateParameters = [])
    {
        $this->objectType = 'posting';
        $this->objectTypeCapital = 'Posting';
        $this->objectTypeLower = 'posting';
        
        $this->hasPageLockSupport = true;
    
        $result = parent::processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
    
        if ('create' == $this->templateParameters['mode']) {
            if (!$this->modelHelper->canBeCreated($this->objectType)) {
                $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('error', $this->__('Sorry, but you can not create the posting yet as other items are required which must be created before!'));
                $logArgs = ['app' => 'MUBoardModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => $this->objectType];
                $this->logger->notice('{app}: User {user} tried to create a new {entity}, but failed as it other items are required which must be created before.', $logArgs);
    
                return new RedirectResponse($this->getRedirectUrl(['commandName' => '']), 302);
            }
        }
    
        $entityData = $this->entityRef->toArray();
    
        // assign data to template as array (for additions like standard fields)
        $this->templateParameters[$this->objectTypeLower] = $entityData;
        $this->templateParameters['supportsHookSubscribers'] = $this->entityRef->supportsHookSubscribers();
    
        return $result;
    }
    
    /**
     * @inheritDoc
     */
    protected function initRelationPresets()
    {
        $entity = $this->entityRef;
        
        // assign identifiers of predefined incoming relationships
        // editable relation, we store the id and assign it now to show it in UI
        $this->relationPresets['parent'] = $this->requestStack->getCurrentRequest()->query->get('parent', '');
        if (!empty($this->relationPresets['parent'])) {
            $relObj = $this->entityFactory->getRepository('posting')->selectById($this->relationPresets['parent']);
            if (null !== $relObj) {
                $relObj->addChildren($entity);
            }
        }
        // editable relation, we store the id and assign it now to show it in UI
        $this->relationPresets['forum'] = $this->requestStack->getCurrentRequest()->query->get('forum', '');
        if (!empty($this->relationPresets['forum'])) {
            $relObj = $this->entityFactory->getRepository('forum')->selectById($this->relationPresets['forum']);
            if (null !== $relObj) {
                $relObj->addPosting($entity);
            }
        }
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    }
    
    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        return $this->formFactory->create(PostingType::class, $this->entityRef, $this->getFormOptions());
    }
    
    /**
     * @inheritDoc
     */
    protected function getFormOptions()
    {
        $options = [
            'entity' => $this->entityRef,
            'mode' => $this->templateParameters['mode'],
            'actions' => $this->templateParameters['actions'],
            'has_moderate_permission' => $this->permissionHelper->hasEntityPermission($this->entityRef, ACCESS_ADMIN),
            'allow_moderation_specific_creator' => $this->variableApi->get('MUBoardModule', 'allowModerationSpecificCreatorFor' . $this->objectTypeCapital, false),
            'allow_moderation_specific_creation_date' => $this->variableApi->get('MUBoardModule', 'allowModerationSpecificCreationDateFor' . $this->objectTypeCapital, false),
            'filter_by_ownership' => !$this->permissionHelper->hasEntityPermission($this->entityRef, ACCESS_ADD),
            'inline_usage' => $this->templateParameters['inlineUsage']
        ];
    
        $workflowRoles = $this->prepareWorkflowAdditions(false);
        $options = array_merge($options, $workflowRoles);
    
        return $options;
    }

    /**
     * @inheritDoc
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
    
        // user index page of posting area
        $codes[] = 'userIndex';
        // admin index page of posting area
        $codes[] = 'adminIndex';
        // user list of postings
        $codes[] = 'userView';
        // admin list of postings
        $codes[] = 'adminView';
        // user list of own postings
        $codes[] = 'userOwnView';
        // admin list of own postings
        $codes[] = 'adminOwnView';
        // user detail page of treated posting
        $codes[] = 'userDisplay';
        // admin detail page of treated posting
        $codes[] = 'adminDisplay';
    
        // user list of forums
        $codes[] = 'userViewForums';
        // admin list of forums
        $codes[] = 'adminViewForums';
        // user list of own forums
        $codes[] = 'userOwnViewForums';
        // admin list of own forums
        $codes[] = 'adminOwnViewForums';
        // user detail page of related forum
        $codes[] = 'userDisplayForum';
        // admin detail page of related forum
        $codes[] = 'adminDisplayForum';
    
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     *
     * @param array $args List of arguments
     *
     * @return string The default redirect url
     */
    protected function getDefaultReturnUrl(array $args = [])
    {
        $objectIsPersisted = $args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel');
        if (null !== $this->returnTo && $objectIsPersisted) {
            // return to referer
            return $this->returnTo;
        }
    
        $routeArea = array_key_exists('routeArea', $this->templateParameters) ? $this->templateParameters['routeArea'] : '';
        $routePrefix = 'muboardmodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // redirect to the list of postings
        $url = $this->router->generate($routePrefix . 'view');
    
        if ($objectIsPersisted) {
            // redirect to the detail page of treated posting
            $url = $this->router->generate($routePrefix . 'display', $this->entityRef->createUrlArgs());
        }
    
        return $url;
    }

    /**
     * @inheritDoc
     */
    public function handleCommand(array $args = [])
    {
        $result = parent::handleCommand($args);
        if (false === $result) {
            return $result;
        }
    
        // build $args for BC (e.g. used by redirect handling)
        foreach ($this->templateParameters['actions'] as $action) {
            if ($this->form->get($action['id'])->isClicked()) {
                $args['commandName'] = $action['id'];
            }
        }
        if ('create' == $this->templateParameters['mode'] && $this->form->has('submitrepeat') && $this->form->get('submitrepeat')->isClicked()) {
            $args['commandName'] = 'submit';
            $this->repeatCreateAction = true;
        }
    
        return new RedirectResponse($this->getRedirectUrl($args), 302);
    }
    
    /**
     * @inheritDoc
     */
    protected function getDefaultMessage(array $args = [], $success = false)
    {
        if (false === $success) {
            return parent::getDefaultMessage($args, $success);
        }
    
        $message = '';
        switch ($args['commandName']) {
            case 'submit':
                if ('create' == $this->templateParameters['mode']) {
                    $message = $this->__('Done! Posting created.');
                } else {
                    $message = $this->__('Done! Posting updated.');
                }
                if ('waiting' == $this->entityRef->getWorkflowState()) {
                    $message .= ' ' . $this->__('It is now waiting for approval by our moderators.');
                }
                break;
            case 'delete':
                $message = $this->__('Done! Posting deleted.');
                break;
            default:
                $message = $this->__('Done! Posting updated.');
                break;
        }
    
        return $message;
    }

    /**
     * @inheritDoc
     * @throws RuntimeException Thrown if concurrent editing is recognised or another error occurs
     */
    public function applyAction(array $args = [])
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $action = $args['commandName'];
    
        $success = false;
        $flashBag = $this->requestStack->getCurrentRequest()->getSession()->getFlashBag();
        try {
            // execute the workflow action
            $success = $this->workflowHelper->executeAction($entity, $action);
        } catch (\Exception $exception) {
            $flashBag->add('error', $this->__f('Sorry, but an error occured during the %action% action. Please apply the changes again!', ['%action%' => $action]) . ' ' . $exception->getMessage());
            $logArgs = ['app' => 'MUBoardModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => 'posting', 'id' => $entity->getKey(), 'errorMessage' => $exception->getMessage()];
            $this->logger->error('{app}: User {user} tried to edit the {entity} with id {id}, but failed. Error details: {errorMessage}.', $logArgs);
        }
    
        $this->addDefaultMessage($args, $success);
    
        if ($success && 'create' == $this->templateParameters['mode']) {
            // store new identifier
            $this->idValue = $entity->getKey();
        }
    
        return $success;
    }

    /**
     * Get URL to redirect to.
     *
     * @param array $args List of arguments
     *
     * @return string The redirect url
     */
    protected function getRedirectUrl(array $args = [])
    {
        if ($this->repeatCreateAction) {
            return $this->repeatReturnUrl;
        }
    
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if ($session->has('muboardmodule' . $this->objectTypeCapital . 'Referer')) {
            $this->returnTo = $session->get('muboardmodule' . $this->objectTypeCapital . 'Referer');
            $session->remove('muboardmodule' . $this->objectTypeCapital . 'Referer');
        }
    
        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args);
        }
    
        $routeArea = substr($this->returnTo, 0, 5) == 'admin' ? 'admin' : '';
        $routePrefix = 'muboardmodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'userIndex':
            case 'adminIndex':
                return $this->router->generate($routePrefix . 'index');
            case 'userView':
            case 'adminView':
                return $this->router->generate($routePrefix . 'view');
            case 'userOwnView':
            case 'adminOwnView':
                return $this->router->generate($routePrefix . 'view', [ 'own' => 1 ]);
            case 'userDisplay':
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel')) {
                    return $this->router->generate($routePrefix . 'display', $this->entityRef->createUrlArgs());
                }
    
                return $this->getDefaultReturnUrl($args);
            case 'userViewForums':
            case 'adminViewForums':
                return $this->router->generate('muboardmodule_forum_' . $routeArea . 'view');
            case 'userOwnViewForums':
            case 'adminOwnViewForums':
                return $this->router->generate('muboardmodule_forum_' . $routeArea . 'view', ['own' => 1]);
            case 'userDisplayForum':
            case 'adminDisplayForum':
                if (!empty($this->relationPresets['forum'])) {
                    return $this->router->generate('muboardmodule_forum_' . $routeArea . 'display',  ['id' => $this->relationPresets['forum']]);
                }
    
                return $this->getDefaultReturnUrl($args);
            default:
                return $this->getDefaultReturnUrl($args);
        }
    }
}
