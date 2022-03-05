<?php
/**
 * @category  Mageants BannerSlider
 * @package   Mageants_BannerSlider
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@Mageants.com>
 */
namespace Mageants\BannerSlider\Block\Adminhtml\Slides\Edit\Tab;

use \Mageants\BannerSlider\Helper\Data;
use \Mageants\BannerSlider\Model\Config\Source\Opacity;
use \Magento\Backend\Block\Template\Context;
use \Magento\Cms\Model\Wysiwyg\Config;
use \Magento\Config\Model\Config\Source\Yesno;
use \Magento\Framework\Data\FormFactory;
use \Magento\Framework\Registry;

class Setting extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Wysiwyg config
     *
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * Yes No options
     *
     */
    protected $_yesNo;

    /**
     * Default Helper options
     *
     */
    protected $_helper;

    /**
     * constructor
     *
     * @param  Config $wysiwygConfig,
     * @param  Context $context,
     * @param  Registry $registry,
     * @param  FormFactory $formFactory,
     * @param  Yesno $yesNo,
     * @param  Data $helper,
     * @param array $data
     */
    public function __construct(
        Config $wysiwygConfig,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        Data $helper,
        Opacity $opacity,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;

        $this->_yesNo = $yesNo;

        $this->_helper = $helper;

        $this->opacity = $opacity;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Mageants\BannerSlider\Model\Slides $slide */
        $slide = $this->_coreRegistry->registry('mageants_bannerslider_slides');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('slidesetting_');

        $form->setFieldNameSuffix('slidesetting');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Slide Setting'),
                'class' => 'fieldset-wide',
                'class' => 'hidden',
            ]
        );

        // $fieldset->addField(
        //     'orientation',
        //     'select',
        //     [
        //         'name' => 'setting[orientation]',
        //         'label' => __('Speed'),
        //         'title' => __('Speed'),
        //         'class' => 'hidden',
        //         'values' => $this->_helper->getOriantationValues(),
        //     ]
        // );

        // $fieldset->addField(
        //     'slice1-rotation',
        //     'text',
        //     [
        //         'name' => 'setting[slice1-rotation]',
        //         'label' => __('Slice 1 Rotation'),
        //         'title' => __('Slice 1 Rotation'),
        //         'class' => 'hidden',
        //         'values' => $this->_yesNo->toOptionArray(),
        //         'note' => 'Accept nagative and positive value',
        //     ]
        // );

        // $fieldset->addField(
        //     'slice2-rotation',
        //     'text',
        //     [
        //         'name' => 'setting[slice2-rotation]',
        //         'label' => __('Slice 2 Rotation'),
        //         'title' => __('Slice 2 Rotation'),
        //         'class' => 'hidden',
        //         'note' => 'Accept nagative and positive value',
        //     ]
        // );

        // $fieldset->addField(
        //     'slice1-scale',
        //     'text',
        //     [
        //         'name' => 'setting[slice1-scale]',
        //         'label' => __('Slice 1 Scale'),
        //         'title' => __('Slice 1 Scale'),
        //         'class' => 'hidden',
        //         'note' => 'Accept only positive value',
        //     ]
        // );

        // $fieldset->addField(
        //     'slice2-scale',
        //     'text',
        //     [
        //         'name' => 'setting[slice2-scale]',
        //         'label' => __('Slice 2 Scale'),
        //         'title' => __('Slice 2 Scale'),
        //         'class' => 'hidden',
        //         'note' => 'Accept only positive value',
        //     ]
        // );

        // Banner Content Setting
        $fieldset = $form->addFieldset(
            'second_base_fieldset',
            [
                'legend' => __('Banner Content Setting'),
                'class' => 'fieldset-wide',
            ]
        );

        // $bgColor = $fieldset->addField(
        //     'background_color',
        //     'text',
        //     [
        //         'name' => 'background_color',
        //         'label' => __('Background Color'),
        //         'title' => __('Background Color'),
        //         'note' => 'Set Background Color of Banner Content'
        //     ]
        // );

        // $value = $bgColor->getData('value');
        // $html = sprintf('<script type="text/javascript">
        // require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
        //     $(function() {
        //         var $el = $("#%s");
        //         $el.css("backgroundColor", "#%s");

        //         // Attach the color picker
        //         $el.ColorPicker({
        //             color: "%s",
        //             onChange: function (hsb, hex, rgb) {
        //                 $el.css("backgroundColor", "#" + hex).val(hex);
        //             }
        //         });
        //     });
        // });
        // </script>', $bgColor->getHtmlId(), $value, $value);
        // $bgColor->setAfterElementHtml($html);

        // $fieldset->addField(
        //     'background_opacity',
        //     'select',
        //     [
        //         'name' => 'background_opacity',
        //         'label' => __('Background Opacity'),
        //         'title' => __('Background Opacity'),
        //         'values' => $this->opacity->toOptionArray(),
        //         'note' => 'Set Background Opacity of Banner Content'
        //     ]
        // );

        $fieldset->addField(
            'top',
            'text',
            [
                'name' => 'setting[top]',
                'label' => __('Top'),
                'title' => __('Top'),
                'class' => "validate-numbers",
                'note' => 'Set Top property of Banner Content in % or px',
            ]
        );

        $fieldset->addField(
            'right',
            'text',
            [
                'name' => 'setting[right]',
                'label' => __('Right'),
                'title' => __('Right'),
                'class' => "validate-numbers",
                'note' => 'Set Right property of Banner Content in % or px',
            ]
        );

        $fieldset->addField(
            'bottom',
            'text',
            [
                'name' => 'setting[bottom]',
                'label' => __('Bottom'),
                'title' => __('Bottom'),
                'class' => "validate-numbers",
                'note' => 'Set Bottom property of Banner Content in % or px',
            ]
        );

        $fieldset->addField(
            'left',
            'text',
            [
                'name' => 'setting[left]',
                'label' => __('Left'),
                'title' => __('Left'),
                'class' => "validate-numbers",
                'note' => 'Set Left property of Banner Content in % or px',
            ]
        );

        $fieldset->addField(
            'button_text',
            'text',
            [
                'name' => 'setting[button_text]',
                'label' => __('Button Text'),
                'title' => __('Button Text'),
                'note' => 'Set Button Text of Banner Content Button',
            ]
        );

        $fieldset->addField(
            'button_url',
            'text',
            [
                'name' => 'setting[button_url]',
                'label' => __('Button Url'),
                'title' => __('Button Url'),
                'class' => 'validate-url',
                'note' => 'Set Button Url of Banner Content Button',
            ]
        );

        $bgColor_Button = $fieldset->addField(
            'bg_color_button',
            'text',
            [
                'name' => 'setting[bg_color_button]',
                'label' => __('Background Color for button'),
                'title' => __('Background Color for button'),
                'note' => 'Set Background Color for Button of Banner Content',
            ]
        );

        $value = $bgColor_Button->getData('value');
        $html = sprintf('<script type="text/javascript">
        require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
            $(function() {
                var $el = $("#%s");
                $el.css("backgroundColor", "#%s");

                // Attach the color picker
                $el.ColorPicker({
                    color: "%s",
                    onChange: function (hsb, hex, rgb) {
                        $el.css("backgroundColor", "#" + hex).val(hex);
                    }
                });
            });
        });
        </script>', $bgColor_Button->getHtmlId(), $value, $value);
        $bgColor_Button->setAfterElementHtml($html);

        $font_color = $fieldset->addField(
            'font_color_button',
            'text',
            [
                'name' => 'setting[font_color_button]',
                'label' => __('Font Color for button'),
                'title' => __('Font Color for button'),
                'note' => 'Set Font Color for button of Banner Content',
            ]
        );
        $value = $font_color->getData('value');
        $html = sprintf('<script type="text/javascript">
        require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
            $(function() {
                var $el = $("#%s");
                $el.css("backgroundColor", "#%s");

                // Attach the color picker
                $el.ColorPicker({
                    color: "%s",
                    onChange: function (hsb, hex, rgb) {
                        $el.css("backgroundColor", "#" + hex).val(hex);
                    }
                });
            });
        });
        </script>', $font_color->getHtmlId(), $value, $value);
        $font_color->setAfterElementHtml($html);

        $id = $this->getRequest()->getParam('id');

        if (!$id) {
            $slideData = $this->_helper->getDefaultSlideSetting();
            $slide->addData($slideData['setting']);
        } else {
            $settingData = $this->_helper->unserializeSetting($slide->getSlidesetting());
            $slide->addData($settingData['setting']);
        }

        $slideData = $this->_session->getData('mageants_bannerslider_slides_data', true);
        if ($slideData) {
            $slide->addData($slideData);
        }

        $form->addValues($slide->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare Slider for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Advance Setting');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
