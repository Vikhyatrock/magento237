<?php 
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model;
use Magento\Framework\Model\AbstractModel;

/*
 * Csmp Model
 */
class Cspm extends AbstractModel
{  
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageants\CSPM\Model\ResourceModel\Cspm');
    }
}