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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Sep 25 14:18:39 CEST 2012.
 */

/**
 * Version information implementation class.
 */
class MUBoard_Version extends MUBoard_Base_Version
{
    public function getMetaData()
    {
        $meta = array();
        // the current module version
        $meta['version'] = '1.1.1';
        // the displayed name of the module
        $meta['displayname'] = $this->__('MUBoard');
        // the module description
        $meta['description'] = $this->__('MUBoard - a small forum module');
        //! url version of name, should be in lowercase without space
        $meta['url'] = $this->__('muboard');
        // core requirement
        $meta['core_min'] = '1.3.1'; // requires minimum 1.3.1 or later
        $meta['core_max'] = '1.3.99'; // not ready for 1.4.0 yet

        // define special capabilities of this module
        $meta['capabilities'] = array(
            HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true) /*,
             HookUtil::PROVIDER_CAPABLE => array('enabled' => true), // TODO: see #15
             'authentication' => array('version' => '1.0'),
             'profile'        => array('version' => '1.0', 'anotherkey' => 'anothervalue'),
             'message'        => array('version' => '1.0', 'anotherkey' => 'anothervalue')
             */
        );

        // permission schema
        // DEBUG: permission schema aspect starts
        $meta['securityschema'] = array(
            'MUBoard::'               => '::',

            'MUBoard:Category:'       => 'CategoryID::',
            'MUBoard:Category:Forum'  => 'CategoryID:ForumID:',

            'MUBoard:Forum:'          => 'ForumID::',
            'MUBoard:Posting:Posting' => 'PostingID:PostingID:',
            'MUBoard:Forum:Posting'   => 'ForumID:PostingID:',

            'MUBoard:Posting:'        => 'PostingID::',
        );
        // DEBUG: permission schema aspect ends

        return $meta;
    }
	
}
