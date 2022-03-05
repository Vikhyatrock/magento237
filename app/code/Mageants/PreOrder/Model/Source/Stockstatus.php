<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Stockstatus implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0,'label' => __('Use Config')],
            ['value' =>1,'label' => __('Yes')],
            ['value' => 2, 'label' => __('No')]
        ];
    }
}
