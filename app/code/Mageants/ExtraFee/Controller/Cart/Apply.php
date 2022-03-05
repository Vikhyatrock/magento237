<?php
namespace Mageants\ExtraFee\Controller\Cart;

class Apply extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageants\ExtraFee\Helper\Data $helperData,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_helperData = $helperData;
        $this->_cookieManager = $cookieManager;
        return parent::__construct($context);
    }

    public function execute()
    {
        $orderFee=$this->_cookieManager->getCookie("orderExtrafeeAmount");
        $fee = 0;
        if ($orderFee != "") {
            $fee = $fee + $orderFee;
        }
        if ($this->_helperData->getExtrafee()) {
            $fee = $fee + $this->_helperData->getExtrafee();
        }
        $this->_checkoutSession->setFee($fee);
        $this->_checkoutSession->getQuote()->collectTotals()->save();

        return $this->_pageFactory->create();
    }
}
