require([
    "jquery",
    'mage/calendar'
], function ($) {
	"use strict";
		$(document).ready(function() {
        var maxD = $('#page_fromdate').val();
        $('#page_fromdate').calendar({
               showsTime: true,           
               hideIfNoPrevNext: true,
               minDate: -1,
               buttonText: 'Select Date',
               dateFormat: 'yyyy-MM-dd',
               timeFormat: 'HH:mm:ss'
            });
          var link = $("#page_link_url").val();
          var openinnew = $("#page_open_in_new_page").val();
            if (link != "") {
              $(".link").attr("href",link);
            }else{
              $(".link").attr("href","#");
            }
            if (openinnew == 1) {
              $(".link").attr("target","_blank");
            }
         });
		$("#page_template").change(function(e){
			var template = $("#page_template").val();	
			if (template == 1) {
				$("#page_bar_background_color").val("#B323C2");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#F5FF0F");
				$("#page_goal_text_color").val("#F5FF0F");
			}else if(template == 2){
				$("#page_bar_background_color").val("#e8562a");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#FFFFFF");
				$("#page_goal_text_color").val("#FFF03D");
			}
			else if(template == 3){
				$("#page_bar_background_color").val("#B323C2");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#FFFFFF");
				$("#page_goal_text_color").val("#F5FF0F");
			}
			else if(template == 4){
				$("#page_bar_background_color").val("#0C020D");
				$("#page_bar_text_color").val("#2139FF");
				$("#page_bar_link_color").val("#FF2E79");
				$("#page_goal_text_color").val("#FF3BB6");
			}
			else if(template == 5){
				$("#page_bar_background_color").val("#B323C2");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#F5FF0F");
				$("#page_goal_text_color").val("#F5FF0F");
			}
			else if(template == 6){
				$("#page_bar_background_color").val("#8C3F22");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#F5FF0F");
				$("#page_goal_text_color").val("#FFFFFF");
			}else{
				$("#page_bar_background_color").val("#0099e5");
				$("#page_bar_text_color").val("#FFFFFF");
				$("#page_bar_link_color").val("#F5FF0F");
				$("#page_goal_text_color").val("#F5FF0F");
			}
		});
		$(document).ready(function() {
              var backcolor =  $("#page_bar_background_color").val();
              var pagetextcolor =  $("#page_bar_text_color").val();
              var opacity =  $("#page_bar_background_opacity").val();
              var linkcolor =  $("#page_bar_link_color").val();
              var goaltextcolor =  $("#page_goal_text_color").val();
              var fontsize =  $("#page_font_size").val();
              var fonts =  $("#page_fonts").val();
              var image =  $("#page_image_image").attr('src');
              if (image != "") {
                $(".freeshippingbar").css('background-image', 'url('+image+')');
              }
              $(".freeshippingbar").css('background-color',backcolor);
              $(".freeshippingbar").css('color',pagetextcolor);
              $(".freeshippingbar").css('opacity',opacity);
              $(".first-message").css('font-size',fontsize+"px");
              $(".first-message").css('font-family',fonts);
              $(".link").css('color',linkcolor);
              $(".goal").css('color',goaltextcolor);
                $("#page_template").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_bar_background_color").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_bar_text_color").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_bar_link_color").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_goal_text_color").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_fonts").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_font_size").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $("#page_bar_background_opacity").change(function(e){
                    var backcolor =  $("#page_bar_background_color").val();
                    var pagetextcolor =  $("#page_bar_text_color").val();
                    var opacity =  $("#page_bar_background_opacity").val();
                    var linkcolor =  $("#page_bar_link_color").val();
                    var goaltextcolor =  $("#page_goal_text_color").val();
                    var fontsize =  $("#page_font_size").val();
                    var fonts =  $("#page_fonts").val();
                    $(".freeshippingbar").css('background-color',backcolor);
                    $(".freeshippingbar").css('color',pagetextcolor);
                    $(".freeshippingbar").css('opacity',opacity);
                    $(".first-message").css('font-size',fontsize+"px");
                    $(".first-message").css('font-family',fonts);
                    $(".link").css('color',linkcolor);
                    $(".goal").css('color',goaltextcolor);
                });
                $('#page_image').change(function(e){
                    $(".freeshippingbar").css('background-image', 'url('+URL.createObjectURL(e.target.files[0])+')');
                });
                var maxD = $('#page_fromdate').val();
                $('#page_todate').calendar({   
                   showsTime: true,         
                   hideIfNoPrevNext: true,
                   minDate: new Date(maxD),
                   buttonText: 'Select Date',
                   dateFormat: 'yyyy-MM-dd',
                   timeFormat: 'HH:mm:ss'
                });
            });
})();