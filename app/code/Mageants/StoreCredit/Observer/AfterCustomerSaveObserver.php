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
 * AfterCustomerSaveObserver class for store credit amount update on after customer save
 */
class AfterCustomerSaveObserver implements ObserverInterface
{
    /**
     * @var $logger,$storecreditfactory,$scopeConfig
     */
    protected $_logger,$_storecreditfactory,$scopeConfig;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param \Mageants\StoreCredit\Helper\Email $helperEmail
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Mageants\StoreCredit\Helper\Email $helperEmail,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_logger = $logger;
        $this->_storecreditfactory = $storecreditfactory;
        $this->helperemail = $helperEmail;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //store credit data insert
        $model = $this->_storecreditfactory->create();
        $customer = $observer->getCustomer();
        $request  = $observer->getEvent()->getRequest();
        $postData = $request->getPostValue();
        $customer_id = $customer->getId();
        if ($postData['balance_change'] > 0) {
            $add_balance = '1';
            $action = '0';
        } else {
            $add_balance = '0';
            $action = '1';
        }
        $model->setCustomerId($data['customer_id'] = $customer_id);
        $model->setComment($data['comment'] = $postData['comment']);
        $model->setBalanceChange($data['balance_change'] = $postData['balance_change']);
        $model->setAction($data['action'] = $action);
        $model->setAddBalance($data['add_balance'] = $add_balance);
        $model->setNewBalance($data['new_balance'] = $postData['old_balance'] + $postData['balance_change']);
        $model->setData($data);
        $model->save();
        
        //customer store credit update mail send
        $email_update_enable = $this->scopeConfig->getValue('store_credit/email_notification/email_enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $email_action = $this->scopeConfig->getValue('store_credit/email_notification/email_actions', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $email_send_mail = $this->scopeConfig->getValue('store_credit/email_notification/credit_send_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender_email = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender_name  = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $allow_action = explode(",", $email_action);
        
        if ($email_update_enable == '1') {
            $customer_name = $customer->getFirstname().' '.$customer->getLastname();
            /* Receiver Detail  */
            $receiverInfo = [
                'name' => $customer->getFirstname(),
                'email' => $customer->getEmail()
            ];

            /* Sender Detail  */
            $senderInfo = [
                'name' => $sender_name,
                'email' => $sender_email
            ];

            //sent mail for add balance
            if (in_array('admin_add_from_balance', $allow_action) && $add_balance == '1') {
                
                /* Assign values for your template variables  */
                $emailTemplateVariables = [];
                $emailTemplateVariables['customer_name'] = $customer_name;
                $emailTemplateVariables['comment'] = $postData['comment'];
                $emailTemplateVariables['balance_change'] = $postData['balance_change'];
                $emailTemplateVariables['new_balance'] = $postData['old_balance'] + $postData['balance_change'];
                $emailTemplateVariables['action'] = 'Added by Admin';
            
                $this->helperemail->StoreCreditMailSend(
                    $emailTemplateVariables,
                    $senderInfo,
                    $receiverInfo
                );
            }
            if (in_array('admin_remove_from_balance', $allow_action) && $add_balance == '0') {
                
                /* Assign values for your template variables  */
                $emailTemplateVariables = [];
                $emailTemplateVariables['customer_name'] = $customer_name;
                $emailTemplateVariables['comment'] = $postData['comment'];
                $emailTemplateVariables['balance_change'] = $postData['balance_change'];
                $emailTemplateVariables['new_balance'] = $postData['old_balance'] + $postData['balance_change'];
                $emailTemplateVariables['action'] = 'Remove by Admin';
                
                $this->helperemail->StoreCreditMailSend(
                    $emailTemplateVariables,
                    $senderInfo,
                    $receiverInfo
                );
            }
        }
    }
}
