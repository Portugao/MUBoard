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

namespace MU\BoardModule\Twig\Base;

use Twig_Extension;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use MU\BoardModule\Helper\ListEntriesHelper;
use MU\BoardModule\Helper\EntityDisplayHelper;
use MU\BoardModule\Helper\WorkflowHelper;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends Twig_Extension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    /**
     * TwigExtension constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param VariableApiInterface   $variableApi    VariableApi service instance
     * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     * @param ListEntriesHelper   $listHelper     ListEntriesHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        VariableApiInterface $variableApi,
        EntityDisplayHelper $entityDisplayHelper,
        WorkflowHelper $workflowHelper,
        ListEntriesHelper $listHelper)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
    }
    
    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * Returns a list of custom Twig functions.
     *
     * @return \Twig_SimpleFunction[] List of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('muboardmodule_moderationObjects', [$this, 'getModerationObjects']),
            new \Twig_SimpleFunction('muboardmodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new \Twig_SimpleFunction('muboardmodule_templateSelector', [$this, 'getTemplateSelector'])
        ];
    }
    
    /**
     * Returns a list of custom Twig filters.
     *
     * @return \Twig_SimpleFilter[] List of filters
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('muboardmodule_fileSize', [$this, 'getFileSize'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('muboardmodule_listEntry', [$this, 'getListEntry']),
            new \Twig_SimpleFilter('muboardmodule_formattedTitle', [$this, 'getFormattedEntityTitle']),
            new \Twig_SimpleFilter('muboardmodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * The muboardmodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|muboardmodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|muboardmodule_objectState(false) }} {# no ui feedback #}
     *
     * @param string  $state      Name of given workflow state
     * @param boolean $uiFeedback Whether the output should include some visual feedback about the state
     *
     * @return string Enriched and translated workflow state ready for display
     */
    public function getObjectState($state = 'initial', $uiFeedback = true)
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if (true === $uiFeedback) {
            $result = '<span class="label label-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    
    /**
     * The muboardmodule_fileSize filter displays the size of a given file in a readable way.
     * Example:
     *     {{ 12345|muboardmodule_fileSize }}
     *
     * @param integer $size     File size in bytes
     * @param string  $filepath The input file path including file name (if file size is not known)
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    public function getFileSize($size = 0, $filepath = '', $nodesc = false, $onlydesc = false)
    {
        if (!is_numeric($size)) {
            $size = (int) $size;
        }
        if (!$size) {
            if (empty($filepath) || !file_exists($filepath)) {
                return '';
            }
            $size = filesize($filepath);
        }
        if (!$size) {
            return '';
        }
    
        return $this->getReadableFileSize($size, $nodesc, $onlydesc);
    }
    
    /**
     * Display a given file size in a readable format
     *
     * @param string  $size     File size in bytes
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    private function getReadableFileSize($size, $nodesc = false, $onlydesc = false)
    {
        $sizeDesc = $this->__('Bytes');
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('KB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('MB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('GB');
        }
        $sizeDesc = '&nbsp;' . $sizeDesc;
    
        // format number
        $dec_point = ',';
        $thousands_separator = '.';
        if ($size - number_format($size, 0) >= 0.005) {
            $size = number_format($size, 2, $dec_point, $thousands_separator);
        } else {
            $size = number_format($size, 0, '', $thousands_separator);
        }
    
        // append size descriptor if desired
        if (!$nodesc) {
            $size .= $sizeDesc;
        }
    
        // return either only the description or the complete string
        return $onlydesc ? $sizeDesc : $size;
    }
    
    
    /**
     * The muboardmodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|muboardmodule_listEntry('entityName', 'fieldName') }}
     *
     * @param string $value      The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     * @param string $delimiter  String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function getListEntry($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && $value != '0') || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    /**
     * The muboardmodule_moderationObjects function determines the amount of unapproved objects.
     * It uses the same logic as the moderation block and the pending content listener.
     *
     * @return string The output of the plugin
     */
    public function getModerationObjects()
    {
        return $this->workflowHelper->collectAmountOfModerationItems();
    }
    
    
    /**
     * The muboardmodule_objectTypeSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getObjectTypeSelector()
    {
        $result = [];
    
        $result[] = [
            'text' => $this->__('Categories'),
            'value' => 'category'
        ];
        $result[] = [
            'text' => $this->__('Forums'),
            'value' => 'forum'
        ];
        $result[] = [
            'text' => $this->__('Postings'),
            'value' => 'posting'
        ];
        $result[] = [
            'text' => $this->__('Abos'),
            'value' => 'abo'
        ];
        $result[] = [
            'text' => $this->__('Users'),
            'value' => 'user'
        ];
        $result[] = [
            'text' => $this->__('Ranks'),
            'value' => 'rank'
        ];
    
        return $result;
    }
    
    
    /**
     * The muboardmodule_templateSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getTemplateSelector()
    {
        $result = [];
    
        $result[] = [
            'text' => $this->__('Only item titles'),
            'value' => 'itemlist_display.html.twig'
        ];
        $result[] = [
            'text' => $this->__('With description'),
            'value' => 'itemlist_display_description.html.twig'
        ];
        $result[] = [
            'text' => $this->__('Custom template'),
            'value' => 'custom'
        ];
    
        return $result;
    }
    
    /**
     * The muboardmodule_formattedTitle filter outputs a formatted title for a given entity.
     * Example:
     *     {{ myPost|muboardmodule_formattedTitle }}
     *
     * @param object $entity The given entity instance
     *
     * @return string The formatted title
     */
    public function getFormattedEntityTitle($entity)
    {
        return $this->entityDisplayHelper->getFormattedTitle($entity);
    }
}
