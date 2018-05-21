<?php

namespace Downloadable\LinkVisibility\Api;

use \Downloadable\LinkVisibility\Api\Data\VisibilityInterface as VisibilityInterface;
use \Magento\Framework\Api\SearchCriteriaInterface as SearchCriteriaInterface;

interface VisibilityRepositoryInterface
{
    /**
     * @param int $id
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByLinkId($id);

    /**
     * @param \Downloadable\LinkVisibility\Api\Data\VisibilityInterface $visibility
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface
     */
    public function save(VisibilityInterface $visibility);

    /**
     * @param \Downloadable\LinkVisibility\Api\Data\VisibilityInterface $visibility
     * @return void
     */
    public function delete(VisibilityInterface $visibility);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}