var config = {
    "map": {
        '*': {
            'freeshippingbar' :'Mageants_FreeShippingBar/js/freeshippingbar'
        }
    },
    /*paths:{
        'mageants/freeshippingbar' : 'Mageants_FreeShippingBar/js/freeshippingbar'
    }.*/
    shim:{
        'freeshippingbar': {
		                  deps: ['jquery']
		                 }
    }
};