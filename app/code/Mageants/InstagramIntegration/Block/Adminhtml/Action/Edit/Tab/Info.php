<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

/**
 * Class Info
 * @package Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit\Tab
 */
class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $newsStatus
     * @param SessionManagerInterface $coreSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_coreSession = $coreSession;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('instagram');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('insta_');
        $form->setFieldNameSuffix('insta');
        
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );

        $fieldset->addType(
            'image',
            '\Mageants\InstagramIntegration\Block\Adminhtml\InstagramIntegration\Grid\Renderer\Image'
        );
        $fieldset->addField(
            'image',
            'image',
            [
                'name'  => 'image',
                'title' => __('Diagram')
            ]
        );
        $fieldset->addField(
            'id',
            'hidden',
            ['name' => 'id']
        );

        $fieldset->addField(
            "link",
            'text',
            [
                'name' => 'link',
                'label' => __('Link URL'),
                'note' => __('Enter link URL to redirect users on click of image.'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title",
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'comment' => __('Title'),
                'note' => __('Enter image title to show on frontend.'),
                'required' => false
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig();
        $fieldset->addField(
            'insta_caption',
            'editor',
            [
                'name' => 'insta_caption',
                'label' => __('Description'),
                'required' => false,
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField(
            "title1",
            'text',
            [
                'name' => 'title1',
                'label' => __('Title 1'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title1",
            'text',
            [
                'name' => 'url_title1',
                'label' => __('Link URL for Title 1'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top1",
            'text',
            [
                'name' => 'position_top1',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left1",
            'text',
            [
                'name' => 'position_left1',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_1",
            'text',
            [
                'name' => 'product_id_1',
                'label' => __('Product Id 1'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title2",
            'text',
            [
                'name' => 'title2',
                'label' => __('Title 2'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title2",
            'text',
            [
                'name' => 'url_title2',
                'label' => __('Link URL for Title 2'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top2",
            'text',
            [
                'name' => 'position_top2',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left2",
            'text',
            [
                'name' => 'position_left2',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_2",
            'text',
            [
                'name' => 'product_id_2',
                'label' => __('Product Id 2'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title3",
            'text',
            [
                'name' => 'title3',
                'label' => __('Title 3'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title3",
            'text',
            [
                'name' => 'url_title3',
                'label' => __('Link URL for Title 3'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top3",
            'text',
            [
                'name' => 'position_top3',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left3",
            'text',
            [
                'name' => 'position_left3',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_3",
            'text',
            [
                'name' => 'product_id_3',
                'label' => __('Product Id 3'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title4",
            'text',
            [
                'name' => 'title4',
                'label' => __('Title 4'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title4",
            'text',
            [
                'name' => 'url_title4',
                'label' => __('Link URL for Title 4'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top4",
            'text',
            [
                'name' => 'position_top4',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left4",
            'text',
            [
                'name' => 'position_left4',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_4",
            'text',
            [
                'name' => 'product_id_4',
                'label' => __('Product Id 4'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title5",
            'text',
            [
                'name' => 'title5',
                'label' => __('Title 5'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title5",
            'text',
            [
                'name' => 'url_title5',
                'label' => __('Link URL for Title 5'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top5",
            'text',
            [
                'name' => 'position_top5',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left5",
            'text',
            [
                'name' => 'position_left5',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_5",
            'text',
            [
                'name' => 'product_id_5',
                'label' => __('Product Id 5'),
                'required' => false
            ]
        );

        $fieldset->addField(
            "title6",
            'text',
            [
                'name' => 'title6',
                'label' => __('Title 6'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "url_title6",
            'text',
            [
                'name' => 'url_title6',
                'label' => __('Link URL for Title 6'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_top6",
            'text',
            [
                'name' => 'position_top6',
                'label' => __('Position-TOP'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "position_left6",
            'text',
            [
                'name' => 'position_left6',
                'label' => __('Position-LEFT'),
                'required' => false
            ]
        );
        $fieldset->addField(
            "product_id_6",
            'text',
            [
                'name' => 'product_id_6',
                'label' => __('Product Id 6'),
                'required' => false
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Insta Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Insta Info');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
