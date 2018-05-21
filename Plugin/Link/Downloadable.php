<?php

namespace Downloadable\LinkVisibility\Plugin\Link;

use Magento\Framework\App\RequestInterface as RequestInterface;
use Magento\Downloadable\Model\Link\Builder as LinkBuilder;
use Magento\Downloadable\Model\Sample\Builder as SampleBuilder;
use Magento\Downloadable\Api\Data\SampleInterfaceFactory as SampleInterfaceFactory;
use Magento\Downloadable\Api\Data\LinkInterfaceFactory as LinkInterfaceFactory;

class Downloadable
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var SampleInterfaceFactory
     */
    private $sampleFactory;

    /**
     * @var LinkInterfaceFactory
     */
    private $linkFactory;

    /**
     * @var SampleBuilder
     */
    private $sampleBuilder;

    /**
     * @var LinkBuilder
     */
    private $linkBuilder;

    /**
     * @var \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
     */
    private $visibilityRepository;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param LinkBuilder $linkBuilder
     * @param SampleBuilder $sampleBuilder
     * @param SampleInterfaceFactory $sampleFactory
     * @param LinkInterfaceFactory $linkFactory
     */
    public function __construct(
        RequestInterface $request,
        LinkBuilder $linkBuilder,
        SampleBuilder $sampleBuilder,
        SampleInterfaceFactory $sampleFactory,
        LinkInterfaceFactory $linkFactory,
        \Magento\Downloadable\Api\Data\LinkExtensionFactory $linkExtensionFactory,
        \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory $visibilityInterfaceFactory,
        \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepositoryInterface
    ) {
        $this->request = $request;
        $this->linkBuilder = $linkBuilder;
        $this->sampleBuilder = $sampleBuilder;
        $this->sampleFactory = $sampleFactory;
        $this->linkFactory = $linkFactory;
        $this->linkExtensionFactory = $linkExtensionFactory;
        $this->visibilityInterfaceFactory = $visibilityInterfaceFactory;
        $this->visibilityRepository = $visibilityRepositoryInterface;
    }

    /**
     * Prepare product to save
     *
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $subject
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function afterInitialize(
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $subject,
        \Magento\Catalog\Model\Product $product
    ) {
        if ($downloadable = $this->request->getPost('downloadable')) {
            $product->setDownloadableData($downloadable);
            $extension = $product->getExtensionAttributes();
            if (isset($downloadable['link']) && is_array($downloadable['link'])) {
                $links = [];
                foreach ($downloadable['link'] as $linkData) {
                    if (!$linkData || (isset($linkData['is_delete']) && $linkData['is_delete'])) {
                        continue;
                    } else {
                        $link = $this->linkBuilder->setData(
                            $linkData
                        )->build(
                            $this->linkFactory->create()
                        );

                        $extensionAttributes = $link->getExtensionAttributes();
                        $linkExtension = $extensionAttributes ? $extensionAttributes : $this->linkExtensionFactory->create();

                        if (array_key_exists('link_id', $linkData)) {
                            try {
                                $visibility = $this->visibilityRepository->getByLinkId($link->getId());

                            } catch (NoSuchEntityException $e) {
                                $visibility = $this->visibilityInterfaceFactory->create();
                                $visibility->setLinkId($link->getId());
                                $visibility->setValue(1);
                            }
                        } else {
                            $visibility = $this->visibilityInterfaceFactory->create();
                        }

                        $visibility->setValue($linkData['visibility']);
                        $linkExtension->setVisibility($visibility);
                        $link->setExtensionAttributes($linkExtension);

                        $links[] = $link;
                    }
                }
                $extension->setDownloadableProductLinks($links);
            }
            if (isset($downloadable['sample']) && is_array($downloadable['sample'])) {
                $samples = [];
                foreach ($downloadable['sample'] as $sampleData) {
                    if (!$sampleData || (isset($sampleData['is_delete']) && (bool)$sampleData['is_delete'])) {
                        continue;
                    } else {
                        $samples[] = $this->sampleBuilder->setData(
                            $sampleData
                        )->build(
                            $this->sampleFactory->create()
                        );
                    }
                }
                $extension->setDownloadableProductSamples($samples);
            }
            $product->setExtensionAttributes($extension);
            if ($product->getLinksPurchasedSeparately()) {
                $product->setTypeHasRequiredOptions(true)->setRequiredOptions(true);
            } else {
                $product->setTypeHasRequiredOptions(false)->setRequiredOptions(false);
            }
        }
        return $product;
    }
}
