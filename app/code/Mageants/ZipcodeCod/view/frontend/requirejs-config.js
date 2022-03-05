/**
 * Mageants ZipcodeCod Magento2 Extension 
 */ 
var config = {
    map: {
        "*": {
            'Magento_Checkout/js/model/new-customer-address': 'Mageants_ZipcodeCod/js/model/new-customer-address',
            'Magento_OfflinePayments/js/view/payment/method-renderer/cashondelivery-method':'Mageants_ZipcodeCod/js/cashondelivery-method',
            'Magento_Checkout/js/view/payment/list':'Mageants_ZipcodeCod/js/view/payment/list'
        }
    }
};
