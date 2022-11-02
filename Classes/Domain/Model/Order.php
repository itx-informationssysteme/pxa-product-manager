<?php
declare(strict_types=1);

namespace Pixelant\PxaProductManager\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014
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
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Order
 *
 * @package Pixelant\PxaProductManager\Domain\Model
 */
class Order extends AbstractEntity
{
    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * products
     *
     * @var ObjectStorage<Product>
     * @Lazy
     */
    protected $products = null;

    /**
     * Complete
     *
     * @var bool
     */
    protected $complete = false;

    /**
     * @var FrontendUser
     */
    protected $feUser = null;

    /**
     * @var string
     */
    protected $serializedOrderFields = '';

    /**
     * @var string
     */
    protected $serializedProductsQuantity = '';

    /**
     * externalId
     *
     * @var string
     */
    protected $externalId = '';

    /**
     * @var \DateTime
     */
    protected $crdate = null;

    /**
     * checkoutType
     *
     * @var string $checkoutType
     */
    protected $checkoutType = 'default';

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->products = new ObjectStorage();
    }

    /**
     * Adds a Product
     *
     * @param Product $product
     *
     * @return void
     */
    public function addProduct(Product $product)
    {
        $this->products->attach($product);
    }

    /**
     * Removes a Product
     *
     * @param Product $productToRemove The Product to be removed
     *
     * @return void
     */
    public function removeProduct(Product $productToRemove)
    {
        $this->products->detach($productToRemove);
    }

    /**
     * Returns the products
     *
     * @return ObjectStorage<\Pixelannt\PxaPixelant\Domain\Model\Product> $products
     */
    public function getProducts(): ObjectStorage
    {
        return $this->products;
    }

    /**
     * Sets the products
     *
     * @param ObjectStorage<\Pixelannt\PxaPixelant\Domain\Model\Product> $products
     *
     * @return void
     */
    public function setProducts(ObjectStorage $products)
    {
        $this->products = $products;
    }

    /**
     * @return FrontendUser
     */
    public function getFeUser()
    {
        return $this->feUser;
    }

    /**
     * @param FrontendUser $feUser
     */
    public function setFeUser(FrontendUser $feUser)
    {
        $this->feUser = $feUser;
    }

    /**
     * Return product uid to quantity array
     *
     * @return array
     */
    public function getProductsQuantity(): array
    {
        $result = unserialize($this->getSerializedProductsQuantity());

        return is_array($result) ? $result : [];
    }

    /**
     * @return string
     */
    public function getSerializedProductsQuantity(): string
    {
        return $this->serializedProductsQuantity;
    }

    /**
     * @param string $serializedProductsQuantity
     */
    public function setSerializedProductsQuantity(string $serializedProductsQuantity)
    {
        $this->serializedProductsQuantity = $serializedProductsQuantity;
    }

    /**
     * Save products quantity as serialized string
     *
     * @param array $productsQuantity
     */
    public function setProductsQuantity(array $productsQuantity)
    {
        $this->setSerializedProductsQuantity(serialize($productsQuantity));
    }

    /**
     * Returns the externalId
     *
     * @return string $externalId
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Sets the externalId
     *
     * @param string $externalId
     *
     * @return void
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->complete;
    }

    /**
     * @param bool $complete
     */
    public function setComplete(bool $complete)
    {
        $this->complete = $complete;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param string $type
     */
    public function setOrderField(string $name, $value, string $type = '')
    {
        $orderFields = $this->getOrderFields() ?: [];
        $orderFields[$name]['value'] = $value;

        if (!empty($type)) {
            $orderFields[$name]['type'] = $type;
        }

        $this->setOrderFields($orderFields);
    }

    /**
     * Get un-serialized order fields information
     *
     * @return array
     */
    public function getOrderFields(): array
    {
        $result = unserialize($this->getSerializedOrderFields());

        return is_array($result) ? $result : [];
    }

    /**
     * @return string
     */
    public function getSerializedOrderFields(): string
    {
        return $this->serializedOrderFields;
    }

    /**
     * @param string $serializedOrderFields
     */
    public function setSerializedOrderFields(string $serializedOrderFields)
    {
        $this->serializedOrderFields = $serializedOrderFields;
    }

    /**
     * Save order fields as serialized string
     *
     * @param array $orderFields
     */
    public function setOrderFields(array $orderFields)
    {
        $this->setSerializedOrderFields(serialize($orderFields));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getOrderField(string $name)
    {
        $orderFields = $this->getOrderFields() ?: [];

        return $orderFields[$name]['value'] ?: null;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function removeOrderField(string $name)
    {
        $orderFields = $this->getOrderFields() ?: [];
        if (!empty($orderFields[$name])) {
            unset($orderFields[$name]);
        }
        $this->setOrderFields($orderFields);
    }

    /**
     * @return \DateTime
     */
    public function getCrdate(): \DateTime
    {
        return $this->crdate;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Order language uid
     *
     * @return int
     */
    public function getLanguageUid(): int
    {
        return $this->_getProperty('_languageUid');
    }

    /**
     * @return string
     */
    public function getCheckoutType(): string
    {
        return $this->checkoutType ?: 'default';
    }

    /**
     * @param string $checkoutType
     */
    public function setCheckoutType(string $checkoutType)
    {
        $this->checkoutType = $checkoutType;
    }
}
