<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\CSPM\Helper;
/**
 * Data class for helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Group collection
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_groupcollection;

    /**
     * Group collection
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\website
     */
    protected $_website;

    /**
     * Shipping All Method
     *
     * @var \Mageants\CSPM\Model\Config\Source\Allmethods
     */
    protected $_allmethod;
    
    /**
     * Payment Method
     *
     * @var \Mageants\CSPM\Model\Config\Source\PaymentMethod
     */
    protected $_paymentMethod;

    /**
     * @param \Mageants\Faq\Model\Category $faqCollection
     */
    public function __construct(
      \Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection,
      \Mageants\CSPM\Model\Config\Source\Allmethods $Allmethods,
      \Mageants\CSPM\Model\Config\Source\PaymentMethod $paymentMethod,
      \Mageants\CSPM\Model\Config\Source\Website $website
    ) {     
      $this->_paymentMethod=$paymentMethod;
      $this->_allmethod=$Allmethods;
      $this->_groupcollection=$groupCollection;
      $this->_website=$website;
      
    }  

  /**
   * Prepare Option Array 
   *
   * @return Array
   */
  public function getCgid()
  {
  $groupOptions = $this->_groupcollection->toOptionArray();
  foreach ($groupOptions as $group)
      {
    $ret[] = [
              'value' => $group['value'],
              'label' => $group['label']
          ];
      }
  return $ret;
  }

  /**
   * Return all Shipping Method
   */
  public function getShippingMethod()
  {
     return $this->_allmethod->toOptionArray();

  }

  /**
   * return all enable payment method
   */
  public function getPaymentMethod()
  {
    return $this->_paymentMethod->toOptionArray();
  }

  /**
   * return all enable payment method
   */
  public function getWebsite()
  {
    return $this->_website->toOptionArray();
  }

}
