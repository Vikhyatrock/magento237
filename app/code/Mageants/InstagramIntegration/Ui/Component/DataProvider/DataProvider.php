<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Ui\Component\DataProvider;

/**
 * Class Collection
 * @package Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Grid
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    protected function prepareUpdateUrl()
    {
        if (!isset($this->data['config']['filter_url_params'])) {
            return;
        }
        foreach ($this->data['config']['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->request->getParam($paramName);
            }
            $this->data['config']['update_url'] = sprintf(
                '%s%s/%s/',
                $this->data['config']['update_url'],
                'store',
                $this->request->getParam('store')
            );
            if ($paramValue) {
                $this->data['config']['update_url'] = sprintf(
                    '%s%s/%s',
                    $this->data['config']['update_url'],
                    $paramName,
                    $paramValue
                );
                if ($paramName != 'store') {
                    $this->addFilter(
                        $this->filterBuilder->setField($paramName)->setValue($paramValue)->setConditionType('eq')->create()
                    );
                }
            }
        }
    }
}
