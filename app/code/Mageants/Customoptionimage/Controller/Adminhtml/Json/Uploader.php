<?php
namespace Mageants\Customoptionimage\Controller\Adminhtml\Json;
 
use Magento\Framework\App\Action\Context;
 
class Uploader extends \Magento\Framework\App\Action\Action //\Magento\Backend\App\Action 
{
    private $imageSaving;

    private $resultJsonFactory;

    public function __construct(
        Context $context,
        \Mageants\Customoptionimage\Helper\ImageSaving $imageSaving,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->imageSaving = $imageSaving;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        if ($this->getRequest()->isAjax()) {
            $param = $this->getRequest()->getParams();
            $result = $this->imageSaving->saveTemporaryImage($param['option_sortorder'], $param['value_sortorder']);
            return $resultJson->setData($result);
        } else {
            return $resultJson->setData(null);
        }
    }
}
