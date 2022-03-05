define([
    "jquery",
    'mage/url'
], function($,url) {
    "use strict";

    $.widget('magently.ajax', {
        options: {
            url: url.build('ajaxshoppingcart/cart/couponpost'),
            method: 'post',
            triggerEvent: 'submit'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self._disabledImputField();
            self.element.on(self.options.triggerEvent, function() {
                self._ajaxSubmit();
            });
        },

        _ajaxSubmit: function() {
            var self = this;
            var data=$("#discount-coupon-form").serialize();
            $('#coupon_code-error').remove();
            if($('#coupon_code').val() == ''){
                return false;
            }
            $.ajax({
                url: self.options.url,
                type: self.options.method,
                data:data,
                dataType: 'json',
                beforeSend: function() {
                    $('body').trigger('processStart');
                },
                success: function(res) {
                    if(res.success==0)
                    {
                        $("#block-discount .mage-error").html(res.message);
                        $("#block-discount .mage-error").show();
                    }else{
                        $("#block-discount .mage-success").fadeIn();
                        $("#block-discount .mage-success").html(res.message);
                        if($('#remove-coupon').val() == 0){
                            $('#remove-coupon').val(1);
                            $('div.fieldset.coupon').find('#coupon_code').attr('disabled','disabled');
                            $('#ajax-info-action').find('span').text('Cancel');
                        }else{
                            $('#remove-coupon').val(0);
                            $('div.fieldset.coupon').find('#coupon_code').removeAttr('disabled');
                            $('#ajax-info-action').find('span').text('Apply');
                        }
                    }
                    $('body').trigger('processStop');
                    setTimeout(self._resetAll(),6000);
                }
            });
        },

        _disabledImputField: function() {
            if($('#remove-coupon').val() == 1){
                $('div.fieldset.coupon').find('#coupon_code').attr('disabled','disabled');
            }else{
                $('div.fieldset.coupon').find('#coupon_code').removeAttr('disabled');
            }

        },
        _resetAll: function(){
            setTimeout(function(){
                $("#block-discount .mage-error").fadeOut();
                $('#block-discount .mage-success').fadeOut();
            },3000);
            
             // Removing it as with next form submit you will be adding the div again in your code. 
        },

    });

    return $.magently.ajax;
});