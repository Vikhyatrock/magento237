<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Controller\Adminhtml\Grid;
 
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Mageants\Productimage\Model\ResourceModel\Grid\CollectionFactory;
 
class Approve extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter.​_
     * @var Filter
     */
    protected $_filter;
 
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
 
    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Mageants\Productimage\Helper\Data $helper
    ) {
 
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
        parent::__construct($context);
    }
 
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $recordApproved = 0;
        $status = 1;
        $vars = [];
        foreach ($collection as $record) {
            $record->setStatus(true);
            $record->save();
            $product = $this->productRepository->get($record->getData('product_sku'));
            $vars['product_name'] = $product->getName();
            $vars['customer_email'] = $record->getData('customer_email');
            if ($record->getData('status') == 1) {
                $vars['status'] = 'approve';
            } else {
                $vars['status'] = 'reject';
            }
            if ($record->getData('customer_email') != null) {
                $this->helper->sendMail($vars);
            }
            $recordApproved++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been Approved.', $recordApproved));
 
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
