<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Controller\Index;

use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    public function __construct(PageFactory $resultPageFactory, \Magento\Framework\App\Action\Context $context)
    {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        // @codingStandardsIgnoreStart
        if ($this->getRequest()->getPostValue('categoryid')) {
            $resultPage = $this->_resultPageFactory->create();
            $block = $resultPage->getLayout()
                ->createBlock('Magento\Framework\View\Element\Template')
                ->setTemplate('Mageants_ImageGallery::gallery/result.phtml')
                ->toHtml();
            echo $block;
            $this->_view->loadLayout();
            $this->_view->renderLayout();
            $this->_view->loadLayoutUpdates();
            exit;
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        $this->_view->loadLayoutUpdates();
        // @codingStandardsIgnoreEnd
    }
}
