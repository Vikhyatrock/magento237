<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageants\StoreCredit\Model\StoreCredit;

/**
 * SaveStorecreditAmountToOrder class for store credit amount update on after prder place
 */
class SaveStorecreditAmountToOrder implements ObserverInterface
{
    /**
     * @var $logger,$storecreditfactory
     */
    protected $_logger, $_storecreditfactory;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Mageants\StoreCredit\Helper\Email $helperEmail
     * @param array $data
     */
    function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageants\StoreCredit\Helper\Email $helperEmail
    ) {
        $this->_logger = $logger;
        $this->_storecreditfactory = $storecreditfactory;
        $this->_checkoutSession=$checkoutSession;
        $this->_customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->helperemail = $helperEmail;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getData('order');
        $quote = $observer->getData('quote');
        $customer_id = $this->_customerSession->getCustomerId();
        $customer_name = $this->_customerSession->getCustomer()->getName();
        $customer_email = $this->_customerSession->getCustomer()->getEmail();
        
        if ($this->_checkoutSession->getStoreCreditbalance()!=''):
            $order->setStoreCreditAmount($this->_checkoutSession->getStoreCreditbalance());
            $order->save();
            
            $quote->setStoreCreditAmount($this->_checkoutSession->getStoreCreditbalance());
            $quote->save();
        endif;
        
        if (!empty($customer_id)) {
            //get old amount
            $old_balance = 0;
            $collection = $this->_storecreditfactory->create()->getCollection()
                       ->addFieldToSelect('new_balance')
                       ->addFieldToFilter('customer_id', $customer_id)
                       ->setOrder(
                           'id',
                           'DESC'
                       )
                        ->setPageSize(1)
                        ->getLastItem();
            $amount_data = $collection->getData();
            if (!empty($amount_data['new_balance'])) {
                $old_balance = $amount_data['new_balance'];
            }
            
            if ($old_balance != 0) {
                //add entry in store_credit table
                $balance_change = $this->_checkoutSession->getStoreCreditbalance();
                $storecreditmodel = $this->_storecreditfactory->create();

                $order = $observer->getEvent()->getOrder();
                $orderId = $order->getId();
                
                if (!empty($balance_change) && $balance_change != '0') {
                    $storecreditmodel->setName($data['customer_id'] = $customer_id);
                    $storecreditmodel->setName($data['order_id'] = $orderId);
                    $storecreditmodel->setName($data['comment'] = '');
                    $storecreditmodel->setName($data['balance_change'] = '-'.$balance_change);
                    $storecreditmodel->setName($data['action'] = '3');
                    $storecreditmodel->setName($data['add_balance'] = '0');
                    $storecreditmodel->setName($data['new_balance'] = $old_balance - $balance_change);
                    $storecreditmodel->setData($data);
                    $storecreditmodel->save();
                    
                    //Order paid store credit update mail send
                    $email_update_enable = $this->scopeConfig->getValue('store_credit/email_notification/email_enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $email_action = $this->scopeConfig->getValue('store_credit/email_notification/email_actions', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $email_send_mail = $this->scopeConfig->getValue('store_credit/email_notification/credit_send_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $sender_email = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $sender_name  = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    
                    $allow_action = explode(",", $email_action);
                    
                    if ($email_update_enable == '1') {
                        /* Receiver Detail  */
                        $receiverInfo = [
                            'name' => $customer_name,
                            'email' => $customer_email
                        ];

                        /* Sender Detail  */
                        $senderInfo = [
                            'name' => $sender_name,
                            'email' => $sender_email
                        ];

                        //sent mail for add balance
                        if (in_array('order_place', $allow_action)) {
                            
                            /* Assign values for your template variables  */
                            $emailTemplateVariables = [];
                            $emailTemplateVariables['customer_name'] = $customer_name;
                            $emailTemplateVariables['comment'] = '';
                            $emailTemplateVariables['balance_change'] = '-'.$balance_change;
                            $emailTemplateVariables['new_balance'] = $old_balance - $balance_change;
                            $emailTemplateVariables['action'] = 'Order Paid';
                        
                            $this->helperemail->StoreCreditMailSend(
                                $emailTemplateVariables,
                                $senderInfo,
                                $receiverInfo
                            );
                        }
                    }
                }
            }
        }
        //checkout session unset
        $this->_checkoutSession->unsStoreCreditbalance();
    }
}
