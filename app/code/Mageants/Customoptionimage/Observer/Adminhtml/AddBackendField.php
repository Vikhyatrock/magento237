<?php
namespace Mageants\Customoptionimage\Observer\Adminhtml;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer as EventObserver;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\DataType\Media;
use Magento\Framework\UrlInterface;

class AddBackendField implements ObserverInterface
{

    const FIELD_UPLOAD_IMAGE_PREVIEW = 'image_url';

    const FIELD_UPLOAD_IMAGE_BUTTON = 'mageants_image_button';

    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getChild()->addData($this->getCustomImageField());
    }
    protected function getCustomImageField()
    {
        return [
            230 => ['index' => static::FIELD_UPLOAD_IMAGE_PREVIEW, 'field' => $this->getImagePreviewFieldConfig(230)],
            240 => ['index' => static::FIELD_UPLOAD_IMAGE_BUTTON, 'field' => $this->getUploadButtonFieldConfig(240)]
        ];
    }

    protected function getImagePreviewFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Image'),
                        'componentType' => Field::NAME,
                        'component' => 'Mageants_Customoptionimage/js/image_preview',
                        'elementTmpl' => 'Mageants_Customoptionimage/image-preview',
                        'dataScope' => static::FIELD_UPLOAD_IMAGE_PREVIEW,
                        'dataType' => Text::NAME,
                        'formElement' => Checkbox::NAME,
                        'sortOrder' => $sortOrder,
                        'valueMap' => [
                            'true' => '1',
                            'false' => ''
                        ]
                    ],
                ],
            ],
        ];
    }

    protected function getUploadButtonFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'fileUploader',
                        'componentType' => 'file',
                        'component' => 'Mageants_Customoptionimage/js/upload_field',
                        'elementTmpl' => 'Mageants_Customoptionimage/upload-field',
                        'visible' => true,
                        'dataType' => Media::NAME,
                        'required' => false,
                        'label' => __('Upload'),
                        'dataScope' => static::FIELD_UPLOAD_IMAGE_BUTTON,
                        'sortOrder' => $sortOrder,
                        'baseUrl' => $this->urlBuilder->getUrl('mageants_custoption/json/uploader')
                    ],
                ],
            ],
        ];
    }
}
