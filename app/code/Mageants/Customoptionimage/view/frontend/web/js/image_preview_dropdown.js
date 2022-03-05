define([
    'jquery',
    'underscore',
    'mage/template',
    'priceUtils',
    'jquery/ui'
], function ($, _, mageTemplate, utils) {
    'use strict';

    $.widget('mageants.mageants_preview', {
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

            $('.Mageants_image_preview img').css('width', width + 'px')
            .css('height', height + 'px').css('border', 'solid 2px #ddd');
            if (this.options.viewType == 1) {
                $('.Mageants_image_preview').fadeIn();
            }

            $widget.element.find('select.product-custom-option:not(.multiselect)').change(function () {
                var optionid = $(this).attr('id').split('_')[1];
                if (viewType == 0) {
                    var element = $widget.element.find('.Mageants_image_preview img');
                    if (typeof url[$(this).val()] == 'string' && url[$(this).val()].length > 0) {
                        element.attr('src', url[$(this).val()]);
                        element.attr('title', $("option[value=" + $(this).val() + "]").html());
                        $widget.element.find('.Mageants_image_preview').fadeIn();
                    } else {
                        $widget.element.find('.Mageants_image_preview').fadeOut();
                    }
                } else if (viewType == 1) {
                    if (typeof url[$(this).val()] == 'string') {
                        var element = $widget.element.find('#image_preview_' + $(this).val());
                        $widget.element.find('.Mageants_image_preview img').css('border','solid 2px #ddd');
                        element.css('border','solid 2px #d33');
                    } else {
                        $widget.element.find('.Mageants_image_preview img').css('border','solid 2px #ddd');
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
    return $.mageants.mageants_preview;
});
