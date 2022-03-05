<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Controller\Adminhtml\Backend;

use Mageants\FreeShippingBar\Model\FreeShippingBarFactory;

class Delete extends \Magento\Framework\App\Action\Action
{

    /**
     * @var CommentFactory
     */
    protected $_commentFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param CommentFactory $commentFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        FreeShippingBarFactory $freeshippingbarfactory
    ) {
        $this->freeshippingbarfactory = $freeshippingbarfactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            $model = $this->freeshippingbarfactory->create();
            $model->load($id);
            $model->delete();
            $this->messageManager->addSuccess(__('The Data Successfully Deleted!'));
            return $this->_redirect('*/*/index');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
