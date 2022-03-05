<?php
/**
 * @category Mageants DeleteAccount
 * @package Mageants_DeleteAccount
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Deleteaccount\Controller\Index;

use Mageants\Deleteaccount\Helper\Data;
use Magento\Framework\Controller\ResultFactory;

class Deleteconfirm extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    protected $_Session;
    protected $_helper;
    protected $_resultPageFactory;
    protected $inlineTranslation;
    protected $customerFactory;
    protected $request;
    /**
     * @param Context $context
     *
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Data $helper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Mageants\Deleteaccount\Model\DeleteaccountFactory $deleteaccount,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_helper = $helper;
        $this->registry = $registry;
        $this->session = $session;
        $this->inlineTranslation = $inlineTranslation;
        $this->customerFactory  = $customerFactory;
        $this->deleteaccount = $deleteaccount;
        $this->request = $request;
        $this->date = $date;
        parent::__construct($context);
    }
    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            $id = $this->session->getId();
        } else {
            $id = base64_decode($this->request->getParam('id'));
        }
        $customer = $this->customerFactory->create()->load($id);
        $Model = $this->deleteaccount->create();
        $name = $customer->getData('firstname')." ".$customer->getData('lastname');
        $email= $customer->getData('email');
        if ($customer->getData('group_id') == 1) {
            $group = "General";
        } elseif ($customer->getData('group_id') == 2) {
            $group = "Wholesale";
        } else {
            $group = "Retailer";
        }
        $customerSince = $customer->getData('created_at');
        $website = "Main Website";
        $createdIn = $customer->getData('created_in');
        $deletedAt = $this->date->date();
        if ($customer->getData('email')) {
            $Model->setData('name', $name);
            $Model->setData('customer_email', $email);
            $Model->setData('group', $group);
            $Model->setData('customer_since', $customerSince);
            $Model->setData('account_deleted', $deletedAt);
            $Model->setData('account_created_in', $createdIn);
            $Model->setData('website', $website);
            $Model->save();
        }
        $this->inlineTranslation->suspend();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($this->_helper->isEnableDeleteAccount() != 1) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
        if ($this->session->isLoggedIn()) {
            $id = $this->session->getId();
            $this->registry->register('isSecureArea', true);
            $customer = $this->customerFactory->create()->load($id);
            $this->session->logout();
            if ($customer->getId() == $id) {
                if ($customer->delete()) {
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setPath('customer/account/login');
                    $this->inlineTranslation->resume();
                    $this->messageManager->addSuccess(
                        __('Your Account Has Been Deleted. Create an Account to Login Again')
                    );
                } else {
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setPath('customer/account/login');
                    $this->inlineTranslation->resume();
                    $this->messageManager->addError(__('We can\'t process your request right now. Sorry'));
                }
            }
            return $resultRedirect;
        } else {
            $id = base64_decode($this->request->getParam('id'));
            $this->registry->register('isSecureArea', true);
            $customer = $this->customerFactory->create()->load($id);
            if ($customer->getId() == $id) {
                $resultPage = $this->_resultPageFactory->create();
                if ($customer->delete()) {
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setPath('customer/account/login');
                    $this->inlineTranslation->resume();
                    $this->messageManager->addSuccess(
                        __('Your Account Has Been Deleted. Create an Account to Login Again')
                    );
                    return $resultRedirect;
                } else {
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setPath('customer/account/login');
                    $this->inlineTranslation->resume();
                    $this->messageManager->addError(__('We can\'t process your request right now. Sorry'));
                    return $resultRedirect;
                }
            } else {
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('customer/account/login');
                $this->inlineTranslation->resume();
                $this->messageManager->addSuccess(
                    __('Your Account Has Been Deleted. Create an Account to Login Again')
                );
                return $resultRedirect;
            }
        }
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_Deleteaccount::deleteaccount');
    }
}
