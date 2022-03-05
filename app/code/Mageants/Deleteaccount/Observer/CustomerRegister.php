<?php
/**
 * @category Mageants DeleteAccount
 * @package Mageants_DeleteAccount
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Deleteaccount\Observer;

use Magento\Framework\Event\ObserverInterface;
use Mageants\Deleteaccount\Helper\Data;
use Magento\Framework\Controller\ResultFactory;

class CustomerRegister implements ObserverInterface
{
    public function __construct(
        Data $helper,
        \Mageants\Deleteaccount\Model\DeleteaccountFactory $deleteaccount
    ) {
        $this->_helper = $helper;
        $this->deleteaccount = $deleteaccount;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_helper->isEnableDeleteAccount() == 1) {
            $customeremail = $observer->getEvent()->getCustomer()->getEmail();
            $Model = $this->deleteaccount->create();
            $id = $Model->getCollection()->addFieldToFilter('customer_email', $customeremail)->addFieldToSelect('id')->getData();
            if (!empty($id)) {
                $customerid =  $id[0]['id'];
                $Model->unsetData();
                $Model->load($customerid);
                $Model->delete();
            }
        }
    }
}
