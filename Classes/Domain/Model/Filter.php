<?php

namespace Pixelant\PxaProductManager\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Filter
 */
class Filter extends AbstractEntity
{
    /**
     * Conjunctions as string
     */
    const CONJUNCTION_OR = 'or';
    const CONJUNCTION_AND = 'and';

    /**
     * categories type
     */
    const TYPE_CATEGORIES = 1;

    /**
     * attributes type
     */
    const TYPE_ATTRIBUTES = 2;

    /**
     * attributes type minmax
     */
    const TYPE_ATTRIBUTES_MINMAX = 3;

    /**
     * type
     *
     * @var int
     */
    protected $type = 1;

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * parentCategory
     *
     * @var \Pixelant\PxaProductManager\Domain\Model\Category
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $parentCategory;

    /**
     * attribute
     *
     * @var \Pixelant\PxaProductManager\Domain\Model\Attribute
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $attribute;

    /**
     * label
     *
     * @var \string
     */
    protected $label = '';

    /**
     * @var bool
     */
    protected $inverseConjunction = false;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the parentCategory
     *
     * @return \Pixelant\PxaProductManager\Domain\Model\Category parentCategory
     */
    public function getParentCategory()
    {
        if ($this->parentCategory instanceof LazyLoadingProxy) {
            $this->parentCategory->_loadRealInstance();
        }
        return $this->parentCategory;
    }

    /**
     * Sets the parentCategory
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Category $parentCategory
     * @return void
     */
    public function setParentCategory(\Pixelant\PxaProductManager\Domain\Model\Category $parentCategory)
    {
        $this->parentCategory = $parentCategory;
    }

    /**
     * Returns the type
     *
     * @return int $type
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     * @return void
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * Returns the attribute
     *
     * @return \Pixelant\PxaProductManager\Domain\Model\Attribute $attribute
     */
    public function getAttribute()
    {
        if ($this->attribute instanceof LazyLoadingProxy) {
            $this->attribute->_loadRealInstance();
        }
        return $this->attribute;
    }

    /**
     * Sets the attribute
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Attribute $attribute
     * @return void
     */
    public function setAttribute(\Pixelant\PxaProductManager\Domain\Model\Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Returns the label
     *
     * @return \string $label
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Sets the label
     *
     * @param \string $label
     * @return void
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function isInverseConjunction(): bool
    {
        return $this->inverseConjunction;
    }

    /**
     * @param bool $inverseConjunction
     */
    public function setInverseConjunction(bool $inverseConjunction): void
    {
        $this->inverseConjunction = $inverseConjunction;
    }

    /**
     * Return filter conjunction as string
     *
     * @return string
     */
    public function getConjunctionAsString(): string
    {
        return $this->isInverseConjunction() ? self::CONJUNCTION_AND : self::CONJUNCTION_OR;
    }
}
