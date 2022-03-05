<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Controller\Adminhtml\Backend;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Mageants\FreeShippingBar\Model\ResourceModel\FreeShippingBar\CollectionFactory;

class Enable extends \Magento\Backend\App\Action
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
        \Magento\Framework\Stdlib\DateTime\Timezone $datetime
    ) {
        $this->_filter = $filter;
        $this->_datetime = $datetime;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
 
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $date = $this->_datetime->date();
        $recordApproved = 0;
        $status = 1;
        foreach ($collection as $record) {
            if ($record->getTodate() >= $date->format('Y-m-d H:i:s')) {
                $record->setStatus(true);
                $record->save();
                $recordApproved++;
            } else {
                $this->messageManager->addError(__('%1 Is Can not able to Enable Because it is Out of Date Please
                 increase Date To Enable', $record->getName()));
            }
        }
        $this->messageManager->addSuccess(__('The %1 record(s) have been Enabled.', $recordApproved));
        
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
