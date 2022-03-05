<?php
/**
 * @category Mageants DeleteAccount
 * @package Mageants_DeleteAccount
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Deleteaccount\Api\Data;

interface GridInterface
{
    const ID = 'id';
    const NAME = 'name';
    const CUSTOMEREMAIL = 'customer_email';
    const GROUP = 'group';
    const CUSTOMERSINCE = 'customer_since';
    const ACCOUNTDELETEDAT = 'account_deleted';
    const ACCOUNTCREATEDIN = 'account_created_in';
    const WEBSITE = 'website';
    
 
    public function getId();
    public function setId($Id);
    
    public function getName();
    public function setName($Name);

    public function getCustomerEmail();
    public function setCustomerEmail($customeremail);

    public function getGroup();
    public function setGroup($group);
    
    public function getCustomerSince();
    public function setCustomerSince($customersince);

    public function getAccountDeletedAt();
    public function setAccountDeletedAt($accountdeletedat);

    public function getAccountCreatedIn();
    public function setAccountCreatedIn($accountcreatedin);

    public function getWebsite();
    public function setWebsite($website);
}
