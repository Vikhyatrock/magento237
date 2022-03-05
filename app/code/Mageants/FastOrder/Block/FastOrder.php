<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FastOrder\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 *
 * @package Mageants\FastOrder\Block\Popup
 */
class FastOrder extends Template
{
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;
    
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleDirReader;
    
    /**
     * @var \Mageants\FastOrder\Helper\Data
     */
    protected $_fastOrderHelper;

    /**
     * @var CUST_GROUP_CONF_ALL
     */
    const CUST_GROUP_CONF_ALL = 32000 ;
    
    /**
     * @var CUST_NOT_LOGIN
     */
    const CUST_NOT_LOGIN = 0;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Module\Dir\Reader $moduleDirReader
     * @param \Mageants\FastOrder\Helper\Data $fastOrderHelper
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Module\Dir\Reader $moduleDirReader,
        \Mageants\FastOrder\Helper\Data $fastOrderHelper,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Customer\Model\SessionFactory $customerSession,
        array $data = []
    ) {
        $this->moduleDirReader=$moduleDirReader;
        $this->_fastOrderHelper=$fastOrderHelper;
        $this->_customerUrl = $customerUrl;
        $this->pageConfig = $context->getPageConfig();
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        //set page title
        $this->pageConfig->getTitle()->set(__('Fast Order'));
        return parent::_prepareLayout();
    }
    
    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->_customerUrl->getLoginUrl();
    }
    /**
     * @return array
     */
    public function getCsvUrl()
    {
        return $this->_fastOrderHelper->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'fastorder/csv/import_fastorder.csv';
    }
    
    /**
     * @return int
     */
    public function getNoOfLines()
    {
        return $this->_fastOrderHelper->getFastOrderConfig('fastorder/general/number_of_lines');
    }

    /**
     * @return int
     */
    public function getMaxRow()
    {
        return $this->_fastOrderHelper->getFastOrderConfig('fastorder/general/max_results');
    }
}
