<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model\Instagram;

use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\CollectionFactory;
use Mageants\InstagramIntegration\Model\Instagram;

/**
 * Class DataProvider
 * @package Mageants\InstagramIntegration\Model\Instagram
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        /** @var Contact $instagram */
        foreach ($items as $instagram) {
            // our fieldset is called "instagram" or this table so that magento can find its datas:
            $temp = $instagram->getData();
            $img = [];
            $img[0]['name'] = $temp['insta_media_medium'];
            $img[0]['url'] = $temp['insta_media_medium'];
            $temp['insta_media_medium'] = $img;

            $instagram->setData($temp);

            $this->loadedData[$instagram->getId()]['instagram'] = $instagram->getData();
        }

        return $this->loadedData;
    }
}
