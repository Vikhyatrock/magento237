<?php
namespace Mageants\FreeShippingBar\Plugin;

use Magento\Checkout\Model\Cart;

class CustomerSessionContext
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        Cart $checkoutSession,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->httpContext = $httpContext;
    }

    /**
     * @param \Magento\Framework\App\ActionInterface $subject
     * @param callable $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        \Magento\Framework\App\ActionInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->httpContext->setValue(
            'customer_id',
            $this->customerSession->getCustomerId(),
            false
        );

        $this->httpContext->setValue(
            'customer_name',
            $this->customerSession->getCustomer()->getName(),
            false
        );

        $this->httpContext->setValue(
            'customer_email',
            $this->customerSession->getCustomer()->getEmail(),
            false
        );

        $this->httpContext->setValue(
            'customer_group_id',
            $this->customerSession->getCustomer()->getGroupId(),
            false
        );

        $this->httpContext->setValue(
            'quote',
            $this->_checkoutSession->getQuote(),
            false
        );

        return $proceed($request);
    }
}
