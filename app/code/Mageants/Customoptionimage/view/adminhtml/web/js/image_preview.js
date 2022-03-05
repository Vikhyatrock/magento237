define([
    'Magento_Catalog/js/form/element/checkbox',
    'Magento_Ui/js/form/element/single-checkbox'
], function (checkbox,singleCheckbox) {
    'use strict';

    return singleCheckbox.extend({
        defaults: {
            mageants_preview: '',
            src: '',
            mageants_span: '',
            mageants_span_class: ''
        },
        initConfig: function () {
            this._super();
            var key1 = this.dataScope.split('.')[3];
            var key2 = this.dataScope.split('.')[5];
            this.mageants_preview = 'mageants_preview_' + key1 + '_' + key2;
            this.mageants_span = 'mageants_span_' + key1 + '_' + key2;
            return this;
        },
        del: function () {
            this.setSrc();
            
            if (document.getElementById(this.mageants_span).className != 'mageants-checkbox-null') {
                if (document.getElementById(this.mageants_span).className == 'mageants-checkbox-del') {
                    this.onCheckedChanged(true);
                    this.onExtendedValueChanged(true);
                    document.getElementById(this.uid).checked = true;
                    document.getElementById(this.mageants_span).className = 'mageants-checkbox-undo';
                } else {
                    this.reset();
                    document.getElementById(this.mageants_span).className = 'mageants-checkbox-del';
                    document.getElementById(this.uid).checked = false;
                    document.getElementById(this.mageants_preview).src = this.src;
                }
            }
        },
        setSrc: function () {
            if (this.src.length == 0) {
                this.src = this.getPreview();
            }
        }
    });
});
