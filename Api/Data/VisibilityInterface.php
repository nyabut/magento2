<?php

namespace Downloadable\LinkVisibility\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface VisibilityInterface extends ExtensibleDataInterface
{
    /**
     * Gets the visibility value
     *
     * @return int
     */
    public function getValue();

    /**
     * Sets the visibility value
     *
     * @param $type int
     *
     * @return int
     */
    public function setValue($type);

    /**
     * Gets the entity id
     *
     * @return int
     */
    public function getId();

    /**
     * Sets the entity id
     *
     * @param $id int
     *
     * @return null
     */
    public function setId($id);

    /**
     * Gets the link id
     *
     * @return int
     */
    public function getLinkId();

    /**
     * Sets the link id
     *
     * @param $linkId int
     *
     * @return null
     */
    public function setLinkId($linkId);

}