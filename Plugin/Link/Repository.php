<?php

namespace Downloadable\LinkVisibility\Plugin\Link;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Downloadable\Api\Data\LinkInterface as LinkInterface;

class Repository
{

    /**
     * @var \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
     */
    protected $visibilityRepository;

    /**
     * @var \Magento\Downloadable\Api\Data\LinkExtensionFactory
     */
    protected $linkExtensionFactory;

    /**
     * @var \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory
     */
    protected $visibilityInterfaceFactory;

    /**
     * @var \Magento\Downloadable\Api\LinkRepositoryInterface
     */
    protected $linkRepository;

    /**
     * Runs after link repository get
     *
     * @param \Magento\Downloadable\Api\LinkRepositoryInterface $subject
     * @param \Magento\Downloadable\Api\Data\LinkInterface $link
     * @return \Magento\Downloadable\Api\Data\LinkInterface
     */
    public function afterGet(
        \Magento\Downloadable\Api\LinkRepositoryInterface $subject,
        \Magento\Downloadable\Api\Data\LinkInterface $link
    )
    {
        $extensionAttributes = $link->getExtensionAttributes();
        $linkExtension = $extensionAttributes ? $extensionAttributes : $this->linkExtensionFactory->create();

        try {
            $visibility = $this->visibilityRepository->getByLinkId($link->getEntityId());
            $linkExtension->setVisibility($visibility);
            $link->setExtensionAttributes($linkExtension);

        } catch (NoSuchEntityException $e) {
        }

        return $link;
    }

    /**
     * Lists links that match specified search criteria.
     *
     * @return \Magento\Downloadable\Api\Data\LinkInterface[]
     */
    public function afterGetList(
        \Magento\Downloadable\Api\LinkRepositoryInterface $subject,
        array $result
    )
    {
        foreach ($result as $link) {
            $extensionAttributes = $link->getExtensionAttributes();
            $linkExtension = $extensionAttributes ? $extensionAttributes : $this->linkExtensionFactory->create();

            try {
                $visibility = $this->visibilityRepository->getByLinkId($link->getId());

            } catch (NoSuchEntityException $e) {
                $visibility = $this->visibilityInterfaceFactory->create();
                $visibility->setLinkId($link->getId());
                $visibility->setValue(1);
            }

            $linkExtension->setVisibility($visibility);
            $link->setExtensionAttributes($linkExtension);
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     */
    public function afterGetLinksByProduct($subject, $result)
    {
        foreach ($result as $link) {
            $extensionAttributes = $link->getExtensionAttributes();
            $linkExtension = $extensionAttributes ? $extensionAttributes : $this->linkExtensionFactory->create();

            try {
                $visibility = $this->visibilityRepository->getByLinkId($link->getId());

            } catch (NoSuchEntityException $e) {
                $visibility = $this->visibilityInterfaceFactory->create();
                $visibility->setLinkId($link->getId());
                $visibility->setValue(1);
            }

            $linkExtension->setVisibility($visibility);
            $link->setExtensionAttributes($linkExtension);
        }

        return $result;
    }

    /**
     * Runs after link save
     *
     * @param \Magento\Downloadable\Api\LinkRepositoryInterface $subject
     * @param \Magento\Downloadable\Api\Data\LinkInterface $link
     * @return \Magento\Downloadable\Api\Data\LinkInterface
     * @throws CouldNotSaveException
     */
    public function aroundSave(
        \Magento\Downloadable\Api\LinkRepositoryInterface $subject,
        callable $proceed,
        $sku,
        LinkInterface $link,
        $isGlobalScopeContent = true
    ) {
        $linkId = $proceed($sku, $link, $isGlobalScopeContent);

        $extensionAttributes = $link->getExtensionAttributes();

        if (
            null !== $extensionAttributes &&
            null !== $extensionAttributes->getVisibility()
        ) {
            $visibility = $extensionAttributes->getVisibility();
            $visibility->setLinkId($linkId);
            $this->visibilityRepository->save($visibility);
        }

        return $linkId;
    }

    /**
     * Repository constructor.
     * @param \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepository
     * @param \Magento\Downloadable\Api\Data\LinkExtensionFactory $linkExtensionFactory
     * @param \Magento\Downloadable\Api\LinkRepositoryInterface $linkRepositoryInterface
     * @param \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory $visibilityInterfaceFactory
     */
    public function __construct(
        \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepository,
        \Magento\Downloadable\Api\Data\LinkExtensionFactory $linkExtensionFactory,
        \Magento\Downloadable\Api\LinkRepositoryInterface $linkRepositoryInterface,
        \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory $visibilityInterfaceFactory
    )
    {
        $this->visibilityRepository = $visibilityRepository;
        $this->linkExtensionFactory = $linkExtensionFactory;
        $this->visibilityInterfaceFactory = $visibilityInterfaceFactory;
        $this->linkRepository = $linkRepositoryInterface;
    }
}