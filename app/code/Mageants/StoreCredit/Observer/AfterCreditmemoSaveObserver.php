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
 * AfterCreditmemoSaveObserver class for store credit amount update on after creditmemo save
 */
class AfterCreditmemoSaveObserver implements ObserverInterface
{
    /**
     * @var $logger,$storecreditfactory
     */
    protected $_logger, $_storecreditfactory;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Mageants\StoreCredit\Helper\Email $helperEmail
     * @param array $data
     */
    function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageants\StoreCredit\Helper\Email $helperEmail
    ) {
        $this->_logger = $logger;
        $this->_storecreditfactory = $storecreditfactory;
        $this->creditmemoLoader = $creditmemoLoader;
        $this->scopeConfig = $scopeConfig;
        $this->helperemail = $helperEmail;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getCreditmemo()->getOrder();
        $order_id = $order->getId();
        $customer_id = $order->getCustomerId();
        $customer_name = $order->getCustomerFirstname().' '.$order->getCustomerLastname();

        //get old amount
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
        } else {
            $old_balance = 0;
        }

        $storecreditmodel = $this->_storecreditfactory->create();

        $creditmemo_data = $this->creditmemoLoader->getCreditmemo();

        if (array_key_exists("store_credit_return_amount", $creditmemo_data)) {
            $balance_change = $creditmemo_data['store_credit_return_amount'];
        } else {
            $balance_change = 0;
        }

        if (!empty($balance_change)) {
            $storecreditmodel->setName($data['customer_id'] = $customer_id);
            $storecreditmodel->setName($data['order_id'] = $order_id);
            $storecreditmodel->setName($data['comment'] = '');
            $storecreditmodel->setName($data['balance_change'] = $balance_change);
            $storecreditmodel->setName($data['action'] = '2');
            $storecreditmodel->setName($data['add_balance'] = '1');
            $storecreditmodel->setName($data['new_balance'] = $old_balance + $balance_change);
            $storecreditmodel->setData($data);
            $storecreditmodel->save();
            
            //Refunded store credit update mail send
            $email_update_enable = $this->scopeConfig->getValue('store_credit/email_notification/email_enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $email_action = $this->scopeConfig->getValue('store_credit/email_notification/email_actions', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $email_send_mail = $this->scopeConfig->getValue('store_credit/email_notification/credit_send_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $sender_email = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $sender_name  = $this->scopeConfig->getValue('trans_email/ident_'.$email_send_mail.'/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            
            $allow_action = explode(",", $email_action);
            
            if ($email_update_enable == '1') {
                /* Receiver Detail  */
                $receiverInfo = [
                    'name' => $order->getCustomerFirstname(),
                    'email' => $order->getCustomerEmail()
                ];

                /* Sender Detail  */
                $senderInfo = [
                    'name' => $sender_name,
                    'email' => $sender_email
                ];

                //sent mail for add balance
                if (in_array('order_refund', $allow_action)) {
                    
                    /* Assign values for your template variables  */
                    $emailTemplateVariables = [];
                    $emailTemplateVariables['customer_name'] = $customer_name;
                    $emailTemplateVariables['comment'] = '';
                    $emailTemplateVariables['balance_change'] = $balance_change;
                    $emailTemplateVariables['new_balance'] = $old_balance + $balance_change;
                    $emailTemplateVariables['action'] = 'Refunded';
                
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
