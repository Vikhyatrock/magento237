<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model;

use Magento\Store\Model\StoreManagerInterface;
use Mageants\FreeShippingBar\Model\ResourceModel\FreeShippingBar\CollectionFactory;
use Mageants\FreeShippingBar\Model;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    /**
     * @param string $name
     **/
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $CollectionFactory,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $CollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager=$storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $baseurl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $contact) {
            $temp = $this->_loadedData[$contact->getId()] = $contact->getData();
            if ($temp['image']) {
                $img = [];
                $img[0]['name'] = $temp['image'];
                $img[0]['url']  = $baseurl.'theme/images/'.$temp['image'];
                $temp['image']  = $img;
            }
            $data = $this->dataPersistor->get('mageants_freeshippingbar');
            if (!empty($data)) {
                $page = $this->collection->getNewEmptyItem();
                $page->setData($data);
                $this->loadedData[$page->getId()] = $page->getData();

                $this->dataPersistor->clear('mageants_freeshippingbar');
            } else {
                if ($contact->getData('image') != null) {
                    $parseData[$contact->getId()] = $temp;
                    return $parseData;
                } else {
                    return $this->_loadedData;
                }
            }
        }
        return $this->_loadedData;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl;
    }
}
