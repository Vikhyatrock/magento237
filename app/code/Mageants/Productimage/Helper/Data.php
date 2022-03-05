<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    const APPROVE_EMAIL_TEMPLATE = 'productimage/email/approve_email_template';
    const REJECT_EMAIL_TEMPLATE = 'productimage/email/reject_email_template';
 
    //const EMAIL_SERVICE_ENABLE = 'email_section/sendmail/enabled';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Get Store code
     *
     * @return string
     */
    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * @return bool|string
     */
    public function getConfig($config_path)
    {
        $storeCode=$this->getStoreCode();
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    /**
     * @return bool
     */
    public function tabTitle()
    {
        return $this->getConfig('productimage/general/frontendtabtitle');
    }
    public function getCustomTitle()
    {
        return $this->getConfig('productimage/general/editortextarea');
    }
    public function allowGuest()
    {
        return $this->getConfig('productimage/general/allowguest');
    }
    public function emailRequired()
    {
        return $this->getConfig('productimage/general/emailrequired');
    }
    public function getNavigationOptions()
    {
        return $this->getConfig('productimage/display/navoptions');
    }
    public function getslideSpeed()
    {
        return $this->getConfig('productimage/display/slidespeed');
    }
    public function getimageonSlide()
    {
        return $this->getConfig('productimage/display/imageonslide');
    }
    public function getimageuploadLimit()
    {
        return $this->getConfig('productimage/display/imageuploadlimit');
    }
    public function gethimageDimenstion()
    {
        return $this->getConfig('productimage/display/himagedimenstion');
    }
    public function getvimageDimenstion()
    {
        return $this->getConfig('productimage/display/vimagedimenstion');
    }
    public function gethimageZoom()
    {
        return $this->getConfig('productimage/display/himagezoom');
    }
    public function getvimageZoom()
    {
        return $this->getConfig('productimage/display/vimagezoom');
    }

    public function sendMail($vars)
    {
        $email = $vars['customer_email']; //set receiver mail
 
        $this->inlineTranslation->suspend();
        $storeId = $this->getStoreId();
 
        /* email template */
        
        if ($vars['status'] == 'approve') {
            $template = $this->scopeConfig->getValue(
                self::APPROVE_EMAIL_TEMPLATE,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } elseif ($vars['status'] == 'reject') {
            $template = $this->scopeConfig->getValue(
                self::REJECT_EMAIL_TEMPLATE,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }
        $sender = $this->scopeConfig->getValue(
            'productimage/email/identity',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
 
        try {
            $transport = $this->transportBuilder->setTemplateIdentifier(
                $template
            )->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->getStoreId()
                ]
            )->setTemplateVars(
                $vars
            )->setFromByScope(
                $sender
            )->addTo(
                $email
            )->getTransport();
            $transport->sendMessage();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();
 
        return $this;
    }

    /*
     * get Current store id
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
 
    /*
     * get Current store Info
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }
}
