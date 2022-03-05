<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Account\Tab;

use Magento\Customer\Model\Session;
use Mageants\StoreCredit\Model\StoreCredit;

/**
 * View class for fetch store credit data for Customer account store credit tab
 */
class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var $storecreditfactory
     */
    protected $storecreditfactory;
    /**
     * @var $timezone
     */
    protected $timezone;
    /**
     * @var $orderRepository
     */
    protected $orderRepository;
    
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_storecreditfactory = $storecreditfactory;
        $this->timezone = $timezone;
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }
    
    public function getStockcreditdata()
    {
        $id = $this->_customerSession->getCustomerId();
        $collection = $this->_storecreditfactory->create()->getCollection()
                   ->addFieldToSelect('*')
                   ->addFieldToFilter('customer_id', $id)
                   ->setOrder(
                       'id',
                       'DESC'
                   );
        $store_credit_data = $collection->getData();
        return $store_credit_data;
    }

    public function getStockcreditbalance()
    {
        $id = $this->_customerSession->getCustomerId();
        $credit_balance = '0';
        $collection = $this->_storecreditfactory->create()->getCollection()
                   ->addFieldToSelect('new_balance')
                   ->addFieldToFilter('customer_id', $id)
                   ->setOrder(
                       'id',
                       'DESC'
                   )
                    ->setPageSize(1)
                    ->getLastItem();
        $amount_data = $collection->getData();
        if (!empty($amount_data['new_balance'])) {
            $credit_balance = $amount_data['new_balance'];
        }
        return $credit_balance;
    }
    
    public function Stockcreditdateformat($date)
    {
        $dateTimeZone = $this->timezone->date(new \DateTime($date))->format('m/d/y');
        return $dateTimeZone;
    }
    
    public function StockcreditorderincrementID($order_id)
    {
        $order = $this->orderRepository->get($order_id);
        $orderIncrementId = $order->getIncrementId();
        return '#'.$orderIncrementId;
    }
}
