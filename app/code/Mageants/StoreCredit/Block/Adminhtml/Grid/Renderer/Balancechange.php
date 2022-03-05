<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Grid\Renderer;

/**
 * Balancechange class for display balance in Grid
 */
class Balancechange extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
     /**
      * @var $storeManager
      */
    protected $storeManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        
        $balance_amount = $row->getData($this->getColumn()->getIndex());
        $value = $priceHelper->currency($balance_amount, true, false);
        if ($balance_amount < '0') {
            return '<span class="col-id text-danger">'.$value.'</span>';
        } else {
            return '<span class="col-id text-success">'.'+'.$value.'</span>';
        }
    }
}
