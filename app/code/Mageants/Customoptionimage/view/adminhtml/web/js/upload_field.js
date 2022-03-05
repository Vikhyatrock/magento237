define([
    'Magento_Ui/js/form/element/file-uploader',
    'Magento_Ui/js/form/element/media',
    'jquery'
], function (upload,media,$) {
    'use strict';

    return upload.extend({
        defaults: {
            mageants_upl_preview: '',
            mageants_upl_span: '',
            mageants_option_so: '',
            mageants_value_so: '',
            tempImg: '',
            formkey: ''
        },
        initConfig: function () {
            this._super();
            this.mageants_upl_preview = 'mageants_upl_preview_' + this.uid;
            this.mageants_upl_span = 'mageants_upl_span_' + this.uid;
            this.mageants_option_so = this.dataScope.split('.')[3];
            this.mageants_value_so = this.dataScope.split('.')[5];
            this.formkey = $.cookie('form_key');
            this.uploadFieldId = 'Mageants_Customoptionimage_' + this.mageants_option_so + '_' + this.mageants_value_so;
            return this;
        },
        del: function () {
            document.getElementById(this.mageants_upl_span).className = 'mageants-checkbox-null';
            document.getElementById(this.uid).value = "";
            this.tempImg = '';
        },
        clickUpload: function () {
            document.getElementById(this.uid).click();
        },
        readURL: function () {
            var formData = new FormData(),
                baseUrl = this.baseUrl,
                upFieldId = this.uploadFieldId,
                $widget = this;
                
            formData.append('temporary_image', $('#' + this.uid)[0].files[0]);
            formData.append('option_sortorder', this.mageants_option_so);
            formData.append('value_sortorder', this.mageants_value_so);
            formData.append('form_key', this.formkey);
            if (document.getElementById(this.uid).files && document.getElementById(this.uid).files[0]) {
                var file = document.getElementById(this.uid).files[0];
                var extension = file.name.substring(file.name.lastIndexOf('.'));
                var validFileType = ".jpg , .png , .bmp , .jpeg , .gif";
                if (validFileType.indexOf(extension.toLowerCase()) < 0) {
                    alert("Please select valid file type. The supported file types are .jpg , .png , .bmp");
                    return false;
                }
            }
            $.ajax({
                url : baseUrl,
                type : 'POST',
                data : formData,
                processData: false,
                contentType: false,
                success : function (data) {
                    $('#' + upFieldId).val(data);
                    $widget.storageImage(data);
                }
            });
        },
        storageImage: function (data) {
            $('#' + this.mageants_upl_preview).attr('src', data);
            $('#' + this.mageants_upl_span).attr('class', 'mageants-checkbox-del');
            this.tempImg = data;
        },
        getClass: function () {
            if (this.tempImg == '') {
                return 'mageants-checkbox-null';
            } else {
                return 'mageants-checkbox-del'
            }
        }
    });
});
