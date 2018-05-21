<?php

namespace Downloadable\LinkVisibility\Plugin\Ui;

class DataProviderProductFormModifierDataLinks
{
    /**
     * @var \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
     */
    protected $visibilityRepository;

    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterGetLinksData($subject, $result)
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

    public function __construct(
        \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface $visibilityRepository
    ) {
        $this->visibilityRepository = $visibilityRepository;
    }
}