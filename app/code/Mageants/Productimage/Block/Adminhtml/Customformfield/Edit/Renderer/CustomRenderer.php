<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Block\Adminhtml\Customformfield\Edit\Renderer;
 
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class CustomRenderer extends \Magento\Framework\Data\Form\Element\AbstractElement
{
   
    private $_storeManager;
   
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\UrlInterface $urlBuilder,
        StoreManagerInterface $storemanager,
        \Mageants\Productimage\Model\GridFactory $gridFactory,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->_storeManager = $storemanager;
        $this->gridFactory = $gridFactory;
        $this->_authorization = $context->getAuthorization();
    }
    public function getModel()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->gridFactory->create();
        return $rowData->load($rowId);
    }
    public function getAfterElementHtml()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        $imageUrl = $mediaDirectory.'theme/images/1.jpeg';
        $customHtml = '<div><img src="'.$imageUrl.'" width="100" height="100"/></div>';
        return $customHtml;
    }
}
