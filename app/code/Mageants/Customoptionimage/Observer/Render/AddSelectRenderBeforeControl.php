<?php
namespace Mageants\Customoptionimage\Observer\Render;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer as EventObserver;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\DataType\Media;
use Magento\Framework\UrlInterface;

class AddSelectRenderBeforeControl implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getChild()->addData(['coi_select_control' => 'Mageants\Customoptionimage\Block\Render\PluginBlock']);
    }
}
