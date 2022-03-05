<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Ui\Component\Listing\Column;
 

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
 
/**
 * Shows customer group name in admin grids instead of group id
 */
class Group extends Column
{
    /**
     * customer Group
     *
     * @var \Magento\Customer\Model\Group
     */
    protected $customerGroup;
    
    /**
     * Constructor
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param \Magento\Customer\Model\Group $customerGroup
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        \Magento\Customer\Model\Group $customerGroup,
        array $data = []
    ) {
        $this->customerGroup=$customerGroup;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) { 
                $groupName=$this->customerGroup->load((int)$item[$this->getData('name')])->getCustomerGroupCode();
                $item[$this->getData('name')] = $groupName;
            }
        }
 
        return $dataSource;
    }
}