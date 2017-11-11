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
namespace MU\BoardModule\Container;
use MU\BoardModule\Container\Base\AbstractLinkContainer;
use Zikula\Core\LinkContainer\LinkContainerInterface;
/**
 * This is the link container service implementation class.
 */
class LinkContainer extends AbstractLinkContainer
{
    /**
     * Returns available header links.
     *
     * @param string $type The type to collect links for
     *
     * @return array Array of header links
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        $contextArgs = ['api' => 'linkContainer', 'action' => 'getLinks'];
        $allowedObjectTypes = $this->controllerHelper->getObjectTypes('api', $contextArgs);
        $permLevel = LinkContainerInterface::TYPE_ADMIN == $type ? ACCESS_ADMIN : ACCESS_READ;
        // Create an array of links to return
        $links = [];
        if (LinkContainerInterface::TYPE_ACCOUNT == $type) {
            if (!$this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_OVERVIEW)) {
                return $links;
            }
            if (true === $this->variableApi->get('MUBoardModule', 'linkOwnCategoriesOnAccountPage', true)) {
                $objectType = 'category';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('muboardmodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My categories', 'muboardmodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }
            if (true === $this->variableApi->get('MUBoardModule', 'linkOwnForumsOnAccountPage', true)) {
                $objectType = 'forum';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('muboardmodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My forums', 'muboardmodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }
            if (true === $this->variableApi->get('MUBoardModule', 'linkOwnPostingsOnAccountPage', true)) {
                $objectType = 'posting';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('muboardmodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My postings', 'muboardmodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }
            if (true === $this->variableApi->get('MUBoardModule', 'linkOwnAbosOnAccountPage', true)) {
                $objectType = 'abo';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('muboardmodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My abos', 'muboardmodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }
            if (true === $this->variableApi->get('MUBoardModule', 'linkOwnRanksOnAccountPage', true)) {
                $objectType = 'rank';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('muboardmodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My ranks', 'muboardmodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('muboardmodule_category_adminindex'),
                    'text' => $this->__('Board Backend', 'muboardmodule'),
                    'icon' => 'wrench'
                ];
            }
            return $links;
        }
        $routeArea = LinkContainerInterface::TYPE_ADMIN == $type ? 'admin' : '';
        if (LinkContainerInterface::TYPE_ADMIN == $type) {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_READ)) {
                $links[] = [
                    'url' => $this->router->generate('muboardmodule_category_index'),
                    'text' => $this->__('Frontend', 'muboardmodule'),
                    'title' => $this->__('Switch to user area.', 'muboardmodule'),
                    'icon' => 'home'
                ];
            }
        } else {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('muboardmodule_category_adminindex'),
                    'text' => $this->__('Backend', 'muboardmodule'),
                    'title' => $this->__('Switch to administration area.', 'muboardmodule'),
                    'icon' => 'wrench'
                ];
            }
        }
        
        if (in_array('category', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Category:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_category_' . $routeArea . 'view'),
                'text' => $this->__('Categories', 'muboardmodule'),
                'title' => $this->__('Category list', 'muboardmodule')
            ];
        }
        if ($routeArea == 'admin') {
        if (in_array('forum', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Forum:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_forum_' . $routeArea . 'view'),
                'text' => $this->__('Forums', 'muboardmodule'),
                'title' => $this->__('Forum list', 'muboardmodule')
            ];
        }
        if (in_array('posting', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Posting:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_posting_' . $routeArea . 'view'),
                'text' => $this->__('Postings', 'muboardmodule'),
                'title' => $this->__('Posting list', 'muboardmodule')
            ];
        }
        if (in_array('abo', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Abo:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_abo_' . $routeArea . 'view'),
                'text' => $this->__('Abos', 'muboardmodule'),
                'title' => $this->__('Abo list', 'muboardmodule')
            ];
        }
        if (in_array('user', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':User:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_user_' . $routeArea . 'view'),
                'text' => $this->__('Users', 'muboardmodule'),
                'title' => $this->__('User list', 'muboardmodule')
            ];
        }
        if (in_array('rank', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Rank:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_rank_' . $routeArea . 'view'),
                'text' => $this->__('Ranks', 'muboardmodule'),
                'title' => $this->__('Rank list', 'muboardmodule')
            ];
        }
        }
        if ($routeArea == 'admin' && $this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
            $links[] = [
                'url' => $this->router->generate('muboardmodule_config_config'),
                'text' => $this->__('Configuration', 'muboardmodule'),
                'title' => $this->__('Manage settings for this application', 'muboardmodule'),
                'icon' => 'wrench'
            ];
        }
        return $links;
    }
}