<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Controller\Index;

use Magento\Framework\Controller\Result\JsonFactory;

class Bundleoption extends \Magento\Framework\App\Action\Action
{
    protected $_registry;
    protected $_pageFactory;
    protected $_productloader;
    private $resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageants\PreOrder\Block\Preorder $preOrder,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->_registry = $registry;
        $this->_pageFactory = $pageFactory;
        $this->_productloader = $_productloader;
        $this->_preOrder = $preOrder;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }
    public function execute()
    {
        
        $preorder = 0;
        $optionid = [];
        $preorderdiv = [];
        $optionid = $this->getRequest()->getParam('id');
        $productid = $this->getRequest()->getParam('productid');
        $response_array = $this->resultJsonFactory->create();
        $bundleproduct = $this->_productloader->create()->load($productid);
        $selectionCollection = $bundleproduct->getTypeInstance()
            ->getSelectionsCollection(
                $bundleproduct->getTypeInstance()->getOptionsIds($bundleproduct),
                $bundleproduct
            );
        foreach ($selectionCollection as $selection) {
            foreach ($optionid as $val) {
                if ($selection->getSelectionId() == $val) {
                    $productname="";
                    $preorderstock = $this->_preOrder->getProductStockStatusById($selection->getId());
                    if ($preorderstock->getPreorderNote() !="") {
                        $preorder_note = $preorderstock->getPreorderNote();
                    } else {
                        $preorder_note = $this->_preOrder->getDefaultMessageForPreorder();
                    }
                    $preorder_date = $preorderstock->getPreorderAvailabilityDate();
                    if ($this->_preOrder->getACTIVE()) {
                        if ($preorderstock->getBackorders() == 4) {
                            if ($preorderstock->getBackstockPreorders() == 1) {
                                $productname=$selection->getName();
                                if ($preorderstock->getPreorderAvailabilityDate() != "") {
                                    $preorderdiv[] = "<div >$productname :: $preorder_note</div><div><span>AvailableOn :: </span>$preorder_date</div>";
                                } else {
                                    $preorderdiv[] = "<div >$productname :: $preorder_note</div>";
                                }
                                
                                $preorder++;
                            } elseif ($preorderstock->getBackstockPreorders() == 0) {
                                // if($this->_preOrder->getAlloweOutofproduct()){
                                    $productname=$selection->getName();
                                    
                                if ($preorderstock->getPreorderAvailabilityDate() != "") {
                                    $preorderdiv[] = "<div >$productname :: $preorder_note</div><div><span>AvailableOn :: </span>$preorder_date</div>";
                                } else {
                                    $preorderdiv[] = "<div >$productname :: $preorder_note</div>";
                                }
                                    $preorder++;
                                // }
                            }
                        }
                    }
                }
            }
             
        }
         
        if ($preorder > 0) {
            return $response_array->setData(['status' => 'preorder', 'preorder_message' => $preorderdiv ]);
        } else {
            $response_array['status'] ="";
            return $response_array->setData(['status' => 'come from json']);
        }
    }
}
