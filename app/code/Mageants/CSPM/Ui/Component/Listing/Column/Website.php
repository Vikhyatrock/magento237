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
use \Magento\Store\Model\StoreRepository;
/**
 * Shows customer group name in admin grids instead of group id
 */
class Website extends Column
{
    /**
     * customer Group
     *
     * @var \Magento\Customer\Model\Group
     */
    protected $storeRepository;
    
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
        StoreRepository $storeRepository,
        array $data = []
    ) {
        $this->storeRepository=$storeRepository;
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
                $webName=$this->storeRepository->getById((int)$item[$this->getData('name')])->getName();
                $item[$this->getData('name')] = $webName;
            }
        }
 
        return $dataSource;
    }
}