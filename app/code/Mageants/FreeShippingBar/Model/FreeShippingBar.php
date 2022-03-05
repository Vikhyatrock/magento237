<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Mageants\FreeShippingBar\Api\Data\GridInterface;

class FreeShippingBar extends \Magento\Framework\Model\AbstractModel implements IdentityInterface, GridInterface
{
    const CACHE_TAG = 'mageants_freeshippingbar';
    protected $_cacheTag = 'mageants_freeshippingbar';
    protected $_eventPrefix = 'mageants_freeshippingbar';
 
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Mageants\FreeShippingBar\Model\ResourceModel\FreeShippingBar::class);
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getProducts(\Mageants\FreeShippingBar\Model\FreeShippingBar $object)
    {
        $tbl = $this->getResource()->getTable("mageants_freeshippingbar");
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['products']
        )
        ->where(
            'id = ?',
            (int)$object->getId()
        );
        
        $productIds = $this->getResource()->getConnection()->fetchCol($select);
        // $productIds =explode(',', $productIds[0]);
        $productIds = (is_array($productIds) && count($productIds)) ? explode(',', $productIds[0]) : [];
        return $productIds;
    }

    /**
     * Get Id.
     * Set Id
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * Get Name.
     * Set Name
     * @return varchar
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }
    public function setName($Name)
    {
        return $this->setData(self::NAME, $Name);
    }
    
    /**
     * Get Title.
     * Set Title
     * @return varchar
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Priority.
     * Set Priority
     * @return varchar
     */
    public function getPriority()
    {
        return $this->getData(self::PRIORITY);
    }
    public function setPriority($priority)
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    /**
     * Get Priority.
     * Set Priority
     * @return varchar
     */
    public function getStoreview()
    {
        return $this->getData(self::STORE_VIEW);
    }
    public function setStoreview($storeview)
    {
        return $this->setData(self::STORE_VIEW, $storeview);
    }

    /**
     * Get Priority.
     * Set Priority
     * @return varchar
     */
    public function getCustomerGroup()
    {
        return $this->getData(self::CUSTOMER_GROUP);
    }
    public function setCustomerGroup($customer_group)
    {
        return $this->setData(self::CUSTOMER_GROUP, $customer_group);
    }
    /**
     * Get Fromdate.
     * Set Fromdate
     * @return varchar
     */
    public function getFromdate()
    {
        return $this->getData(self::FROM_DATE);
    }
    public function setFromdate($fromdate)
    {
        return $this->setData(self::FROM_DATE, $fromdate);
    }

    /**
     * Get Todate.
     * Set Todate
     * @return varchar
     */
    public function getTodate()
    {
        return $this->getData(self::TO_DATE);
    }
    public function setTodate($fromdate)
    {
        return $this->setData(self::TO_DATE, $fromdate);
    }

    /**
     * Get UPDATED_AT.
     * Set UPDATED_AT
     * @return varchar
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get GOAL.
     * Set GOAL
     * @return varchar
     */
    public function getGoal()
    {
        return $this->getData(self::GOAL);
    }
    public function setGoal($goal)
    {
        return $this->setData(self::GOAL, $goal);
    }

    /**
     * Get FIRST_MESSAGE.
     * Set FIRST_MESSAGE
     * @return varchar
     */
    public function getFirstMessage()
    {
        return $this->getData(self::FIRST_MESSAGE);
    }
    public function setFirstMessage($first_message)
    {
        return $this->setData(self::FIRST_MESSAGE, $first_message);
    }

    /**
     * Get BELOW_GOAL_MESSAGE.
     * Set BELOW_GOAL_MESSAGE
     * @return varchar
     */
    public function getBelowGoalMessage()
    {
        return $this->getData(self::BELOW_GOAL_MESSAGE);
    }
    public function setBelowGoalMessage($below_goal_message)
    {
        return $this->setData(self::BELOW_GOAL_MESSAGE, $below_goal_message);
    }

    /**
     * Get ACHIVE_GOAL_MESSAGE.
     * Set ACHIVE_GOAL_MESSAGE
     * @return varchar
     */
    public function getAchiveGoalMessage()
    {
        return $this->getData(self::ACHIVE_GOAL_MESSAGE);
    }
    public function setAchiveGoalMessage($achive_goal_message)
    {
        return $this->setData(self::ACHIVE_GOAL_MESSAGE, $achive_goal_message);
    }

    /**
     * Get CLICKABLE.
     * Set CLICKABLE
     * @return varchar
     */
    public function getClickable()
    {
        return $this->getData(self::CLICKABLE);
    }
    public function setClickable($clickable)
    {
        return $this->setData(self::CLICKABLE, $clickable);
    }

    /**
     * Get LINK_URL.
     * Set LINK_URL
     * @return varchar
     */
    public function getLinkUrl()
    {
        return $this->getData(self::LINK_URL);
    }
    public function setLinkUrl($link_url)
    {
        return $this->setData(self::LINK_URL, $link_url);
    }

    /**
     * Get OPEN_IN_NEW_PAGE.
     * Set OPEN_IN_NEW_PAGE
     * @return varchar
     */
    public function getOpenInNewPage()
    {
        return $this->getData(self::OPEN_IN_NEW_PAGE);
    }
    public function setOpenInNewpage($open_in_new_page)
    {
        return $this->setData(self::OPEN_IN_NEW_PAGE, $open_in_new_page);
    }

    /**
     * Get TEMPLATE.
     * Set TEMPLATE
     * @return varchar
     */
    public function getTemplate()
    {
        return $this->getData(self::TEMPLATE);
    }
    public function setTemplate($template)
    {
        return $this->setData(self::TEMPLATE, $template);
    }

    /**
     * Get BAR_BACKGROUND_OPACITY.
     * Set BAR_BACKGROUND_OPACITY
     * @return varchar
     */
    public function getBarBackgroundOpacity()
    {
        return $this->getData(self::BAR_BACKGROUND_OPACITY);
    }
    public function setBarBackgroundOpacity($bar_background_opacity)
    {
        return $this->setData(self::BAR_BACKGROUND_OPACITY, $bar_background_opacity);
    }

    /**
     * Get BAR_BACKGROUND_COLOR.
     * Set BAR_BACKGROUND_COLOR
     * @return varchar
     */
    public function getBarBackgroundColor()
    {
        return $this->getData(self::BAR_BACKGROUND_COLOR);
    }
    public function setBarBackgroundColor($bar_background_opacity)
    {
        return $this->setData(self::BAR_BACKGROUND_COLOR, $bar_background_opacity);
    }

    /**
     * Get BAR_TEXT_COLOR.
     * Set BAR_TEXT_COLOR
     * @return varchar
     */
    public function getBarTextColor()
    {
        return $this->getData(self::BAR_TEXT_COLOR);
    }
    public function setBarTextColor($bar_text_color)
    {
        return $this->setData(self::BAR_TEXT_COLOR, $bar_text_color);
    }

    /**
     * Get BAR_LINK_COLOR.
     * Set BAR_LINK_COLOR
     * @return varchar
     */
    public function getBarLinkColor()
    {
        return $this->getData(self::BAR_LINK_COLOR);
    }
    public function setBarLinkColor($bar_link_color)
    {
        return $this->setData(self::BAR_LINK_COLOR, $bar_link_color);
    }

    /**
     * Get GOAL_TEXT_COLOR.
     * Set GOAL_TEXT_COLOR
     * @return varchar
     */
    public function getGoalTextColor()
    {
        return $this->getData(self::GOAL_TEXT_COLOR);
    }
    public function setGoalTextColor($goal_text_color)
    {
        return $this->setData(self::GOAL_TEXT_COLOR, $goal_text_color);
    }

    /**
     * Get IMAGE.
     * Set IMAGE
     * @return varchar
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Get FONTS.
     * Set FONTS
     * @return varchar
     */
    public function getFonts()
    {
        return $this->getData(self::FONTS);
    }
    public function setFonts($fonts)
    {
        return $this->setData(self::FONTS, $fonts);
    }
    
    /**
     * Get FONT_SIZE.
     * Set FONT_SIZE
     * @return varchar
     */
    public function getFontSize()
    {
        return $this->getData(self::FONT_SIZE);
    }
    public function setFontSize($font_size)
    {
        return $this->setData(self::FONT_SIZE, $font_size);
    }

    /**
     * Get POSITION.
     * Set POSITION
     * @return varchar
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Get ALLOWED_PAGE.
     * Set ALLOWED_PAGE
     * @return varchar
     */
    public function getAllowedPage()
    {
        return $this->getData(self::ALLOWED_PAGE);
    }
    public function setAllowedPage($allow_page)
    {
        return $this->setData(self::ALLOWED_PAGE, $allow_page);
    }

    /**
     * Get SPECIFIC_PAGE_TO_SHOW.
     * Set SPECIFIC_PAGE_TO_SHOW
     * @return varchar
     */
    public function getSpecificPage()
    {
        return $this->getData(self::SPECIFIC_PAGE_TO_SHOW);
    }
    public function setSpecificPage($specific_page_to_show)
    {
        return $this->setData(self::SPECIFIC_PAGE_TO_SHOW, $specific_page_to_show);
    }

    /**
     * Get SPECIFIC_PAGE_URL.
     * Set SPECIFIC_PAGE_URL
     * @return varchar
     */
    public function getSpecificPageUrl()
    {
        return $this->getData(self::SPECIFIC_PAGE_URL);
    }
    public function setSpecificPageUrl($specific_page_to_show)
    {
        return $this->setData(self::SPECIFIC_PAGE_URL, $specific_page_to_show);
    }

    /**
     * Get EXCLUDE_PAGE.
     * Set EXCLUDE_PAGE
     * @return varchar
     */
    public function getExcludePage()
    {
        return $this->getData(self::EXCLUDE_PAGE);
    }
    public function setExcludePage($exclude_page)
    {
        return $this->setData(self::EXCLUDE_PAGE, $exclude_page);
    }

    /**
     * Get EXCLUDE_PAGE_URL.
     * Set EXCLUDE_PAGE_URL
     * @return varchar
     */
    public function getExcludePageUrl()
    {
        return $this->getData(self::EXCLUDE_PAGE_URL);
    }
    public function setExcludePageUrl($exclude_page_url)
    {
        return $this->setData(self::EXCLUDE_PAGE_URL, $exclude_page_url);
    }

    /**
     * Get Products.
     * Set Products
     * @return varchar
     */
    public function getSelectedProducts()
    {
        return $this->getData(self::PRODUCTS);
    }
    public function setSelectedProducts($products)
    {
        return $this->setData(self::PRODUCTS, $products);
    }
}
