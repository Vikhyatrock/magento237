<?php
namespace Mageants\Customoptionimage\Controller\json;
 
use Magento\Framework\App\Action\Context;
 
class UrlData extends \Magento\Framework\App\Action\Action
{
    private $coiData;

    private $resultJsonFactory;

    public function __construct(
        Context $context,
        \Mageants\Customoptionimage\Helper\Data $coiData,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->coiData = $coiData;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        if ($this->getRequest()->isAjax()) {
            $productId = $this->getRequest()->getParam('productId');
            $result = $this->coiData->getUrlData($productId);
            return $resultJson->setData($result);
        } else {
            return $resultJson->setData(null);
        }
    }
}
