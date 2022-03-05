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

/*use Magento\Framework\App\Action\Context;*/
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    /**
    * Recipient email config path
    */
    const XML_PATH_EMAIL_SENDER = 'deleteaccount/general/identity';
    /**
    * @var \Magento\Framework\Mail\Template\TransportBuilder
    */
    protected $_transportBuilder;

    /**
    * @var \Magento\Framework\Translate\Inline\StateInterface
    */
    protected $inlineTranslation;

    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    /**
    * @var \Magento\Store\Model\StoreManagerInterface
    */
    protected $storeManager;
    /**
    * @var \Magento\Framework\Escaper
    */
    protected $_escaper;

    /**
    * @param \Magento\Framework\App\Action\Context $context
    * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
    * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param \Magento\Store\Model\StoreManagerInterface $storeManager
    */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Data $helper
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->_helper = $helper;
    }

    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->_helper->isEnableDeleteAccount() != 1) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerid = $customerSession->getCustomer()->getId();
            $customername = $customerSession->getCustomer()->getName();
            $customeremail = $customerSession->getCustomer()->getEmail();
            $post = $this->getRequest()->getPostValue();
            if ($post) {
                $this->inlineTranslation->suspend();
                try {
                    $url = $this->storeManager->getStore()->getUrl('deleteaccount/index/deleteconfirm/', ['id'=> base64_encode($customerid)]);
                    $postObject = new \Magento\Framework\DataObject();
                    $postObject->setData($post);
                    $error = false;
                    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                    $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('deleteaccount_general_template')
                    ->setTemplateOptions(
                        [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                     ->setTemplateVars(['data' => $postObject,
                    'customername' => $customername,
                    'message'   => 'You have Received This E-mail because 
                    we\'ve been notified to delete your account from Default. After deleting your account, you will permanently loose your account and order confirmation stored in Our Default',
                    'customer_url' => $url
                     ])
                    ->setFrom('general')
                    ->addTo($customeremail)
                    ->getTransport();
                    $transport->sendMessage();
                    $this->inlineTranslation->resume();
                    $this->messageManager->addSuccess(
                        __('Please Check Your Registered Email Id to Confirm and delete Your account permanently.')
                    );
                } catch (\Exception $e) {
                    $this->inlineTranslation->resume();
                    $this->messageManager->addError(
                        __('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
                    );
                }
            }
            $resultPage = $this->_resultPageFactory->create();
            $this->_view->loadLayout();
            $this->_view->renderLayout();
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_Deleteaccount::deleteaccount');
    }
}
