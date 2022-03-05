<?php
namespace Mageants\Customoptionimage\Block\Render;

class PluginBlock extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageants\Customoptionimage\Helper\ModuleConfig $moduleConfig,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->moduleConfig = $moduleConfig;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        $this->setTemplate('Mageants_Customoptionimage::select/image-render.phtml');
    }

    public function getConfigHelper()
    {
        return $this->moduleConfig;
    }

    public function getImageList()
    {
        $result = [];
        $values = $this->getOption()->getValues();
        foreach ($values as $key => $value) {
            $valueData = $value->getData();
            if ($valueData['image_url']) {
                $result[] = [
                    'id' => $valueData['option_type_id'],
                    'url' => $valueData['image_url'],
                    'title' => $value['title']
                ];
            }
        }
        return $this->jsonEncoder->encode($result);
    }
}
