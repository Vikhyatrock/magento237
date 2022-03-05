<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block\Adminhtml\InstagramIntegration\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;

/**
 * Class Image
 * @package Mageants\InstagramIntegration\Block\Adminhtml\InstagramIntegration\Grid\Renderer
 */
class Image extends \Magento\Framework\Data\Form\Element\AbstractElement
{

    const POINTPINSCRIPT = '</div><style type="text/css">#insta_image{display: none;}
    #imgdiv{position:relative;}.pin-img{position:absolute;}
    #pin-img2{top:0px;}div#imgdiv span{float:right}
    #pin-img2{top:60px;margin-top:5px}#pin-img3{top:120px;margin-top:5px}
    #pin-img4{top:180px;margin-top:5px}#pin-img5{top:240px;margin-top:5px}
    #pin-img6{top:300px;margin-top:5px}</style>
    <script type="text/javascript">require(["jquery", "jquery/ui"], function(
    jQuery){jQuery(function(){ jQuery("img.pin-img").draggable(); });
    jQuery("img.pin-img").mouseup(function(){var id = jQuery(
    this).attr("id"); var pinno = jQuery(this).data("pinno");
    var pleft = jQuery( "#"+id ); var position = pleft.position();
    var pl=Math.ceil(position.left+24);
    var pt=Math.ceil(position.top+15);
    jQuery("#insta_position_top"+pinno).val(pt);
    jQuery("#insta_position_left"+pinno).val(pl); });});</script>';
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_coreSession;

    /**
     * @param Repository $assetRepo
     * @param SessionManagerInterface $coreSession
     */
    public function __construct(
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        Collection $intagramCollection
    ) {
        $this->_coreSession = $coreSession;
        $this->_assetRepo = $assetRepo;
        $this->_instagramCollection = $intagramCollection;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $this->_coreSession->start();
        $id = $this->_coreSession->getInstaId();

        $collection = $this->_instagramCollection->addFieldToFilter('insta_media_id', $id);
        
        $instaData = $collection->getData();

        foreach ($instaData as $instaColl) {
            $pin1 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/1.png');
            $pin2 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/2.png');
            $pin3 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/3.png');
            $pin4 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/4.png');
            $pin5 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/5.png');
            $pin6 = $this->_assetRepo->getUrl('Mageants_InstagramIntegration::images/6.png');

            $customDiv = '<label><span>Simply drag and drop each pin to 
            automatically set top and left positions for each Pin point.</span>
            </label><div id="imgdiv">
            <img class="parentimg" src="'.$instaColl['insta_media_medium'].'" style="width:554px; max-height:554px;"/>';
            if ($instaColl['position_top1'] || $instaColl['position_left1']) {
                $customDiv .= '<span><img id="pin-img1" data-pinno="1" class="pin-img"
                src="'.$pin1.'" width="50px" height="50px"
                style="top:'.($instaColl['position_top1']-15).'px;
                left:'.($instaColl['position_left1']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img id="pin-img1" data-pinno="1"
                class="pin-img" src="'.$pin1.'" width="50px" height="50px"/></span>';
            }
            if ($instaColl['position_top2'] || $instaColl['position_left2']) {
                $customDiv .= '<span><img  id="pin-img2" class="pin-img" data-pinno="2"
                src="'.$pin2.'" width="50px" height="50px"
                style="top:'.($instaColl['position_top2']-15).'px;
                left:'.($instaColl['position_left2']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img  id="pin-img2" class="pin-img" data-pinno="2"
                src="'.$pin2.'" width="50px" height="50px"/></span>';
            }
            if ($instaColl['position_top3'] || $instaColl['position_left3']) {
                $customDiv .= '<span><img id="pin-img3" class="pin-img" data-pinno="3"
                src="'.$pin3.'" width="50px" height="50px"
                style="top:'.($instaColl['position_top3']-15).'px;
                left:'.($instaColl['position_left3']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img id="pin-img3" class="pin-img" data-pinno="3"
                src="'.$pin3.'" width="50px" height="50px"/></span>';
            }
            if ($instaColl['position_top4'] || $instaColl['position_left4']) {
                $customDiv .= '<span><img id="pin-img4" class="pin-img"
                src="'.$pin4.'" data-pinno="4" width="50px" height="50px"
                style="top:'.($instaColl['position_top4']-15).'px;
                left:'.($instaColl['position_left4']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img id="pin-img4" class="pin-img"
                src="'.$pin4.'" data-pinno="4" width="50px" height="50px"/></span>';
            }
            if ($instaColl['position_top5'] || $instaColl['position_left5']) {
                $customDiv .= '<span><img id="pin-img5" class="pin-img"
                src="'.$pin5.'" data-pinno="5" width="50px" height="50px"
                style="top:'.($instaColl['position_top5']-15).'px;
                left:'.($instaColl['position_left5']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img id="pin-img5" class="pin-img"
                src="'.$pin5.'" data-pinno="5" width="50px" height="50px"/></span>';
            }
            if ($instaColl['position_top6'] || $instaColl['position_left6']) {
                $customDiv .= '<span><img id="pin-img6" class="pin-img" data-pinno="6"
                src="'.$pin6.'" width="50px" height="50px"
                style="top:'.($instaColl['position_top6']-15).'px;
                left:'.($instaColl['position_left6']-24).'px;"/></span>';
            } else {
                $customDiv .= '<span><img id="pin-img6" class="pin-img" data-pinno="6"
                src="'.$pin6.'" width="50px" height="50px"/></span>';
            }
        
            $customDiv .= self::POINTPINSCRIPT;
        }
        return $customDiv;
    }
}
