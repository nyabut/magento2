<?php

namespace Downloadable\LinkVisibility\Plugin\Ui;

class DataProviderProductFormModifierDataLinks
{
    /**
     * @var \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
     */
    protected $visibilityRepository;

    /**
     * Modifies links to include visibility
     *
     * @param \Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Data\Links $subject
     * @param array $result
     * @return array
     */
    public function afterGetLinksData(
        \Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Data\Links $subject,
        array $result
    )
    {
        foreach ($result as &$link) {
            $linkId = $link['link_id'];
            $link['visibility'] = 1;

            try {
                $visibility = $this->visibilityRepository->getByLinkId($linkId);

                $link['visibility'] = $visibility->getValue();
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {

            }
        }

        return $result;
    }

    /**
     * Constructor
     *
     * DataProviderProductFormModifierDataLinks constructor.
     * @param \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepository
     */
    public function __construct(
        \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepository
    ) {
        $this->visibilityRepository = $visibilityRepository;
    }
}