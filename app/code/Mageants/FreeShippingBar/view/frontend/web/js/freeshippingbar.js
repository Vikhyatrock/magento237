jQuery(document).ready(function() {
    window.setBarValues = function (backcolor,classid,pagetextcolor,opacity,linkcolor,goaltextcolor,fontsize,fonts,link,openinnew,img,position){
        jQuery(".freeshippingbar"+classid).css('background-color',backcolor);
        jQuery(".freeshippingbar"+classid).css('color',pagetextcolor);
        jQuery(".freeshippingbar"+classid).css('opacity',opacity);
        jQuery(".freeshippingbar"+classid).css('font-size',fontsize+"px");
        jQuery(".freeshippingbar"+classid).css('font-family',fonts);
        jQuery(".freeshippingbar"+classid).find(".link").css('color',linkcolor);
        jQuery(".freeshippingbar"+classid).find(".goal").css('color',goaltextcolor);
        if (img != "") {
            jQuery(".freeshippingbar"+classid).css('background-image', 'url('+img+')');
        }
        if (link != "") {
          jQuery(".freeshippingbar"+classid).find(".link").attr("href",link);
        }else{
          jQuery(".freeshippingbar"+classid).find(".link").attr("href","#");
        }
        if (openinnew != "") {
            if (openinnew == 1) {
              jQuery(".freeshippingbar"+classid).find(".link").attr("target","_blank");
            }
        }
        if (position == 1) {
            jQuery(".freeshippingbar"+classid).css('position','fixed');
            jQuery(".freeshippingbar"+classid).css('padding','1px 0 0 0');
            jQuery(".freeshippingbar"+classid).css('width','100%');
            jQuery(".freeshippingbar"+classid).css('z-index','10');
            jQuery(".freeshippingbar"+classid).css('top','0px');
            jQuery(".freeshippingbar"+classid).css('left','0px');
            jQuery(".page-wrapper").css('top','45px');
            jQuery(".logo").css('z-index','0');
            jQuery(".block-search").css('z-index','0');
            jQuery(".navigation").css('z-index','1');
        }else if(position == 3){
            jQuery(".freeshippingbar"+classid).css('position','fixed');
            jQuery(".freeshippingbar"+classid).css('padding','1px 0 0 0');
            jQuery(".freeshippingbar"+classid).css('width','100%');
            jQuery(".freeshippingbar"+classid).css('z-index','10');
            jQuery(".freeshippingbar"+classid).css('bottom','0px');
            jQuery(".freeshippingbar"+classid).css('left','0px');
            jQuery(".copyright").css('margin','0 0 45px 0');
        }else if(position == 2){
            jQuery(".freeshippingbar"+classid).css('margin','10px 0');
        }
    }
});