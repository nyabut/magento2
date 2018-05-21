<?php

namespace Downloadable\LinkVisibility\Model;

use \Downloadable\LinkVisibility\Api\Data\VisibilityInterface as VisibilityInterface;
use \Magento\Framework\Model\AbstractModel;

class Visibility extends AbstractModel implements VisibilityInterface
{
    const LINK_ID = 'link_id';
    const VALUE = 'value';
    const ID = 'entity_id';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'downloadable_link_visibility';

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($type)
    {
        return $this->setData(self::VALUE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkId()
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setLinkId($id)
    {
        return $this->setData(self::LINK_ID, $id);
    }

    /**
     * Constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        return parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Constructor for model
     */
    protected function _construct()
    {
        $this->_init(\Downloadable\LinkVisibility\Model\ResourceModel\Visibility::class);
    }



}