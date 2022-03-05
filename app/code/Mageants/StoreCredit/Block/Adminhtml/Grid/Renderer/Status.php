<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Grid\Renderer;

/**
 * Status class for display status in Grid
 */
class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
     /**
      * @var $storeManager,@var $orderRepository
      */
    protected $storeManager,$orderRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->orderRepository = $orderRepository;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $current_row_order_id = $row->getData('order_id');
        if (!empty($current_row_order_id)) {
            $incremented_order_id = $this->StockcreditorderincrementID($current_row_order_id);
        }
        
        $value = $row->getData($this->getColumn()->getIndex());
        $action_array = ["0" => "Added by Admin" , "1" => "Removed by Admin", "2" => "Refunded", "3" => "Order Paid", "4" => "Order Canceled"];
        
        if ($value != '0' && $value != '1' && !empty($incremented_order_id)) {
            return $action_array[$value].' #'.$incremented_order_id;
        } else {
            return 'Changed By Admin';
        }
    }

    public function StockcreditorderincrementID($order_id)
    {
        $order = $this->orderRepository->get($order_id);
        $orderIncrementId = $order->getIncrementId();
        return $orderIncrementId;
    }
}
