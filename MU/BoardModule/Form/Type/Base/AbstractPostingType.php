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

namespace MU\BoardModule\Form\Type\Base;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use MU\BoardModule\Entity\Factory\EntityFactory;
use MU\BoardModule\Form\Type\Field\UploadType;
use MU\BoardModule\Helper\CollectionFilterHelper;
use MU\BoardModule\Helper\EntityDisplayHelper;
use MU\BoardModule\Helper\ListEntriesHelper;
use MU\BoardModule\Helper\UploadHelper;
use MU\BoardModule\Traits\ModerationFormFieldsTrait;
use MU\BoardModule\Traits\WorkflowFormFieldsTrait;

/**
 * Posting editing form type base class.
 */
abstract class AbstractPostingType extends AbstractType
{
    use TranslatorTrait;
    use ModerationFormFieldsTrait;
    use WorkflowFormFieldsTrait;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;

    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var UploadHelper
     */
    protected $uploadHelper;

    /**
     * PostingType constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param EntityFactory $entityFactory EntityFactory service instance
     * @param CollectionFilterHelper $collectionFilterHelper CollectionFilterHelper service instance
     * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     * @param UploadHelper $uploadHelper UploadHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityFactory $entityFactory,
        CollectionFilterHelper $collectionFilterHelper,
        EntityDisplayHelper $entityDisplayHelper,
        ListEntriesHelper $listHelper,
        UploadHelper $uploadHelper
    ) {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->collectionFilterHelper = $collectionFilterHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->listHelper = $listHelper;
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEntityFields($builder, $options);
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addAdditionalNotificationRemarksField($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('title', TextType::class, [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the title of the posting.')
            ],
            'required' => false,
        ]);
        
        $builder->add('text', TextareaType::class, [
            'label' => $this->__('Text') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => '',
                'title' => $this->__('Enter the text of the posting.')
            ],
            'required' => true,
        ]);
        
        $builder->add('invocations', IntegerType::class, [
            'label' => $this->__('Invocations') . ':',
            'empty_data' => 0,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => $this->__('Enter the invocations of the posting.') . ' ' . $this->__('Only digits are allowed.')
            ],
            'required' => false,
            'scale' => 0
        ]);
        
        $builder->add('state', CheckboxType::class, [
            'label' => $this->__('State') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('state ?')
            ],
            'required' => false,
        ]);
        
        $builder->add('solved', CheckboxType::class, [
            'label' => $this->__('Solved') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('solved ?')
            ],
            'required' => false,
        ]);
        
        $builder->add('firstImage', UploadType::class, [
            'label' => $this->__('First image') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the first image of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'firstImage')),
            'allowed_size' => '200k'
        ]);
        
        $builder->add('secondImage', UploadType::class, [
            'label' => $this->__('Second image') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the second image of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'secondImage')),
            'allowed_size' => '200k'
        ]);
        
        $builder->add('thirdImage', UploadType::class, [
            'label' => $this->__('Third image') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the third image of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'thirdImage')),
            'allowed_size' => '200k'
        ]);
        
        $builder->add('firstFile', UploadType::class, [
            'label' => $this->__('First file') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the first file of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'firstFile')),
            'allowed_size' => '2M'
        ]);
        
        $builder->add('secondFile', UploadType::class, [
            'label' => $this->__('Second file') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the second file of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'secondFile')),
            'allowed_size' => '2M'
        ]);
        
        $builder->add('thirdFile', UploadType::class, [
            'label' => $this->__('Third file') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the third file of the posting.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('posting', 'thirdFile')),
            'allowed_size' => '2M'
        ]);
    }

    /**
     * Adds fields for incoming relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIncomingRelationshipFields(FormBuilderInterface $builder, array $options = [])
    {
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $entityDisplayHelper = $this->entityDisplayHelper;
        $choiceLabelClosure = function ($entity) use ($entityDisplayHelper) {
            return $entityDisplayHelper->getFormattedTitle($entity);
        };
        $builder->add('parent', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'MUBoardModule:PostingEntity',
            'choice_label' => $choiceLabelClosure,
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'placeholder' => $this->__('Please choose an option.'),
            'required' => false,
            'label' => $this->__('Parent'),
            'attr' => [
                'title' => $this->__('Choose the parent.')
            ]
        ]);
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $entityDisplayHelper = $this->entityDisplayHelper;
        $choiceLabelClosure = function ($entity) use ($entityDisplayHelper) {
            return $entityDisplayHelper->getFormattedTitle($entity);
        };
        $builder->add('forum', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'MUBoardModule:ForumEntity',
            'choice_label' => $choiceLabelClosure,
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'placeholder' => $this->__('Please choose an option.'),
            'required' => false,
            'label' => $this->__('Forum'),
            'attr' => [
                'title' => $this->__('Choose the forum.')
            ]
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = [])
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], SubmitType::class, [
                'label' => $action['title'],
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass']
                ]
            ]);
            if ($options['mode'] == 'create' && $action['id'] == 'submit' && !$options['inline_usage']) {
                // add additional button to submit item and return to create form
                $builder->add('submitrepeat', SubmitType::class, [
                    'label' => $this->__('Submit and repeat'),
                    'icon' => 'fa-repeat',
                    'attr' => [
                        'class' => $action['buttonClass']
                    ]
                ]);
            }
        }
        $builder->add('reset', ResetType::class, [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'muboardmodule_posting';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'MU\BoardModule\Entity\PostingEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createPosting();
                },
                'error_mapping' => [
                    'firstImage' => 'firstImage.firstImage',
                    'secondImage' => 'secondImage.secondImage',
                    'thirdImage' => 'thirdImage.thirdImage',
                    'firstFile' => 'firstFile.firstFile',
                    'secondFile' => 'secondFile.secondFile',
                    'thirdFile' => 'thirdFile.thirdFile',
                ],
                'mode' => 'create',
                'is_moderator' => false,
                'is_creator' => false,
                'actions' => [],
                'has_moderate_permission' => false,
                'allow_moderation_specific_creator' => false,
                'allow_moderation_specific_creation_date' => false,
                'filter_by_ownership' => true,
                'inline_usage' => false
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedTypes('is_moderator', 'bool')
            ->setAllowedTypes('is_creator', 'bool')
            ->setAllowedTypes('actions', 'array')
            ->setAllowedTypes('has_moderate_permission', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creator', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creation_date', 'bool')
            ->setAllowedTypes('filter_by_ownership', 'bool')
            ->setAllowedTypes('inline_usage', 'bool')
            ->setAllowedValues('mode', ['create', 'edit'])
        ;
    }
}
