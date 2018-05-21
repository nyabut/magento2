<?php

namespace Downloadable\LinkVisibility\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface VisibilityInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getValue();

    /**
     * @param $type int
     *
     * @return int
     */
    public function setValue($type);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id int
     *
     * @return null
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getLinkId();

    /**
     * @param $linkId int
     *
     * @return null
     */
    public function setLinkId($linkId);

}