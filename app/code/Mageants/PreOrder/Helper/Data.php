<?php
 /**
  * @category Mageants PreOrder
  * @package Mageants_PreOrder
  * @copyright Copyright (c) 2018  Mageants
  * @author Mageants Team <support@mageants.com>
  */
namespace Mageants\PreOrder\Helper;

/**
 * Data class for Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ACTIVE = 'mageants_preorder/general/active';
    const Change_Status = 'mageants_preorder/general/change_status';
    const Allowe_Outofproduct = 'mageants_preorder/general/allowe_outofproduct_for_preorder';
    const Restrict_Order = 'mageants_preorder/general/restrict_order';
    const Cart_Button_Text = 'mageants_preorder/display_options/add_to_cart_button_text';
    const Default_Message_For_Preorder = 'mageants_preorder/display_options/default_message_for_preorder';
    const Note_For_Preorder_Incart = 'mageants_preorder/display_options/note_for_preorder_incart';
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @return int
     */
    public function getACTIVE()
    {
        return $this->scopeConfig->getValue(
            self::ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getChangeStatus()
    {
        return $this->scopeConfig->getValue(
            self::Change_Status,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getAlloweOutofproduct()
    {
        return $this->scopeConfig->getValue(
            self::Allowe_Outofproduct,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getRestrictOrder()
    {
        return $this->scopeConfig->getValue(
            self::Restrict_Order,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

     /**
      * @return String
      */
    public function getCartButtonText()
    {
        return $this->scopeConfig->getValue(
            self::Cart_Button_Text,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return String
     */
    public function getDefaultMessageForPreorder()
    {
        return $this->scopeConfig->getValue(
            self::Default_Message_For_Preorder,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return String
     */
    public function getNoteForPreorderIncart()
    {
        return $this->scopeConfig->getValue(
            self::Note_For_Preorder_Incart,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
