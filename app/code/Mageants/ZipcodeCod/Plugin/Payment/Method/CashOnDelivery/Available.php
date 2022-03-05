<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Plugin\Payment\Method\CashOnDelivery;

use Magento\OfflinePayments\Model\Cashondelivery;
use Magento\Checkout\Model\Session;

class Available
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    public $checkoutSession;
    
    /**
     * @var \Mageants\ZipcodeCod\Model\ZipcodeCod
     */
    public $zipcodeCod;
    
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Mageants\ZipcodeCod\Model\ZipcodeCod $zipcodeCod
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        \Mageants\ZipcodeCod\Model\ZipcodeCod $zipcodeCod,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Mageants\ZipcodeCod\Helper\Data $zipCodeHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->_zipcodeCod = $zipcodeCod;
        $this->zipCodeHelper = $zipCodeHelper;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Cashondelivery $subject
     * @param $result
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterIsAvailable(Cashondelivery $subject, $result)
    {
        $Subject = $subject;
        $quote = $this->checkoutSession->getQuote()->getShippingAddress();
        $zipcode = $quote->getPostcode();
        $zipcodeCollections = $this->_zipcodeCod->getCollection();
        $postCodeDetailes = [];
        $enable  = $this->zipCodeHelper->isZipcodeEnable();
        $i=0;
        foreach ($zipcodeCollections as $zipcodeCollection) {
            $postCodeDetailes[$i]['postcode'] = $zipcodeCollection->getZipcode();
            $postCodeDetailes[$i]['deliverydays'] = $zipcodeCollection->getEstimatedDeliveryTime();
            $postCodeDetailes[$i]['codavailable'] = $zipcodeCollection->getIsCodAvailable();
            $postCodeDetailes[$i]['city'] = $zipcodeCollection->getCity();
            $i++;
        }
        foreach ($postCodeDetailes as $postCodeDetail) {
            if ($enable == 1) {
                if ($postCodeDetail['postcode'] == $zipcode && $postCodeDetail['codavailable'] == 1) {
                    return $result;
                }
            } else {
                return $result;
            }
        }
        return false;
    }
}
