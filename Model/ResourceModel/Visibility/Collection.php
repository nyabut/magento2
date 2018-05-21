<?php
namespace Downloadable\LinkVisibility\Model\ResourceModel\Visibility;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'link_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Downloadable\LinkVisibility\Model\Visibility',
            'Downloadable\LinkVisibility\Model\ResourceModel\Visibility'
        );
    }

}