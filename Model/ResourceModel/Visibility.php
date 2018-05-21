<?php
namespace Downloadable\LinkVisibility\Model\ResourceModel;


class Visibility extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('downloadable_link_visibility', 'entity_id');
    }

    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$this->isValidValue($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The visibility value is not valid.')
            );
        }

        return parent::_beforeSave($object);
    }

    /**
     * Load an object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        return parent::load($object, $value, $field);
    }

    /**
     *  Check whether value is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidValue(\Magento\Framework\Model\AbstractModel $object)
    {
        $val = (int) $object->getData('value');

        if ($val !== 0 AND $val !== 1) {
            return false;
        }

        return true;
    }
}