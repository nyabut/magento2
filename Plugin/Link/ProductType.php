<?php

namespace Downloadable\LinkVisibility\Plugin\Link;

use \Magento\Framework\Exception\NoSuchEntityException;

class ProductType
{
    /**
     * @var \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
     */
    protected $visibilityRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * ProductType constructor.
     * @param \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepositoryInterface
     */
    public function __construct(
        \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepositoryInterface,
        \Magento\Framework\App\RequestInterface $requestInterface
    )
    {
        $this->visibilityRepository = $visibilityRepositoryInterface;
        $this->request = $requestInterface;
    }

    /**
     * Overrides the getLinks method
     *
     * @param \Magento\Downloadable\Model\Product\Type $subject
     * @param callable $proceed
     * @param $product
     * @return array
     */
    public function aroundGetLinks(
        \Magento\Downloadable\Model\Product\Type $subject,
        callable $proceed,
        $product
    )
    {
        $allowedLinkIds = [];
        $linkIds = $proceed($product);

        foreach ($linkIds as $linkId => $link) {
            try {
                $visibility = $this->visibilityRepository->getByLinkId($linkId);

                if ( ! $visibility->getValue()) {
                    if ('catalog' != $this->request->getModuleName()) {
                        continue;
                    }
                }
            } catch (NoSuchEntityException $e) {
            }
            $allowedLinkIds[$linkId] = $link;
        }
        $product->setDownloadableLinks($allowedLinkIds);
        return $allowedLinkIds;
    }
}
