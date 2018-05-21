<?php

namespace Downloadable\LinkVisibility\Ui\DataProvider\Product\Form\Modifier;

use Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Links as DownloadableLinks;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Downloadable\Model\Source\TypeUpload;
use Magento\Downloadable\Model\Source\Shareable;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\DynamicRows;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form;
use Magento\Config\Model\Config\Source\Yesno;

class Links extends DownloadableLinks
{
    /**
     * @var Yesno
     */
    protected $yesNo;

    /**
     * @param LocatorInterface $locator
     * @param StoreManagerInterface $storeManager
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     * @param TypeUpload $typeUpload
     * @param Shareable $shareable
     * @param Data\Links $linksData
     * @param Yesno $yesno
     */
    public function __construct(
        LocatorInterface $locator,
        StoreManagerInterface $storeManager,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        TypeUpload $typeUpload,
        Shareable $shareable,
        \Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Data\Links $linksData,
        Yesno $yesNo
    ) {
        $this->locator = $locator;
        $this->storeManager = $storeManager;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->typeUpload = $typeUpload;
        $this->shareable = $shareable;
        $this->linksData = $linksData;
        $this->yesNo = $yesNo;
    }

    /**
     * @return array
     */
    protected function getRecord()
    {
        $record['arguments']['data']['config'] = [
            'componentType' => Container::NAME,
            'isTemplate' => true,
            'is_collection' => true,
            'component' => 'Magento_Ui/js/dynamic-rows/record',
            'dataScope' => '',
        ];
        $recordPosition['arguments']['data']['config'] = [
            'componentType' => Form\Field::NAME,
            'formElement' => Form\Element\Input::NAME,
            'dataType' => Form\Element\DataType\Number::NAME,
            'dataScope' => 'sort_order',
            'visible' => false,
        ];
        $recordActionDelete['arguments']['data']['config'] = [
            'label' => null,
            'componentType' => 'actionDelete',
            'fit' => true,
        ];

        return $this->arrayManager->set(
            'children',
            $record,
            [
                'container_link_title' => $this->getTitleColumn(),
                'container_link_price' => $this->getPriceColumn(),
                'container_file' => $this->getFileColumn(),
                'container_sample' => $this->getSampleColumn(),
                'visibility' => $this->getVisibilityColumn(),
                'is_shareable' => $this->getShareableColumn(),
                'max_downloads' => $this->getMaxDownloadsColumn(),
                'position' => $recordPosition,
                'action_delete' => $recordActionDelete,
            ]
        );
    }

    protected function getVisibilityColumn()
    {
        $visibilityField['arguments']['data']['config'] = [
            'label' => __('Is Visible'),
            'formElement' => Form\Element\Select::NAME,
            'componentType' => Form\Field::NAME,
            'dataType' => Form\Element\DataType\Number::NAME,
            'dataScope' => 'visibility',
            'options' => $this->yesNo->toOptionArray(),
        ];

        return $visibilityField;
    }
}