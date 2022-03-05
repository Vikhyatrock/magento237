define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/cart/totals-processor/default',
        'Magento_Catalog/js/price-utils',
		'Magento_Checkout/js/action/get-totals',
		'mage/url',
		'Magento_Checkout/js/action/get-payment-information'
    ],
    function (
        $, 
        ko, 
        Component, 
        quote, 
        totals, 
        defaultTotal, 
        priceUtils,
		getTotalsAction,
		urlBuilder,
		getPaymentInformationAction
        ) {
		if(totals.getSegment('storecredit') !== null){
			var applyLink = urlBuilder.build('storecredit/cart/apply');
			var apply_store_credit = totals.getSegment('storecredit').value;
			var apply_store_credit_final = parseFloat(totals.getSegment('storecredit').value) * -1;
			var body = $('body').loader();
			body.loader('show'); 
			$.ajax({
				url: applyLink,
				data:  {
					apply_amount:apply_store_credit_final
				},
				type: "post",
				cache: false,
				success: function (data) {
					body.loader('hide'); 
					var deferred = $.Deferred();
					getTotalsAction([], deferred); //this will reload the order summary section
				}
			});
		}
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Mageants_StoreCredit/checkout/storecredit' //template file location
            },
			isDisplayed: function() {
                return window.checkoutConfig.store_config_enable != 0 && window.checkoutConfig.customer_id != null;
            },
			totals: quote.getTotals(),
            checkavailable: function() {
				var price = 0,
                storecredit = totals.getSegment('storecredit');
                if (this.totals() && storecredit !== null && storecredit.value) {
                    return true;
                }
            },
			checknotavailable: function() {
				var price = 0,
                storecredit = totals.getSegment('storecredit');
                if (storecredit.value == '0') {
                    return true;
                }
            },
			getStoreCreditLeft: function() {
				var left_price = window.checkoutConfig.store_credit_balance;
                var storecredit_amount = totals.getSegment('storecredit');
                if (this.totals() && storecredit_amount !== null && storecredit_amount.value) {
					left_price = parseFloat(left_price) + parseFloat(storecredit_amount.value);
                }
				return priceUtils.formatPrice(left_price,quote.getPriceFormat());
            },
			getappliedamount: function() {
				var appliedprice = 0,
                storecredit = totals.getSegment('storecredit');
                if (this.totals() && storecredit !== null && storecredit.value) {
                    appliedprice = storecredit.value;
					if(appliedprice < 0){
						appliedprice = appliedprice * -1; 
					}
                }
				return priceUtils.formatPrice(appliedprice,quote.getPriceFormat());
            },
            /**
             * apply action
             */
            apply: function(value) {
                if (this.validate()) {
                    /* Apply Button action */
					var applyLink = urlBuilder.build('storecredit/cart/apply');
					var apply_store_credit = $("#store_credit_amount"). val();
					if(apply_store_credit != ''){

					var body = $('body').loader();
					body.loader('show'); 
					$.ajax({
						url: applyLink,
						data:  {
							apply_amount:apply_store_credit
						},
						type: "post",
						cache: false,
						success: function (data) {
							var code = data;
						    if (code[0]=='1')
						    {
								$("#message").html(code[1]);
						    }
							body.loader('hide'); 
							var deferred = $.Deferred();
							getTotalsAction([], deferred); //this will reload the order summary section
							getPaymentInformationAction(deferred);
                            $.when(deferred).done(function () {
                               // isApplied(false);
                                totals.isLoading(false);
                            });
						}
					});
				}
                }
            },
            /**
             * Cancel action
             */
            cancel: function() {
                /* Cancel Button action */
				var applyLink = urlBuilder.build('storecredit/cart/cancel');
				var body = $('body').loader();
				body.loader('show'); 
				$.ajax({
					url: applyLink,
					data:  {
						cancel_store_credit:'1'
					},
					type: "post",
					cache: false,
					success: function (data) {
						body.loader('hide'); 
						var deferred = $.Deferred();
						getTotalsAction([], deferred);
						getPaymentInformationAction(deferred);
                        $.when(deferred).done(function () {
                           // isApplied(false);
                            totals.isLoading(false);
                        });
					}
				});
            },
            /**
             * form validation
             *
             * @returns {boolean}
             */
            validate: function() {
                var form = '#my-form';
                return $(form).validation() && $(form).validation('isValid');
            },
        });
    }
);