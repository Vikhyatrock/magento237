define([
    'jquery',
    'underscore',
    'mage/template',
    'priceUtils',
    'jquery/ui'
], function ($, _, mageTemplate, utils) {
    'use strict';

    $.widget('mageants.mageants_preview_checkbox', {
        _create: function () {
            var url = [],
                $widget = this;
            url = $widget.updateImage($widget);
            $widget.updateEventListener($widget, url);
        },
        updateEventListener: function ($widget, url) {
            var viewType = this.options.viewType,
                width = this.options.imageWidth,
                height = this.options.imageHeight;

            if (this.options.viewType == 1) {
                $('.Mageants_image_checkbox img').css('width', width + 'px')
                .css('height', height + 'px').css('border', 'solid 2px #ddd');
                $('.Mageants_image_checkbox').fadeIn();
            }

            
                $widget.element.find('.product-custom-option.checkbox').live('click',function () {
                    var values = [];
                    var html = '';
                if (viewType == 0) {
                        $(this).each(function () {
                            values.push($(this).val());
                        });
                        
                    if($(this).is(":checked") == true){
                        
                        if(typeof(url[$(this).val()]) !='undefined')
                        {
                            $widget.element.find('.Mageants_image_checkbox').append('<img class='+$(this).val()+' alt="" src="' +
                            url[$(this).val()] +
                            '" id="img-'+$(this).val()+'" title="' +
                            $("input[value=" + $(this).val() + "]").html() +
                            '" style="height: ' +
                            height +
                            'px; width: ' +
                            width +
                            'px; border: solid 1px #ddd;" />');
                            $widget.element.find('.Mageants_image_checkbox').fadeIn();
                        }
           
                    }
                    else
                    {
                        var imgsrc ='';
                        var imgsrc=$widget.element.find('#img-'+$(this).val()).attr('src');
                            if(imgsrc==url[$(this).val()])
                            {
                                var imgId = $(this).val();
                                $('img#img-'+imgId).remove();
                            }
                    }
                } else if (viewType == 1) {
                    $(this).each(function () {
                        values.push($(this).val());
                    });
                    
                    if(this.checked){
                        $('#image_preview_' + $(this).val()).css('border', 'solid 2px #d33');
                    }
                    else
                    {
                        $('#image_preview_' + $(this).val()).css('border', 'solid 2px #ddd');
                    }
                }
            });
        },
        updateImage: function ($widget) {
            var result = [];
            $.each($widget.options.imageUrls, function (index, image) {
                result[image.id] = image.url;
            });
            return result;
        }
    });
    return $.mageants.mageants_preview_checkbox;
});
