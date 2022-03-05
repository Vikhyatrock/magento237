<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Model\ZipcodeCod\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class CodStatus implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options=[
            ['label'=>'Yes', 'value'=>'1'],
            ['label'=>'No', 'value'=>'0'],
        ];
        return $options;
    }
}
