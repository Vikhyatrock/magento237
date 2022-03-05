<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Model\Import;

/*
 * for validate title
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
  
    const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
    const ERROR_TITLE_IS_EMPTY = 'EmptyTITLE';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}
