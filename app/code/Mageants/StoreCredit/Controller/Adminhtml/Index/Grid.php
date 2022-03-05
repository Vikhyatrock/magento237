<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

/**
 * Grid class for adminhtml grid
 */
class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    private $resultLayoutFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    public function _isAllowed()
    {
        return true;
    }

    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $gridBlock = $resultLayout->getLayout()->getBlock('storecredit.edit.tab');
        
        return $resultLayout;
    }
}
