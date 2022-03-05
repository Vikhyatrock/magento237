define([
    'jquery',
    'underscore',
    'mage/template',
    'priceUtils',
    'jquery/ui'
], function ($, _, mageTemplate, utils) {
    'use strict';

    $.widget('mageants.coiStaticImage', {
        _create: function () {
            var $widget = this;
            $widget.updateImage($widget);
            if ($('#bundle-slide').length == 0) {
                $widget.updateStyle($widget, false);
            }
            $('#bundle-slide').click(function () {
                $widget.updateStyle($widget, true);
            });
        },
        updateImage: function ($widget) {
            $.each($widget.options.imageUrls, function (index, value) {
                $widget.element.find('input[value="' + value.id + '"]').parent('.field.choice')
                .append(mageTemplate('<img src="<%- data.url %>" title="<%- data.title %>" alt="" />', {data: value}))
                .children().wrapAll('<div class="Mageants_image_radio"></div>');
            });
        },
        updateStyle: function ($widget, $isBundle) {
            var $element = $widget.element;
            $element.find('.Mageants_image_radio img').height($widget.options.imageHeight).width($widget.options.imageWidth);
            $element.find('.Mageants_image_radio').each(function () {
                if ($isBundle) {
                    if ($(this).width() - Number($widget.options.imageWidth) - 90 < $(this).find('.label').width()) {
                        $(this).find('.price-notice').css('display', 'block');
                    }
                }
                var height = Number($widget.options.imageHeight),
                    labelMargin = Number($widget.options.imageHeight)/2 - 4;
                if ($(this).find('.label').height() >= Number($widget.options.imageHeight)) {
                    height = $(this).find('label').height();
                    labelMargin = 5;
                    var imgMargin = (height - Number($widget.options.imageHeight))/2 + 5;
                    $(this).find('img').css('margin-top', imgMargin + 'px');
                }
                $(this).height(height + 10);
                $(this).find('input').css('margin-top', height/2 + 6 + 'px');
                $(this).find('.label').css('margin-left', '40px').css('margin-top', labelMargin + 'px');
            });
        }
    });
    return $.mageants.coiStaticImage;
});
