<?php 
namespace Mageants\MinimumMaximumQuantity\Controller\Index;
use Mageants\MinimumMaximumQuantity\Model\InsertQuantityFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;
class Index extends \Magento\Framework\App\Action\Action{
    protected $_insertQuantity;
    protected $resultRedirect;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageants\MinimumMaximumQuantity\Model\InsertQuantityFactory  $insertQuantity,
        \Magento\Framework\Controller\ResultFactory $result
    ){
        parent::__construct($context);
        $this->_insertQuantity = $insertQuantity;
        $this->resultRedirect = $result;
    }
	public function execute(){
        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
		$model = $this->_insertQuantity->create();
		$model->addData([
			"p_id" => 1,
			"min_qty" => 20,
			"max_qty" => 40
			
			]);
        $saveData = $model->save();
        if ($saveData) {
            echo 'Insert Record Successfully !';
            exit();
        }
        if($saveData){
            $this->messageManager->addSuccess( __('Insert Record Successfully !') );
        }
		return $resultRedirect;
	}
}

/*class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    protected $_insertQuantityFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Mageplaza\HelloWorld\Model\InsertQuantityFactory $insertQuantityFactory
        )
    {
        $this->_pageFactory = $pageFactory;
        $this->_insertQuantityFactory = $insertQuantityFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $insert = $this->_insertQuantityFactory->create();
        $collection = $insert->getCollection();
        foreach($collection as $item){
            echo "<pre>";
            print_r($item->getData());
            echo "</pre>";
        }
        exit();
        return $this->_pageFactory->create();
    }
}*/
 ?>