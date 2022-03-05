
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
            var ajaxcall = 0;
            var simpleproductid;
        $.widget('mage.SwatchRenderer', widget, {
            
                getProduct: function () {
                    simpleproductid = "";
                    var products = this._CalcProducts();
                    var PreorderData = this.options.jsonConfig.preorderdata;
                    if(products.length == 1){
                        ajaxcall = 1;
                        simpleproductid = products;
                        this._UpdatePreOrder();  
                    }else{
                        ajaxcall = 0;
                        this._UpdatePreOrder();
                    }
                    return _.isArray(products) ? products[0] : null;
                },

                _UpdatePreOrder: function () {
                    var $widget = this,
                        $this = $widget.element;
                    var page_url = $("#url").val();
                    var button_text = $("#buttontext").val();
                    if(ajaxcall == 1){
                        $.ajax({ 
                            type: "POST", 
                            url: page_url, 
                            data : 'id='+ simpleproductid,
                            dataType: "json", 
                            success: function(data){ 
                                if(data.status == "preorder"){
                                    $this.parent().find('button.action.tocart.primary').text(button_text);
                                    $this.parent().find('.simpleproductid').val(simpleproductid);
                                    $('#product-addtocart-button').text(button_text);
                                    $('.product-info-stock-sku .stock span').html("Pre Order");
                                    $('.preorder_note').html(data.preorder_message);
                                    $('.simpleproductid').val(simpleproductid);  
                                }else{
                                    $this.parent().find('button.action.tocart.primary').text("Add to Cart");
                                    $this.parent().find('.simpleproductid').val("");
                                    $('#product-addtocart-button').text("Add to Cart");
                                    $('.product-info-stock-sku .stock span').html("IN STOCK");
                                    $('.preorder_note').html("");
                                    $('.simpleproductid').val("");
                                }
                              }
                        });
                    }
                         
                },
        });
        return $.mage.SwatchRenderer;
    }
});