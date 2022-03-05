<?php
namespace Mageants\Customoptionimage\Plugin;

class OptionValuePlugin
{
    private $imageSaving;

    private $imageUrlFactory;

    public function __construct(
        \Mageants\Customoptionimage\Helper\ImageSaving $imageSaving,
        \Mageants\Customoptionimage\Model\ImageUrlFactory $imageUrlFactory
    ) {
        $this->imageSaving = $imageSaving;
        $this->imageUrlFactory = $imageUrlFactory;
    }

    public function aroundSave(
        \Magento\Catalog\Model\Product\Option\Value $subject,
        $proceed
    ) {
        $imageUrl = $this->imageSaving->moveImage($subject);
        $proceed();
        $imageUrlModel = $this->imageUrlFactory->create()->getCollection()
        ->getItemByColumnValue('option_type_id', $subject->getOptionTypeId());
        if (!$imageUrlModel) {
            $imageUrlModel = $this->imageUrlFactory->create();
        }
        $imageUrlModel
        ->setOptionTypeId($subject->getOptionTypeId())
        ->setImageUrl($imageUrl)
        ->save();
    }

    public function aroundGetData(
        \Magento\Catalog\Model\Product\Option\Value $subject,
        $proceed,
        $key = '',
        $index = null
    ) {
        $result = $proceed($key, $index);
        if ($key === '') {
            if (isset($result['option_type_id']) && !isset($result['image_url'])) {
                $imageData = $this->imageUrlFactory->create()->getCollection()
                ->getItemByColumnValue('option_type_id', $result['option_type_id']);
                if ($imageData) {
                    $imageData = $imageData->getImageUrl();
                }
                $result['image_url'] = $imageData;
            }
        }
        if ($key === 'image_url' && $subject->getData('option_type_id') && !$subject->hasData('image_url')) {
            $imageData = $this->imageUrlFactory->create()->getCollection()
            ->getItemByColumnValue('option_type_id', $subject->getData('option_type_id'));
            if ($imageData) {
                $imageData = $imageData->getImageUrl();
            }
            return $imageData;
        }
        return $result;
    }
}
