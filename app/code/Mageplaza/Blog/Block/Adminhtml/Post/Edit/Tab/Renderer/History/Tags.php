<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Blog
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer\History;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\Text;
use Magento\Framework\DataObject;
use Mageplaza\Blog\Helper\Data;

/**
 * Class Action
 * @package Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer
 */
class Tags extends Text
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Categories constructor.
     *
     * @param Context $context
     * @param Data $_helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $_helperData,
        array $data = []
    ) {
        $this->_helperData = $_helperData;
        parent::__construct($context, $data);
    }

    /**
     * @param DataObject $row
     *
     * @return string
     */
    public function render(DataObject $row)
    {
        if (!empty($row->getData($this->getColumn()->getIndex()))) {
            $text   = '';
            $tagIds = explode(',', $row->getData($this->getColumn()->getIndex()));
            foreach ($tagIds as $tagId) {
                $tag  = $this->_helperData->getFactoryByType('tag')->create()->load($tagId);
                $text .= $tag->getName() . ',';
            }
            $row->setData($this->getColumn()->getIndex(), trim($text, ','));
        }

        return parent::render($row);
    }
}
