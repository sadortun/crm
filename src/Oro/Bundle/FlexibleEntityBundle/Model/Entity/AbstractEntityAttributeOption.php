<?php
namespace Oro\Bundle\FlexibleEntityBundle\Model\Entity;

/**
 * Abstract entity attribute option, independent of storage
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
abstract class AbstractEntityAttributeOption
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var boolean $translatable
     */
    protected $translatable;

    /**
     * @var integer $sortOrder
     */
    protected $sortOrder;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return AbstractEntityAttributeOption
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set translatable
     *
     * @param boolean $translatable
     *
     * @return AbstractEntityAttributeOption
     */
    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }

    /**
     * Get translatable
     *
     * @return boolean $translatable
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * Set sort order
     *
     * @param string $sortOrder
     *
     * @return AbstractEntityAttributeOption
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sort order
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

}
