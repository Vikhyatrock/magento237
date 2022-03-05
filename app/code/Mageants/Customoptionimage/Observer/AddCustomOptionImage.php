<?php
namespace Mageants\Customoptionimage\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer as EventObserver;
use Mageants\Customoptionimage\Ui\DataProvider\Product\Form\Modifier\MageantsCustomOptions;

class AddCustomOptionImage implements ObserverInterface
{
    public $imageSaving;

    public $moduleConfig;

    public $version;

    public function __construct(
        \Mageants\Customoptionimage\Helper\ImageSaving $imageSaving,
        \Mageants\Customoptionimage\Helper\ModuleConfig $moduleConfig,
        \Magento\Framework\App\ProductMetadataInterface $version
    ) {
        $this->moduleConfig = $moduleConfig;
        $this->imageSaving = $imageSaving;
        $this->version = $version;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->imageSaving->saveImage(
            $this->moduleConfig->isModuleEnable(),
            $observer->getData('controller')->getRequest()->getPost('product'),
            $observer->getData('product')->getEntityId()
        );
    }
}
