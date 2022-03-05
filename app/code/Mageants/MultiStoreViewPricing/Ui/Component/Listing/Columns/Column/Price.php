<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\MultiStoreViewPricing\Ui\Component\Listing\Columns\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * @api
 * @since 100.0.2
 */
class Price extends \Magento\Catalog\Ui\Component\Listing\Columns\Price
{
    /**
     * Column name
     */
    const NAME = 'column.price';

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    public $localeCurrency;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;

        $filterParams =$this->context->getFiltersParams();
        if (isset($filterParams['store_id'])) {
            $storeId = $filterParams['store_id'];
        }

        if (isset($dataSource['data']['items'])) {
            $store = $this->storeManager->getStore(
                $this->context->getFilterParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            );
            $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());
            $fieldName = $this->getData('name');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $productCollection= $objectManager
                        ->create(\Mageants\MultiStoreViewPricing\Model\Pricing::class)
                        ->getCollection()
                        ->addFieldToFilter('entity_id', $item['entity_id'])
                        ->addFieldToFilter('store_id', $storeId);
                    if (empty($productCollection->getData())) {
                        $productCollection= $objectManager
                            ->create(\Mageants\MultiStoreViewPricing\Model\Pricing::class)
                            ->getCollection()
                            ->addFieldToFilter('entity_id', $item['entity_id'])
                            ->addFieldToFilter('store_id', 0);
                    }

                    if ($productCollection->getFirstItem()->getPrice() != "") {
                        $item[$fieldName] = $productCollection->getFirstItem()->getPrice();
                    }

                    if (isset($item[$fieldName])) {
                        $item[$fieldName] = $currency->toCurrency(sprintf("%f", $item[$fieldName]));
                    }
                }
            }
        }

        return $dataSource;
    }
}
