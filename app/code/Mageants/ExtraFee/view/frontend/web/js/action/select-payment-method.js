/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'mage/storage',
    'Magento_Checkout/js/action/get-totals',
    'jquery/jquery.cookie'
], function ($,quote,urlBuilder,storage,getTotalsAction) {
    'use strict';

    return function (paymentMethod) {
        quote.paymentMethod(paymentMethod);
        if (paymentMethod.method == "cashondelivery") {
            var cod_fee_amount=window.checkoutConfig.cod_fee_amount;
            jQuery.cookie("codFee",cod_fee_amount);
            var serviceUrl = urlBuilder.build('extrafee/payment/apply'); // Our controller to re-collect the totals
            return storage.post(
                serviceUrl
            ).done(
                function(response) {
                    if(response) {
                        var deferred = $.Deferred();
                        getTotalsAction([], deferred);
                        setTimeout(function(){
                            var commentText = $('tr.totals.fee.excl th.mark span.value').html();                            
                            var onlycodlable = '$'+$.cookie("codFee")+' - Cash On Delivery Fee';
                            jQuery.cookie("onlycodlable", onlycodlable);

                            if(jQuery.cookie("beforeCodFee") == null){
                                jQuery.cookie("beforeCodFee",commentText);
                            }
                            else{
                                commentText = jQuery.cookie("beforeCodFee");
                            }

                            if(commentText == ''){
                                 commentText = '$'+$.cookie("codFee")+' - Cash On Delivery Fee';
                                
                            }else if(commentText != '' && $.cookie("codFee") != ''){
                                commentText = commentText+' + $'+$.cookie("codFee")+' - Cash On Delivery Fee';
                            }
                            if(commentText){
                                jQuery.cookie("extraFeeComment",commentText);
                                jQuery.cookie("onlyOrderFeeLableId",commentText);
                                jQuery.cookie("codFeeLable",commentText);
                                $('tr.totals.fee.excl th.mark span.value').html(commentText);
                            }  
                        }, 1000);
                    }
                }
            );            
        }
        else{
            var serviceUrl = urlBuilder.build('extrafee/payment/removecodfee'); // Our controller to re-collect the totals
            return storage.post(
                serviceUrl
            ).done(
                function(response) {
                    if(response) {
                        var substring = '$'+$.cookie("codFee")+' - Cash On Delivery Fee';
                        jQuery.cookie("codFee",'');
                        var deferred = $.Deferred();
                        getTotalsAction([], deferred);
                        var commentText = $('tr.totals.fee.excl th.mark span.value').html();
                        if(commentText){
                            var str = commentText.replace(substring, "");
                            var lastChar = str[str.length -2];
                            if(lastChar == '+'){
                                str = str.slice(0, -2);
                            } 
                            if(jQuery.cookie("onlycodlable")){
                                str = jQuery.cookie("beforeCodFee");
                                jQuery.cookie("onlycodlable", "");
                            }
                            jQuery.cookie("extraFeeComment",str);
                            jQuery.cookie("onlyOrderFeeLableId",str);
                            jQuery.cookie("codFeeLable", "");
                            $('tr.totals.fee.excl th.mark span.value').html(str);
                        }
                    }
                }
            ); 
        }

    };
});