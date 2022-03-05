<?php
namespace Mageants\ExtraFee\Controller\Payment;

class Apply extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_total;
    protected $_cookieManager;
    protected $_cookieMetadataFactory;
    protected $sessionManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageants\ExtraFee\Helper\Data $helperData,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Quote\Model\Quote\Address\Total $total,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_helperData = $helperData;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_total = $total;
        $this->sessionManager = $sessionManager;
        return parent::__construct($context);
    }

    public function execute()
    {
        $metadata = $this->_cookieMetadataFactory->create()
              ->setPath($this->sessionManager->getCookiePath())
              ->setDomain($this->sessionManager->getCookieDomain());
        $codFee = $this->_helperData->getCodFee();
       
        
        $total = $this->_total;
        $fee = 0;
        
        
        if ($codFee != 0 || $codFee != null) {
            $fee = $fee + $codFee;
            // $total->setGrandTotal($total->getGrandTotal() + $codFee);
            // $total->setBaseGrandTotal($total->getBaseGrandTotal() + $codFee);
        }
        if ($this->_helperData->getExtrafee()) {
            $fee = $fee + $this->_helperData->getExtrafee();
        }
        $this->_checkoutSession->setFee($fee);
        $this->_checkoutSession->getQuote()->collectTotals()->save();

        return $this->_pageFactory->create();
    }
}
