<?php
/**
 * @category  Mageants BannerSlider
 * @package   Mageants_BannerSlider
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@Mageants.com>
 */
namespace Mageants\BannerSlider\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterfac
     */
    protected $_scopeConfig;

    /**
     * @var array
     */
    protected $_sliderConfig;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $_helperBackend;

    /*Extention Enable Disable Constant*/
    const ENABLE = 'mageants_bannerslider/general/enable';

    /*Defaul Slider Setting Configuration Constant*/
    const CONF_MODE = 'mageants_bannerslider/defslidersetting/slider_mode';
  

    /*Defaul Advance Slider Setting Configuration Constant*/
    const CONF_OPACITY = 'mageants_bannerslider/advslidersetting/optopacity';
    const CONF_TRANSLATEFACTOR = 'mageants_bannerslider/advslidersetting/translatefactor';
    const CONF_MAX_ANGLE = 'mageants_bannerslider/advslidersetting/maxangle';
    const CONF_MAX_SCALE = 'mageants_bannerslider/advslidersetting/maxscale';
    const CONF_ON_BEFORE_CHANGE = 'mageants_bannerslider/advslidersetting/onbeforechange';
    const CONF_ON_AFTER_CHANGE = 'mageants_bannerslider/advslidersetting/onafterchange';

    /**
     *construct
     *
     * @param Context $context,
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $HelperBackend
     */
    public function __construct(
        Context $context,
        \Magento\Backend\Helper\Data $HelperBackend,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->_scopeConfig = $context->getscopeConfig();

        $this->storeManager = $storeManager;

        $this->_helperBackend = $HelperBackend;

        $this->_sliderConfig['setting']['width'] = '';
        $this->_sliderConfig['setting']['height'] = '';
        $this->_sliderConfig['setting']['slider_mode'] = $this->_scopeConfig->getValue(self::CONF_MODE, ScopeInterface::SCOPE_STORE);


        $this->_sliderConfig['setting']['optopacity'] = $this->_scopeConfig->getValue(self::CONF_OPACITY, ScopeInterface::SCOPE_STORE);
        $this->_sliderConfig['setting']['translatefactor'] = $this->_scopeConfig->getValue(self::CONF_TRANSLATEFACTOR, ScopeInterface::SCOPE_STORE);
        $this->_sliderConfig['setting']['maxangle'] = $this->_scopeConfig->getValue(self::CONF_MAX_ANGLE, ScopeInterface::SCOPE_STORE);
        $this->_sliderConfig['setting']['maxscale'] = $this->_scopeConfig->getValue(self::CONF_MAX_SCALE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Media URl
     *
     * @return Url
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    /**
     * Retrieve extention enable or disable
     *
     * @return boolean
     */
    public function isExtentionEnable()
    {
        return $this->_scopeConfig->getValue(self::ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve default slider configuration
     *
     * @return array
     */
    public function getDefaultSliderSetting()
    {
        return $this->_sliderConfig;
    }

    /**
     * Retrieve slider mode
     *
     * @return array
     */
    public function getSliderMode()
    {
        return $this->_scopeConfig->getValue(self::CONF_MODE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve default slide configuration
     *
     * @return array
     */
    public function getDefaultSlideSetting()
    {
        // $slideData['setting']['orientation'] = 'orientation';
        // $slideData['setting']['slice1-rotation'] = '25';
        // $slideData['setting']['slice2-rotation'] = '25';
        // $slideData['setting']['slice1-scale'] = '2';
        // $slideData['setting']['slice2-scale'] = '2';

        // Banner content settings
        // $slideData['setting']['background_color'] = 'e316e3';
        // $slideData['setting']['background_opacity'] = '0.5';
        $slideData['setting']['top'] = '50%';
        $slideData['setting']['right'] = '';
        $slideData['setting']['bottom'] = '';
        $slideData['setting']['left'] = '';
        $slideData['setting']['button_text'] = 'Read More';
        $slideData['setting']['button_url'] = '';
        $slideData['setting']['bg_color_button'] = '';
        $slideData['setting']['font_color_button'] = '';

        return $slideData;
    }

    /**
     * Retrieve edited Slider url
     *
     * @return string
     */
    public function getSlidesGridUrl()
    {
        return $this->_helperBackend->getUrl('mageants_bannerslider/sliders/slides/ajax/1/', ['_current' => true]);
    }

    /**
     * Retrieve url of add new slide to slider
     *
     * @return string
     */
    public function getAddSliderSlidesGridUrl($sliderid)
    {
        return $this->_helperBackend->getUrl('mageants_bannerslider/slides/new/sliderid/' . $sliderid, ['_current' => false]);
    }

    /**
     * Retrieve default slide Oriantation setting Options
     *
     * @return array
     */
    public function getOriantationValues()
    {
        $oriantation['horizontal'] = 'Horizontal';
        $oriantation['vertical'] = 'Vertical';

        return $oriantation;
    }

    /**
     * Retrieve serialize setting
     *
     * @return array
     */
    public function serializeSetting($setting)
    {
        return serialize($setting);
    }

    /**
     * Retrieve unserialize setting
     *
     * @return array
     */
    public function unserializeSetting($setting)
    {
        $data['setting'] = array();

        if (!empty($setting)) {
            return unserialize($setting);
        } else {
            return $data;
        }
    }
}
