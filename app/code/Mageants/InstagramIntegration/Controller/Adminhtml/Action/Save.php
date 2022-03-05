<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Action;

use Mageants\InstagramIntegration\Model\InstagramFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Save
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Action
 */
class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param ManagerInterface $messageManager
     * @param InstagramFactory  $instagramFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        InstagramFactory $instagramFactory
    ) {
        parent::__construct($context);
        $this->_instagramFactory = $instagramFactory;
        $this->_messageManager = $context->getMessageManager();
        $this->_resultFactory = $context->getResultFactory();
    }

    /**
     * Image Information save controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParam('insta');
        
        $model = $this->_instagramFactory->create();
        $modelupdate=$model->load($data['id']);
        $modelupdate->setData($data);
        $savedata=$model->save();

        $this->_messageManager->addSuccessMessage('Image Information successfully saved');

        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('instagramintegrationadmin/imagemanager/imagemanager');

        return $resultRedirect;
    }
}
