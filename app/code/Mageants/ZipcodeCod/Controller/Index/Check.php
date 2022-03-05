<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\ZipcodeCod\Controller\Index;

use Magento\Customer\Model\Session;

class Check extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public $scopeConfig;

    /**
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    public $checkoutSession;
    
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    public $customerSession;
    
    public $zipCodeModel;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageants\ZipcodeCod\Model\ZipcodeCod $zipCodeModel
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->_zipCodeModel = $zipCodeModel;
    }
    public function execute()
    {
        $zipcode = $this->getRequest()->getParam('zipcode');
                
        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }
        
        $estimatedDeliveryMsg = $this->scopeConfig->getValue(
            'mageants_zipcodecod/general/estimated_delivery_msg',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $codAvailableMsg = $this->scopeConfig->getValue(
            'mageants_zipcodecod/general/cod_available_msg',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $codUnavailableMsg = $this->scopeConfig->getValue(
            'mageants_zipcodecod/general/cod_unavailable_msg',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $zipcodeCollections = $this->_zipCodeModel->getCollection();
        $postCodeDetailes = [];
        $i = 0;
        foreach ($zipcodeCollections as $zipcodeCollection) {
            $postCodeDetailes[$i]['postcode'] = $zipcodeCollection->getZipcode();
            $postCodeDetailes[$i]['deliverydays'] = $zipcodeCollection->getEstimatedDeliveryTime();
            $postCodeDetailes[$i]['codavailable'] = $zipcodeCollection->getIsCodAvailable();
            $postCodeDetailes[$i]['city'] = $zipcodeCollection->getCity();
            $i++;
        }
        foreach ($postCodeDetailes as $postCodeDetail) {
            if ($postCodeDetail['postcode'] == $zipcode && $postCodeDetail['codavailable'] == 1) {
                if ($postCodeDetail['codavailable'] == 1) {
                    $html = '<div class="message-success success message"><div>'.
                    sprintf(__($estimatedDeliveryMsg), $postCodeDetail['deliverydays']).'</div></div>';
                    $html .= '<div class="message-success success message"><div>'.
                    sprintf(__($codAvailableMsg), $postCodeDetail['city']).'</div></div>';
                } else {
                    $html .= '<div class="message-error error message"><div>'.$codUnavailableMsg.'</div>';
                }
                break;
            } else {
                $html = '<div class="message-error error message"><div>'.$codUnavailableMsg.'</div><p><?php print_r($postCodeDetailes); ?></P>';
            }
        }
        if (empty($postCodeDetailes)) {
            $html = '<div class="message-error error message"><div>'.$codUnavailableMsg.'</div><p><?php print_r($postCodeDetailes); ?></P>';
        }
        $this->checkoutSession->setCheckoutZipCode($zipcode);
        $this->customerSession->setCheckoutZipCode($zipcode);
        $this->checkoutSession->getQuote()->getShippingAddress()->setPostcode($zipcode)->save();

        $result['html'] = $html;
        $jsonData = json_encode($result);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
