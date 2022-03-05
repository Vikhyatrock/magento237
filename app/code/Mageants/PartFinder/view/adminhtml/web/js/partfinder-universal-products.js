require(['jquery','Magento_Ui/js/modal/modal'],function($,modal){
	
	$(".admin__grid-massaction-form select").removeClass("required-entry");
	$(".admin__grid-massaction-form select").removeClass("local-validation");
	var main_url = universalProductsGrid_massactionJsObject.grid.url;
	var internal_procuct_ids = [];
	$('body').on("change","input[name='procuct_ids']",function(){
		var procuct_ids = $('input[name="procuct_ids"]:checked');
		if($(this).prop("checked") == true){
			if(jQuery.inArray( $(this).val(), internal_procuct_ids ) == '-1'){
				internal_procuct_ids.push($(this).val())
			}
		}else{
			var removeItem = $(this).val();
			internal_procuct_ids = jQuery.grep(internal_procuct_ids, function(value) {
			  return value != removeItem;
			});
		}
		if(internal_procuct_ids.length > 0){
			var internal_new_procuct_ids = internal_procuct_ids.join(',');
			if($('#universalProductsGrid_massaction-mass-select').val() == null){
				universalProductsGrid_massactionJsObject.checkedString = internal_new_procuct_ids;
			}
			if(main_url.includes("?")){
				universalProductsGrid_massactionJsObject.grid.url = main_url+'&internal_procuct_ids='+internal_new_procuct_ids;
			}else{
				universalProductsGrid_massactionJsObject.grid.url = main_url+'?internal_procuct_ids='+internal_new_procuct_ids;
			}
		}else{
			if($('#universalProductsGrid_massaction-mass-select').val() == null){
				universalProductsGrid_massactionJsObject.checkedString = internal_new_procuct_ids;
			}
			if(main_url.includes("?")){
				universalProductsGrid_massactionJsObject.grid.url = main_url+'&internal_procuct_ids=';
			}else{
				universalProductsGrid_massactionJsObject.grid.url = main_url+'?internal_procuct_ids=';
			}
		}		
	});
})