define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils',
    'mage/translate'
], function ($, ko, Component, customerData, priceUtils) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Mageants_FreeShippingBar/shippingbar'
        },
        /**
         * init function
         */
        initialize: function (config) {
            /*if (config.cartTotal <= 0) {
                var Total = 0;
            }else{
                
                var Total = config.cartTotal;
            }
            this._super();
            this.below_goal = ko.observable();
            var cartData = customerData.get('cart');
            this.below_goal(config.goal - Total);
            cartData.subscribe(function (updatedCart) {
                var selectedProducts = config.products.split(",");
                var totals = "";
                if(updatedCart.items.length){
                    $.each(updatedCart.items, function (key, val) {
                        if(jQuery.inArray(val.product_id, selectedProducts) !== -1){
                            totals += val.product_price_value;
                        }
                    });
                }
                this.below_goal(config.goal - totals);
            }, this);*/

            var cartData = customerData.get('cart');
            this._super();
            this.below_goal = ko.observable();
            var Total = 0;
            if (config.cartTotal <= 0) {
                Total = 0;
            } else {
                Total = 0;
                var cartData = customerData.get('cart');

                var selectedProducts;
                if(config.products !== ""){
                    selectedProducts = config.products.split(",");
                } else {
                    selectedProducts = [];
                }

                if(selectedProducts.length){
                    if(cartData().items !== undefined){
                        $.each(cartData().items, function (key, val) {
                            if(jQuery.inArray(val.product_id, selectedProducts) !== -1){
                                Total += val.product_price_value*val.qty;
                            }
                        });
                    }
                } else {
                    if(cartData().items !== undefined){
                        $.each(cartData().items, function (key, val) {
                            Total += val.product_price_value*val.qty;
                        });
                    }
                }
            }
            this.below_goal(config.goal - Total);
            cartData.subscribe(function (updatedCart) {
                var selectedProducts;
                if(config.products !== ""){
                    selectedProducts = config.products.split(",");
                } else {
                    selectedProducts = [];
                }
                var totals = 0;
                if(selectedProducts.length){
                    if(updatedCart.items.length){
                        $.each(updatedCart.items, function (key, val) {
                            if(jQuery.inArray(val.product_id, selectedProducts) !== -1){
                                totals += val.product_price_value*val.qty;
                            }
                        });
                    }
                } else {
                    if(updatedCart.items.length){
                        $.each(updatedCart.items, function (key, val) {
                                totals += val.product_price_value*val.qty;
                        });
                    }
                }
                this.below_goal(config.goal - totals);
            }, this);
            
            this.message = ko.computed(function () {
                if (this.below_goal() == this.goal) {
                    return config.first_message.replace("{{goal}}", "<span class='goal' style='color:"+config.goalcolor+";'>"+ config.currency + this.getFormattedPrice(config.goal) + "</span>");
                } else if (this.below_goal() > 0 && this.below_goal() < config.goal) {
                    return config.below_goal_message.replace("{{below_goal}}", "<span class='goal' style='color:"+config.goalcolor+";'>" + config.currency + this.getFormattedPrice(this.below_goal()) + "</span>");
                } else if (this.below_goal() <= 0) {
                    return config.achieve_message;
                }
            }, this);
        },

        /**
         * FormatPrice
         */
        getFormattedPrice: function (price) {
            return priceUtils.formatPrice(price).replace(",",".");
        }
    });
});
