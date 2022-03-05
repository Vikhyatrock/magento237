<?php
/**
 * @category Mageants DeleteAccount
 * @package Mageants_DeleteAccount
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Deleteaccount\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Mageants\Deleteaccount\Api\Data\GridInterface;

class Deleteaccount extends \Magento\Framework\Model\AbstractModel implements IdentityInterface, GridInterface
{
    const CACHE_TAG = 'mageants_deleteaccount';
    protected $_cacheTag = 'mageants_deleteaccount';
    protected $_eventPrefix = 'mageants_deleteaccount';
 
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Mageants\Deleteaccount\Model\ResourceModel\Deleteaccount');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * Get Id.
     * Set Id
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * Get Name.
     * Set Name
     * @return varchar
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }
    public function setName($Name)
    {
        return $this->setData(self::NAME, $Name);
    }

    /**
     * Get CUSTOMER EMAIL.
     * Set CUSTOMER EMAIL
     * @return varchar
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMEREMAIL);
    }
    public function setCustomerEmail($customeremail)
    {
        return $this->setData(self::CUSTOMEREMAIL, $customeremail);
    }

    /**
     * Get Group.
     * Set Group
     * @return varchar
     */
    public function getGroup()
    {
        return $this->getData(self::GROUP);
    }
    public function setGroup($group)
    {
        return $this->setData(self::GROUP, $group);
    }

    /**
     * Get Customer Since.
     * Set Customer Since
     * @return varchar
     */
    public function getCustomerSince()
    {
        return $this->getData(self::CUSTOMERSINCE);
    }
    public function setCustomerSince($customersince)
    {
        return $this->setData(self::CUSTOMERSINCE, $customersince);
    }

    /**
     * Get Account Deleted At.
     * Set Account Deleted At
     * @return varchar
     */
    public function getAccountDeletedAt()
    {
        return $this->getData(self::ACCOUNTDELETEDAT);
    }
    public function setAccountDeletedAt($accountdeletedat)
    {
        return $this->setData(self::ACCOUNTDELETEDAT, $accountdeletedat);
    }

    /**
     * Get Account Created In.
     * Set Account Created In
     * @return varchar
     */
    public function getAccountCreatedIn()
    {
        return $this->getData(self::ACCOUNTCREATEDIN);
    }
    public function setAccountCreatedIn($accountcreatedin)
    {
        return $this->setData(self::ACCOUNTCREATEDIN, $accountcreatedin);
    }

    /**
     * Get Website.
     * Set Website
     * @return varchar
     */
    public function getWebsite()
    {
        return $this->getData(self::WEBSITE);
    }
    public function setWebsite($website)
    {
        return $this->setData(self::WEBSITE, $website);
    }
}
