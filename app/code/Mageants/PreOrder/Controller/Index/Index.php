<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageants\PreOrder\Block\Preorder $preOrder,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_preOrder = $preOrder;
        return parent::__construct($context);
    }
    public function execute()
    {
        if ($this->_preOrder->getACTIVE()) {
            $preorder = 0;
            // $product_id=2052;
            $product_id = $this->getRequest()->getParam('id');
            $preorderstock = $this->_preOrder->getProductStockStatusById($product_id);
            if ($preorderstock->getPreorderNote() !="") {
                $preorder_note = $preorderstock->getPreorderNote();
            } else {
                $preorder_note = $this->_preOrder->getDefaultMessageForPreorder();
            }
            
            $preorder_date = $preorderstock->getPreorderAvailabilityDate();
            if ($preorderstock->getBackorders() == 4) {
                if ($preorderstock->getBackstockPreorders() == 1) {
                        $preorder++;
                } elseif ($preorderstock->getBackstockPreorders() == 0) {
                    // if ($this->_preOrder->getAlloweOutofproduct()) {
                    echo $preorderstock->getIsInStock();
                    if ($this->_preOrder->getAlloweOutofproduct() == 1) {
                        $preorder++;
                    }
                    if ($this->_preOrder->getAlloweOutofproduct() == 0 ) {
                        $response_array['status'] ="";
                    }
                } elseif ($preorderstock->getBackstockPreorders() == 2) {
                    if ($preorderstock->getIsInStock() == 1) {
                        $preorder++;
                    } else {
                        $response_array['status'] ="";
                    }
                }
            } else {
                $response_array['status'] ="";
            }
            if ($preorder > 0) {
                $response_array['status'] ="preorder";
                if ($preorderstock->getPreorderAvailabilityDate() != "") {
                        
                    $response_array['preorder_message'] = "<div class='preorder_info'>$preorder_note</div><div class='preorder_date'><span>AvailableOn :: </span>$preorder_date</div>";
                } else {
                    $response_array['preorder_message'] = "<div class='preorder_info'>$preorder_note</div>";
                }
            }
        } else {
            $response_array['status'] ="";
        }
        echo json_encode($response_array);
        exit();
    }
}
