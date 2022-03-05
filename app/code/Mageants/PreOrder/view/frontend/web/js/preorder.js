
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
define([
    'jquery',
], function ($) {
	$(document).ready(function() {
		
		var sid,page_url,button_text,add_to_cart,stock_status;
		add_to_cart = $("#product-addtocart-button").text();
        stock_status = $('.product-info-stock-sku .stock span').text();
        page_url = $("#url").val();
        button_text = $("#buttontext").val();
        jQuery(".product-options-wrapper select[id^='attribute']").last().on('change', function() {
			var simpleId = "";
	      	setTimeout(function (){
	            simpleId=jQuery("input[name=selected_configurable_option]").val();
	           	if(simpleId != ""){
	            	$.ajax({ 
					    type: "POST", 
					    url: page_url, 
		               	data : 'id='+ simpleId,
					    dataType: "json", 
					    success: function(data){ 
					        if(data.status == "preorder"){
					      		$('#product-addtocart-button').text(button_text);
			                	$('.product-info-stock-sku .stock span').html("Pre Order");
			                	$('.preorder_note').html(data.preorder_message);
			                	$('#simpleproductid').val(simpleId);	 
							}else{
								$('#product-addtocart-button').text(add_to_cart);
			                	$('.product-info-stock-sku .stock span').html(stock_status);
			                	$('.preorder_note').html("");
			                	$('#simpleproductid').val("");
							}
					    }
					});
	            }
		       
	      }, 500); 
	    });
	
	});	
});
  
