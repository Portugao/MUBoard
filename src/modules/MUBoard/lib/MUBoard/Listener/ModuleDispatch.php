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
 * Event handler implementation class for dispatching modules.
 */
class MUBoard_Listener_ModuleDispatch
{
    /**
     * Listener for the `module_dispatch.postloadgeneric` event.
     *
     * Called after a module api or controller has been loaded.
     * Receives the args `array('modinfo' => $modinfo, 'type' => $type, 'force' => $force, 'api' => $api)`.
     */
    public static function postLoadGeneric(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `module_dispatch.preexecute` event.
     *
     * Occurs in `ModUtil::exec()` after function call with the following args:
     * `array('modname' => $modname, 'modfunc' => $modfunc, 'args' => $args, 'modinfo' => $modinfo, 'type' => $type, 'api' => $api)`.
     */
    public static function preExecute(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `module_dispatch.postexecute` event.
     *
     * Occurs in `ModUtil::exec()` after function call with the following args:
     * `array('modname' => $modname, 'modfunc' => $modfunc, 'args' => $args, 'modinfo' => $modinfo, 'type' => $type, 'api' => $api)`.
     * Receives the modules output with `$event->getData();`.
     * Can modify this output with `$event->setData($data);`.
     */
    public static function postExecute(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `module_dispatch.custom_classname` event.
     *
     * In order to override the classname calculated in `ModUtil::exec()`.
     * In order to override a pre-existing controller/api method, use this event type to override the class name that is loaded.
     * This allows to override the methods using inheritance.
     * Receives no subject, args of `array('modname' => $modname, 'modinfo' => $modinfo, 'type' => $type, 'api' => $api)`
     * and 'event data' of `$className`. This can be altered by setting `$event->setData()` followed by `$event->stop()`.
     */
    public static function customClassname(Zikula_Event $event)
    {
    }
}
