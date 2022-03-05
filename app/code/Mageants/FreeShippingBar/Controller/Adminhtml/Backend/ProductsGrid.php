<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\FreeShippingBar\Controller\Adminhtml\Backend;

class ProductsGrid extends \Magento\Catalog\Controller\Adminhtml\Product
{

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    public $resultLayoutFactory;

    /**
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function _isAllowed()
    {
        return true;
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('freeshippingbar.freeshippingbar.edit.tab.product')
        ->setInProducts($this->getRequest()->getPost('freeshippingbar_products', null));
        return $resultLayout;
    }
}
