<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Api\Data;

interface GridInterface
{
    const ID = 'id';
    const NAME = 'name';
    const STATUS = 'status';
    const PRIORITY = 'priority';
    const STORE_VIEW = 'storeview';
    const CUSTOMER_GROUP = 'customer_group';
    const FROM_DATE = 'fromdate';
    const TO_DATE = 'todate';
    const UPDATED_AT = 'updatedat';
    const GOAL = 'goal';
    const FIRST_MESSAGE = 'first_message';
    const BELOW_GOAL_MESSAGE = 'below_goal_message';
    const ACHIVE_GOAL_MESSAGE = 'achive_goal_message';
    const CLICKABLE = 'clickable';
    const LINK_URL = 'link_url';
    const OPEN_IN_NEW_PAGE = 'open_in_new_page';
    const TEMPLATE = 'template';
    const BAR_BACKGROUND_OPACITY = 'bar_background_opacity';
    const BAR_BACKGROUND_COLOR = 'bar_background_color';
    const BAR_TEXT_COLOR = 'bar_text_color';
    const BAR_LINK_COLOR = 'bar_link_color';
    const GOAL_TEXT_COLOR = 'goal_text_color';
    const IMAGE = 'image';
    const FONTS = 'fonts';
    const FONT_SIZE = 'font_size';
    const POSITION = 'position';
    const ALLOWED_PAGE = 'allow_page';
    const SPECIFIC_PAGE_TO_SHOW = 'specific_page_to_show';
    const SPECIFIC_PAGE_URL = 'specific_page_url';
    const EXCLUDE_PAGE = 'exclude_page';
    const EXCLUDE_PAGE_URL = 'exclude_page_url';
    const PRODUCTS = 'products';
 
    public function getId();
    public function setId($Id);
    
    public function getName();
    public function setName($Name);

    public function getStatus();
    public function setStatus($status);

    public function getPriority();
    public function setPriority($priority);

    public function getStoreview();
    public function setStoreview($storeview);

    public function getCustomerGroup();
    public function setCustomerGroup($customer_group);
 
    public function getFromdate();
    public function setFromdate($fromdate);

    public function getTodate();
    public function setTodate($todate);

    public function getUpdatedAt();
    public function setUpdatedAt($updatedAt);

    public function getGoal();
    public function setGoal($goal);

    public function getFirstMessage();
    public function setFirstMessage($first_message);

    public function getBelowGoalMessage();
    public function setBelowGoalMessage($below_goal_message);

    public function getAchiveGoalMessage();
    public function setAchiveGoalMessage($achive_goal_message);

    public function getClickable();
    public function setClickable($clickable);

    public function getLinkUrl();
    public function setLinkUrl($link_url);

    public function getOpenInNewPage();
    public function setOpenInNewpage($open_in_new_page);

    public function getTemplate();
    public function setTemplate($template);

    public function getBarBackgroundOpacity();
    public function setBarBackgroundOpacity($bar_background_opacity);

    public function getBarBackgroundColor();
    public function setBarBackgroundColor($bar_background_color);

    public function getBarTextColor();
    public function setBarTextColor($bar_text_color);

    public function getBarLinkColor();
    public function setBarLinkColor($bar_link_color);

    public function getGoalTextColor();
    public function setGoalTextColor($goal_text_color);

    public function getImage();
    public function setImage($image);

    public function getFonts();
    public function setFonts($fonts);

    public function getFontSize();
    public function setFontSize($font_size);

    public function getPosition();
    public function setPosition($position);

    public function getAllowedPage();
    public function setAllowedPage($allow_page);

    public function getSpecificPage();
    public function setSpecificPage($specific_page_to_show);

    public function getSpecificPageUrl();
    public function setSpecificPageUrl($specific_page_url);

    public function getExcludePage();
    public function setExcludePage($exclude_page);

    public function getExcludePageUrl();
    public function setExcludePageUrl($exclude_page_url);

    public function getSelectedProducts();
    public function setSelectedProducts($products);
}
