var config = {
    "map": {
        '*': {
            'fancyBox' :'Mageants_Productimage/js/jquery.fancybox',
            'owlCarousel' :'Mageants_Productimage/js/owl.carousel',
        }
    },
    "shim":{
		        'owlCarousel': {
		        deps: ['jquery']
		    },
		    'fancyBox': {
		        deps: ['jquery']
		    }
    }
};