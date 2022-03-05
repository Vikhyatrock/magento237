<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Grid;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;
use Magento\Directory\Model\Currency;
use Magento\Store\Model\StoreManagerInterface as storeManager;
use Magento\Framework\ObjectManagerInterface;
use Mageants\StoreCredit\Model\ResourceModel\StoreCredit\CollectionFactory as StoreCreditCollection;

/**
 * Grid class for fetch store credit data for Grid
 */
class Grid extends Extended
{
    protected $registry;
    protected $currency;
    protected $storeManager;
    protected $_objectManager = null;
    protected $storecreditFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Registry $registry,
        ObjectManagerInterface $objectManager,
        Currency $currency,
        StoreManager $storeManager,
        StoreCreditCollection $storecreditFactory,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->currency = $currency;
        $this->_storeManager = $storeManager;
        $this->registry = $registry;
        $this ->storecreditFactory = $storecreditFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    public function getCustomerId()
    {
        return $this->registry->registry(\Magento\Customer\Controller\RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    protected function _prepareCollection()
    {
        $id = $this->getRequest()->getParam('id');
        $data = $this->storecreditFactory->create()
                     ->addFieldToSelect('*');
        $data->addFieldToFilter('customer_id', $id);
        $this->setCollection($data);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('Customer Transaction ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'sortable'  => true,
            ]
        );
        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'options',
                'index' => 'action',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'sortable'  => true,
                'renderer'  => 'Mageants\StoreCredit\Block\Adminhtml\Grid\Renderer\Status',
                'options' => ["0" => "Added by Admin", "1" => "Removed by Admin", "2" => "Refunded", "3" => "Order Paid", "4" => "Order Canceled"],
            ]
        );
        $this->addColumn(
            'comment',
            [
                'header' => __('Comment'),
                'type' => 'text',
                'index' => 'comment',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'sortable'  => true,
            ]
        );
        $this->addColumn(
            'balance_change',
            [
                'header' => __('Balance Change'),
                'type' => 'price',
                'index' => 'balance_change',
                'header_css_class' => 'col-id',
                'renderer'  => 'Mageants\StoreCredit\Block\Adminhtml\Grid\Renderer\Balancechange',
                'currency_code' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
                'sortable'  => true,
            ]
        );
        $this->addColumn(
            'new_balance',
            [
                'header' => __('New Balance'),
                'type' => 'price',
                'index' => 'new_balance',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'currency_code' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
                'sortable'  => true,
            ]
        );
        $this->addColumn(
            'created_at',
            [
                'header' => __('Transaction Date'),
                'index' => 'created_at',
                'type' => 'datetime',
                'sortable'  => true,
            ]
        );
        return parent::_prepareColumns();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('storecredit/index/grid', ['_current' => true]);
    }

    public function getTabUrl()
    {
        return $this->getUrl('storecredit/index/storecreditgrid', ['_current' => true]);
    }
    
    public function getRowUrl($row)
    {
        return '';
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return true;
    }
    
    public function isAjaxLoaded()
    {
        return true;
    }

    public function getTabLabel()
    {
        return __('Store Credit');
    }

    public function getTabTitle()
    {
        return __('Store Credit');
    }
}
