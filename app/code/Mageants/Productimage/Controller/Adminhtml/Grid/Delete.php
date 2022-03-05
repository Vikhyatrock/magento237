<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Controller\Adminhtml\Grid;

use Mageants\Productimage\Model\GridFactory;

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
        GridFactory $gridFactory
    ) {
        $this->gridFactory = $gridFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            $model = $this->gridFactory->create();
            $model->load($id);
            $model->delete();
            $this->messageManager->addSuccess(__('The Data Successfully Deleted!'));
            return $this->_redirect('*/*/index');
        } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
        }
    }
}
