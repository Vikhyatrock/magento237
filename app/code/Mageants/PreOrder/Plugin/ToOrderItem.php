<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Plugin;
 
use Magento\Quote\Model\Quote\Item\ToOrderItem as QuoteToOrderItem;
use Magento\Framework\Serialize\SerializerInterface;
 
class ToOrderItem
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * aroundConvert
     *
     * @param QuoteToOrderItem $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param array $data
     *
     * @return \Magento\Sales\Model\Order\Item
     */
    public function aroundConvert(
        QuoteToOrderItem $subject,
        \Closure $proceed,
        $item,
        $data = []
    ) {
        // Get Order Item
        $orderItem = $proceed($item, $data);
        
        // Get Quote Item's additional Options
        $additionalOptions = $item->getOptionByCode('additional_options');
        
        // Check if there is any additional options in Quote Item
        if (isset($additionalOptions)) {
        // if (is_array($additionalOptions) || $additionalOptions instanceof Countable)
        // Get Order Item's other options
            $options = $orderItem->getProductOptions();
        
           // Set additional options to Order Item
            $options['additional_options'] = $this->serializer->unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
        
        return $orderItem;
    }
}
