<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Model;

use Magento\Store\Model\StoreManagerInterface;
use Mageants\Productimage\Model\ResourceModel\Grid\CollectionFactory;
use Mageants\Productimage\Model;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

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
            if ($temp['image']):
                $img = [];
                $img[0]['name'] = $temp['image'];
                $img[0]['url'] = $baseurl.$temp['image'];
                $temp['image'] = $img;
            endif;
            $data = $this->dataPersistor->get('mageants_productimage_bycustomer');
            if (!empty($data)) {
                $page = $this->collection->getNewEmptyItem();
                $page->setData($data);
                $this->loadedData[$page->getId()] = $page->getData();

                $this->dataPersistor->clear('mageants_productimage_bycustomer');
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
