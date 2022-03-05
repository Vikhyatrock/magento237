define([
    'jquery',
    'underscore',
    'mage/template',
    'priceUtils',
    'jquery/ui'
], function ($, _, mageTemplate, utils) {
    'use strict';

    $.widget('mageants.mageants_preview_radio', {
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
                $('.Mageants_image_radio img').css('width', width + 'px')
                .css('height', height + 'px').css('border', 'solid 2px #ddd');
                $('.Mageants_image_radio').fadeIn();
            }

            $widget.element.find('.product-custom-option.radio').on('change',function () {
                var values = [];
                var html = '';
                if (viewType == 0) {
                    $(this).each(function () {
                        values.push($(this).val());
                    });
                        console.log('called');
                        if (typeof(url[$(this).val()]) != 'undefined') {
                            html += '<img alt="" src="' +
                            url[$(this).val()] +
                            '" title="' +
                            $("option[value=" + $(this).val() + "]").html() +
                            '" style="height: ' +
                            height +
                            'px; width: ' +
                            width +
                            'px; border: solid 1px #ddd;" />';
                        }
                    if (html.length > 0) {
                        $widget.element.find('.Mageants_image_radio').html(html);
                        $widget.element.find('.Mageants_image_radio').fadeIn();
                    } else {
                        $widget.element.find('.Mageants_image_radio').fadeOut();
                    }
                } else if (viewType == 1) {
                    $(this).each(function () {
                        values.push($(this).val());
                    });
                    $('.Mageants_image_radio img').css('border', 'solid 2px #ddd');
                    $('#image_preview_'+ $(this).val()).css('border', 'solid 2px #d33');
                    
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
    return $.mageants.mageants_preview_radio;
});
