require(['jquery','Magento_Ui/js/modal/modal'],function($,modal){
	
	
	var options = {
		type: 'popup',
		responsive: true,
		innerScroll: true,
		buttons: [{
			text: $.mage.__('OK'),
			class: 'success_product_delete',
			click: function () {
				this.closeModal();
			}
		}]
	};
	
	var popup_html = $('<div class="finder-popup"/>').html('<div id="content"></div>')
	var last_product_grid_rand = "";
	$(".admin__grid-massaction-form select").removeClass("required-entry");
	$(".admin__grid-massaction-form select").removeClass("local-validation");
	var main_url = productsGrid_massactionJsObject.grid.url;
	var internal_value_map_ids = [];

	$('body').on("change","input[name='value_map_ids']",function(){
		var value_map_ids = $('input[name="value_map_ids"]:checked');
		if($(this).prop("checked") == true){
			if(jQuery.inArray( $(this).val(), internal_value_map_ids ) == '-1'){
				internal_value_map_ids.push($(this).val())
			}
		}else{
			var removeItem = $(this).val();
			internal_value_map_ids = jQuery.grep(internal_value_map_ids, function(value) {
			  return value != removeItem;
			});
		}
		if(internal_value_map_ids.length > 0){
			var internal_new_value_map_ids = internal_value_map_ids.join(',');
			if($('#productsGrid_massaction-mass-select').val() == null){
				productsGrid_massactionJsObject.checkedString = internal_new_value_map_ids;
			}
			if(main_url.includes("?")){
				productsGrid_massactionJsObject.grid.url = main_url+'&internal_value_map_ids='+internal_new_value_map_ids;
			}else{
				productsGrid_massactionJsObject.grid.url = main_url+'?internal_value_map_ids='+internal_new_value_map_ids;
			}
		}else{
			if($('#productsGrid_massaction-mass-select').val() == null){
				productsGrid_massactionJsObject.checkedString = internal_new_value_map_ids;
			}
			if(main_url.includes("?")){
				productsGrid_massactionJsObject.grid.url = main_url+'&internal_value_map_ids=';
			}else{
				productsGrid_massactionJsObject.grid.url = main_url+'?internal_value_map_ids=';
			}
		}
	});

	setInterval(function(){
		if(window.product_grid_rand != last_product_grid_rand)
		{             
			last_product_grid_rand = window.product_grid_rand ;
			
			$(".delete-product-row").on("click",function(){
				var $this = $(this);
				var url = $(this).attr("href");
				var confirm_text = $(this).data('confirm-text');
				$productsGrid_table = $('#productsGrid_table').html();
				if(confirm(confirm_text))
				{
					$.ajax({
						url:url,
						dataType: "json",
						showLoader: true,
						type:"get",
						success:function(data){
							if(data.success)
							{
								options.title = "Success";
								jQuery("#universalProductsGrid .action-reset").click();	
								jQuery("#productsGrid .action-reset").click();

								
							}
							else{
								options.title = "Error"
							}
							var popup = modal(options, popup_html);
							popup_html.find("#content").html(data.message)
							popup_html.modal('openModal');
							$(".success_product_delete").on("click",function(){
								$(".admin__grid-massaction-form select").removeClass("required-entry");
							});
							

						}
					});
					
					
				}
				return false;
			})
		}
	},1000)
})