<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model\Sales\Pdf;

/**
 * StoreCredit Model class for display store credit in order invoice pdf
 */
class Storecredit extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{
    public function getTotalsForDisplay()
    {
        $amount = $this->getOrder()->formatPriceTxt($this->getAmount());
        if ($this->getAmountPrefix()) {
            $amount = $this->getAmountPrefix() . $amount;
        }

        $title = __($this->getTitle());
        if ($this->getTitleSourceField()) {
            $label = $title . ' (' . $this->getTitleDescription() . '):';
        } else {
            $label = $title . ':';
        }

        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $total = ['amount' => $amount, 'label' => $label, 'font_size' => $fontSize];
        return [$total];
    }
    
    public function getAmount()
    {
        if ($this->getSource()->getDataUsingMethod($this->getSourceField()) == '') {
            if (!empty($this->getSource()->getOrder()->getData($this->getSourceField())) && $this->getSource()->getOrder()->getData($this->getSourceField()) != 0) {
                return '-'.$this->getSource()->getOrder()->getData($this->getSourceField());
            } else {
                return '';
            }
        } else {
            return $this->getSource()->getDataUsingMethod($this->getSourceField());
        }
    }
}
