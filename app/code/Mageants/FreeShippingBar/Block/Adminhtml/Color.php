<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Block\Adminhtml;

class Color extends \Magento\Framework\Data\Form\Element\Text
{
    /**
     * constructor
     *
     * @param \Rcoktechnolabs\Advancesizechart\Model\ResourceModel\Image $imageModel
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        array $data
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        
        $this->setType('color');
        
        $this->setExtType('color');
    }
}
