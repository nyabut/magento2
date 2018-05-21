<?php

namespace Downloadable\LinkVisibility\Model;

use \Magento\Framework\Api\SearchCriteriaInterface as SearchCriteriaInterface;
use \Downloadable\LinkVisibility\Api\Data\VisibilityInterface as VisibilityInterface;
use \Downloadable\LinkVisibility\Model\ResourceModel\Visibility\CollectionFactory as VisibilityCollectionFactory;
use \Downloadable\LinkVisibility\Api\VisibilityInterfaceFactory;
use \Magento\Framework\Exception\NoSuchEntityException;

class VisibilityRepository implements \Downloadable\LinkVisibility\Api\VisibilityRepositoryInterface
{
    /**
     * @var VisibilityInterfaceFactory
     */
    protected $visibilityFactory;

    /**
     * @var VisibilityCollectionFactory
     */
    protected $visibilityCollectionFactory;

    /**
     * @param int $id
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByLinkId($id)
    {
        $visibility = $this->visibilityFactory->create();
        $visibility->getResource()->load($visibility, $id, 'link_id');
        if (!$visibility->getId()) {
            throw new NoSuchEntityException(__('Unable to find visibility with ID "%1"', $id));
        }

        return $visibility;
    }

    /**
     * @param \Downloadable\LinkVisibility\Api\Data\VisibilityInterface $visibility
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface
     */
    public function save(VisibilityInterface $visibility)
    {
        $visibility->getResource()->save($visibility);

        return $visibility;
    }

    /**
     * Deletes the visibility
     *
     * @param VisibilityInterface $visibility
     */
    public function delete(VisibilityInterface $visibility)
    {
        $visibility->getResource()->delete($visibility);
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Downloadable\LinkVisibility\Api\Data\VisibilityInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        return $collection->getItems();
    }

    /**
     * Adds filters to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Add sort orders to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * Add paging to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * VisibilityRepository constructor.
     * @param \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory $visibilityFactory
     * @param \Downloadable\LinkVisibility\Model\ResourceModel\Visibility\CollectionFactory $visibilityCollectionFactory
     */
    public function __construct(
        \Downloadable\LinkVisibility\Api\Data\VisibilityInterfaceFactory $visibilityFactory,
        \Downloadable\LinkVisibility\Model\ResourceModel\Visibility\CollectionFactory $visibilityCollectionFactory
    ) {
        $this->visibilityFactory = $visibilityFactory;
        $this->visibilityCollectionFactory = $visibilityCollectionFactory;
    }
}