<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

/**
 * Class Form
 * @package Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit
 */
class Form extends Generic
{
    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
            'data' => [
                'id' => 'edit_form',
                'enctype' =>'multipart/form-data',
                'action' => $this->getUrl('productimage/grid/update'),
                'method' => 'POST',
                ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
