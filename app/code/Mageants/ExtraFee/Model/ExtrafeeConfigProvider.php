<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 *  Extra Fee config provider
 */
class ExtrafeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Mageants\Extrafee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Mageants\Extrafee\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Mageants\ExtraFee\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $ExtrafeeConfig = [];
        $enabled = $this->dataHelper->isModuleEnabled();
        $ExtrafeeConfig['fee_label'] = $this->dataHelper->getFeeLabel();
        $ExtrafeeConfig['custom_fee_amount'] = $this->dataHelper->getExtrafee();
        $ExtrafeeConfig['enable'] = ($enabled) ? true : false;
        $ExtrafeeConfig['checkoutfee_label']=$this->dataHelper->getCheckoutFeeLabel();
        $ExtrafeeConfig['custom_checkoutfee_amount']=$this->dataHelper->getCheckoutFeeAmount();
        $ExtrafeeConfig['mandatory_shipfee_lable']=$this->dataHelper->getAllMandatoryShipingfeeLable();
        $ExtrafeeConfig['mandatory_orderfee_lable']=$this->dataHelper->getAllMandatoryOrderfeeLable();
        $ExtrafeeConfig['orderfee_label']=$this->dataHelper->getOrderFeeLabel();
        $ExtrafeeConfig['custom_orderfee_amount']=$this->dataHelper->getOrderFeeAmount();
        $ExtrafeeConfig['cod_fee_amount']=$this->dataHelper->getCodFee();
        $ExtrafeeConfig['all_fee_labels']=$this->dataHelper->getAllFeeLabels();
        return $ExtrafeeConfig;
    }
}
