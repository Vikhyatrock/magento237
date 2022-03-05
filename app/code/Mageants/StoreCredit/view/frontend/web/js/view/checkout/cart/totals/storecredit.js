define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageants_StoreCredit/checkout/summary/storecredit'
            },
            totals: quote.getTotals(),
            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() != 0;
            },
            getStoreCreditCode: function() {
                if (this.totals()) {
                    return totals.getSegment('storecredit').title;
                }
                return null;
            },
            getPureValue: function() {
                var price = 0,
                    storecredit = totals.getSegment('storecredit');
                if (this.totals() && storecredit !== null && storecredit.value) {
                    price = storecredit.value;
                }
                return price;
            },
            getValue: function() {
                return this.getFormattedPrice(this.getPureValue());
            }
        });
    }
);
