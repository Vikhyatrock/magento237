var config = {
    map: {
        '*': {
        	catalogAddToCart:'Mageants_PreOrder/js/catalog-add-to-cart',
         	'Magento_Catalog/js/catalog-add-to-cart':'Mageants_PreOrder/js/catalog-add-to-cart',
            preorder: 'Mageants_PreOrder/js/preorder',
            productSummary: 'Mageants_PreOrder/js/product-summary'
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Mageants_PreOrder/js/swatch-renderer': true
            }
        }
    }
};