<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Observer;
use Magento\Framework\Event\ObserverInterface;
use Exception;

/**
 * check payment method for current user
 */
class PaymentActive implements ObserverInterface 
{
    /**
     * customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    
    /**
     * csmp Model
     *
     * @var \Mageants\CSPM\Model\Cspm
     */
    protected $_cspmModel;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Mageants\CSPM\Model\Cspm $cspmModel
     */
    public function __construct (
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Mageants\CSPM\Model\Cspm $cspmModel
    ) {
        $this->customerSession = $customerSession;
        $this->_cspmModel=$cspmModel;
        $this->storeManager = $storeManager;
    }

    /**
     * check payment method is available for customer or not
     */
    public function execute(\Magento\Framework\Event\Observer $observer) 
    {
        $event           = $observer->getEvent();
	    $method          = $event->getMethodInstance();
        
        $result          = $event->getResult();
        $currencyCode    = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $groupId = $this->customerSession->getCustomerGroupId();
        if ($this->customerSession->isLoggedIn()) {
            $groupId = $this->customerSession->getCustomer()->getGroupId();
        }
        $webId=$this->storeManager->getStore()->getId();
        $storeView=array();
        $storeView[0]="0";
        $storeView[1]=$webId;
        $collection=$this->_cspmModel->getCollection()
                    ->addFieldToFilter("cgid",$groupId)
                    ->addFieldToFilter("cstatus","Enable")
                    ->addFieldToFilter("website",array('in' => $storeView));
        $set=0;
        $check=0;
        if(sizeof($collection) > 0)
        {
            foreach ($collection->getData() as $item) {
                if($item['pmethod']!=="0")
                {
                    $check=1;
                    $paymentArray = explode(',', $item['pmethod']);
			        foreach ($paymentArray as $payment) {
			            if($method->getCode() == $payment){
                            $set=1;
                        }
                    }
                }
            }
        }

        if($check==1)
        {
            if($set==1){$result->setData('is_available', true);}else{$result->setData( 'is_available', false);}    
        }
        else
        {
         $result->setData( 'is_available', true);   
        }
        
    }
}
