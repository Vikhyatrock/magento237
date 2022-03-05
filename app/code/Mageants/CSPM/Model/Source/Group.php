<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\Source;
use Magento\Framework\Option\ArrayInterface;

class Group implements ArrayInterface
{
    
    /**
     * @return Array
     */  
    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $groups = $objectManager->get('\Magento\Customer\Model\ResourceModel\Group\Collection')->toOptionArray();

        
        
        foreach($groups as $key=>$group){
            $options[$key]=['label'=>$group['label'],'value'=>$group['value']];
        }
        return $options;
    }
}