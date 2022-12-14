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
use Pixelant\PxaProductManager\Utility\AttributeHolderUtility;
use Pixelant\PxaProductManager\Utility\ConfigurationUtility;
use Pixelant\PxaProductManager\Utility\ProductUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 *
 *
 * @package pxa_product_manager
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Product extends AbstractEntity
{

    /**
     * Categories
     *
     * @var ObjectStorage<Category>
     */
    protected $categories;

    /**
     * name
     *
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug = '';

    /**
     * sku
     *
     * @var \string
     */
    protected $sku;

    /**
     * Price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * taxRate
     *
     * @var float $taxRate
     */
    protected $taxRate = 0.00;

    /**
     * description
     *
     * @var \string
     */
    protected $description = '';

    /**
     * disableSingleView
     *
     * @var boolean
     */
    protected $disableSingleView = false;

    /**
     * @var \string
     */
    protected $alternativeTitle = '';

    /**
     * @var \string
     */
    protected $pathSegment = '';

    /**
     * @var \string
     */
    protected $keywords = '';

    /**
     * @var \string
     */
    protected $metaDescription = '';

    /**
     * relatedProducts
     *
     * @Lazy
     * @var ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product>
     */
    protected $relatedProducts;

    /**
     * Images
     *
     * @var ObjectStorage<Image>
     * @Lazy
     */
    protected $images;

    /**
     * Attributes files
     *
     * @var ObjectStorage<AttributeFalFile>
     * @Lazy
     */
    protected $attributeFiles;

    /**
     * links
     *
     * @Lazy
     * @var ObjectStorage<Link>
     */
    protected $links;

    /**
     * subProducts
     *
     * @Lazy
     * @var ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product>
     */
    protected $subProducts;

    /**
     * Fal links
     *
     * @var ObjectStorage<FileReference>
     * @Lazy
     */
    protected $falLinks;

    /**
     * Attributes
     *
     * @var ObjectStorage<Attribute>
     */
    protected $attributes;

    /**
     * Attributes as array with identifier as index
     *
     * @var array
     */
    protected $attributesIdentifiersArray = [];

    /**
     * Attributes grouped by sets
     *
     * @var ObjectStorage
     */
    protected $attributesGroupedBySets;

    /**
     * attributeValues
     *
     * @Lazy
     * @var ObjectStorage<AttributeValue>
     */
    protected $attributeValues;

    /**
     * @var int
     */
    protected $crdate;

    /**
     * @var int
     */
    protected $tstamp;

    /**
     * @var boolean
     */
    protected $hidden;

    /**
     * @var boolean
     */
    protected $deleted;

    /**
     * Attribute values
     *
     * @var string
     */
    protected $serializedAttributesValues = '';

    /**
     * Product main image
     *
     * @var Image
     */
    protected $mainImage;

    /**
     * Product listing image
     *
     * @var Image
     */
    protected $thumbnailImage;

    /**
     * attributesDescription
     *
     * @var \string
     */
    protected $attributesDescription = '';

    /**
     * Assets
     *
     * @var ObjectStorage<FileReference>
     * @Lazy
     */
    protected $assets;

    /**
     * additionalInformation
     *
     * @var \string
     */
    protected $additionalInformation = '';

    /**
     * teaser
     *
     * @var \string
     */
    protected $teaser = '';

    /**
     * usp
     *
     * @var \string
     */
    protected $usp = '';

    /**
     * @var \DateTime|NULL
     */
    protected $launched;

    /**
     * @var \DateTime|NULL
     */
    protected $discontinued;

    /**
     * accessories
     *
     * @Lazy
     * @var ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product>
     */
    protected $accessories;

    /**
     * @var int
     */
    protected $customSorting = 0;

    /**
     * __construct
     *
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        /**
         * Do not modify this method!
         * It will be rewritten on each save in the extension builder
         * You may modify the constructor of this class instead
         */
        $this->relatedProducts = new ObjectStorage();

        $this->images = new ObjectStorage();

        $this->attributeFiles = new ObjectStorage();

        $this->links = new ObjectStorage();

        $this->subProducts = new ObjectStorage();

        $this->falLinks = new ObjectStorage();

        $this->categories = new ObjectStorage();

        $this->attributeValues = new ObjectStorage();

        $this->assets = new ObjectStorage();

        $this->accessories = new ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return \string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param \string $name
     *
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Returns the sku
     *
     * @return \string $sku
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Sets the sku
     *
     * @param \string $sku
     *
     * @return void
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

    /**
     * Format price
     *
     * @return float
     */
    public function getFormatPrice(): string
    {
        return ProductUtility::formatPrice($this->price);
    }

    /**
     * Returns the taxRate
     *
     * @return float $taxRate
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /**
     * Sets the taxRate
     *
     * @param float $taxRate
     *
     * @return void
     */
    public function setTaxRate(float $taxRate)
    {
        $this->taxRate = $taxRate;
    }

    /**
     * Adds a Product
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Product $relatedProduct
     *
     * @return void
     */
    public function addRelatedProduct(Product $relatedProduct)
    {
        $this->relatedProducts->attach($relatedProduct);
    }

    /**
     * Removes a Product
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Product $relatedProductToRemove The Product to be removed
     *
     * @return void
     */
    public function removeRelatedProduct(Product $relatedProductToRemove)
    {
        $this->relatedProducts->detach($relatedProductToRemove);
    }

    /**
     * Returns the relatedProducts
     *
     * @return ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product> $relatedProducts
     */
    public function getRelatedProducts(): ObjectStorage
    {
        return $this->relatedProducts;
    }

    /**
     * Sets the relatedProducts
     *
     * @param ObjectStorage <\Pixelant\PxaProductManager\Domain\Model\Product> $relatedProducts
     *
     * @return void
     */
    public function setRelatedProducts(ObjectStorage $relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;
    }

    /**
     * Returns the description
     *
     * @return \string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param \string $description
     *
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Adds a Image
     *
     * @param Image $image
     *
     * @return void
     */
    public function addImage(Image $image)
    {
        $this->images->attach($image);
    }

    /**
     * Removes a Image
     *
     * @param Image $image The Image to be removed
     *
     * @return void
     */
    public function removeImage(Image $image)
    {
        $this->images->detach($image);
    }

    /**
     * Returns the Attribute files
     *
     * @return ObjectStorage<AttributeFalFile>
     */
    public function getAttributeFiles(): ObjectStorage
    {
        return $this->attributeFiles;
    }

    /**
     * Sets the Attribute files
     *
     * @param ObjectStorage $files
     *
     * @return void
     */
    public function setAttributeFiles(ObjectStorage $files)
    {
        $this->attributeFiles = $files;
    }

    /**
     * Adds a Category
     *
     * @param Category $category
     *
     * @return void
     */
    public function addCategory(Category $category)
    {
        if ($this->categories === null) {
            $this->categories = new ObjectStorage();
        }

        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param Category $categoryToRemove The Category to be removed
     *
     * @return void
     */
    public function removeCategory(Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return Category
     */
    public function getFirstCategory()
    {
        $this->categories->rewind();

        return $this->categories->current();
    }

    /**
     * @return ObjectStorage<Attribute> $attributes
     */
    public function getAttributes(): ObjectStorage
    {
        if ($this->attributes === null) {
            if (!(int)$this->getUid()) {
                return (new ObjectStorage());
            }
            $this->initializeAttributes();
        }

        return $this->attributes;
    }

    /**
     * @param ObjectStorage<Attribute> $attributes
     */
    public function setAttributes(ObjectStorage $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Initialize attributes
     */
    protected function initializeAttributes()
    {
        $this->attributes = new ObjectStorage();

        /** @var AttributeHolderUtility $attributeHolder */
        $attributeHolder = GeneralUtility::makeInstance(AttributeHolderUtility::class);
        $attributeHolder->start($this->getUid());

        $this->attributesGroupedBySets = $attributeHolder->getAttributeSets();

        $attributesValues = (array)unserialize($this->getSerializedAttributesValues());

        /** @var Attribute $attribute */
        foreach ($attributeHolder->getAttributes() as $attribute) {
            $id = $attribute->getUid();

            if ($attribute->isFalType()) {
                $falFiles = [];
                /** @var AttributeFalFile $falReference */
                foreach ($this->attributeFiles->toArray() as $falReference) {
                    if ($falReference->getAttribute() === $id) {
                        $falFiles[] = $falReference;
                    }
                }

                $attribute->setValue($falFiles);
            } elseif (array_key_exists($id, $attributesValues)) {
                $value = $attributesValues[$id];

                switch ($attribute->getType()) {
                    case Attribute::ATTRIBUTE_TYPE_DROPDOWN:
                    case Attribute::ATTRIBUTE_TYPE_MULTISELECT:
                        $options = [];

                        /** @var Option $option */
                        foreach ($attribute->getOptions() as $option) {
                            if (GeneralUtility::inList($value, $option->getUid())) {
                                $options[] = $option;
                            }
                        }

                        $attribute->setValue($options);
                        break;
                    case Attribute::ATTRIBUTE_TYPE_DATETIME:
                        if ($value) {
                            try {
                                $value = new \DateTime($value);
                            }
                            catch (\Exception $exception) {
                                $value = '';
                            }
                        }
                        $attribute->setValue($value);
                        break;
                    default:
                        $attribute->setValue($value);
                }
            }

            $this->attributes->attach($attribute);
            $this->attributesIdentifiersArray[$attribute->getIdentifier() ?: $attribute->getUid()] = $attribute;
        }
    }

    /**
     * @return string
     */
    public function getSerializedAttributesValues(): string
    {
        return $this->serializedAttributesValues;
    }

    /**
     * @param string $serializedAttributesValues
     */
    public function setSerializedAttributesValues(string $serializedAttributesValues)
    {
        $this->serializedAttributesValues = $serializedAttributesValues;
    }

    /**
     * @return array
     */
    public function getAttributesGroupedBySets(): ObjectStorage
    {
        if ($this->attributesGroupedBySets === null) {
            if (!(int)$this->getUid()) {
                return (new ObjectStorage());
            }
            $this->initializeAttributes();
        }

        return $this->attributesGroupedBySets;
    }

    /**
     * Adds a attribute
     *
     * @param Attribute $attribute
     *
     * @return void
     */
    public function addAttribute(Attribute $attribute)
    {
        if ($this->attributes === null) {
            $this->attributes = new ObjectStorage();
        }
        $this->attributes->attach($attribute);
    }

    /**
     * Removes a attribute
     *
     * @param Attribute $attribute
     *
     * @return void
     */
    public function removeAttribute(Attribute $attribute)
    {
        if ($this->attributes !== null) {
            $this->attributes->detach($attribute);
        }
    }

    /**
     * Adds a AttributeValue
     *
     * @param AttributeValue $attributeValue
     *
     * @return void
     */
    public function addAttributeValue(AttributeValue $attributeValue)
    {
        if ($this->attributeValues === null) {
            $this->attributeValues = new ObjectStorage();
        }

        $this->attributeValues->attach($attributeValue);
    }

    /**
     * Removes a AttributeValue
     *
     * @param AttributeValue $attributeValueToRemove The AttributeValue to be removed
     *
     * @return void
     */
    public function removeAttributeValue(AttributeValue $attributeValueToRemove)
    {
        $this->attributeValues->detach($attributeValueToRemove);
    }

    /**
     * Returns the attributeValues
     *
     * @return ObjectStorage<AttributeValue> $attributeValues
     */
    public function getAttributeValues(): ?ObjectStorage
    {
        return $this->attributeValues;
    }

    /**
     * Sets the attributeValues
     *
     * @param ObjectStorage<AttributeValue> $attributeValues
     *
     * @return void
     */
    public function setAttributeValues(ObjectStorage $attributeValues)
    {
        $this->attributeValues = $attributeValues;
    }

    /**
     * getThumbnail
     *
     * @return Image
     */
    public function getThumbnail()
    {
        if ($this->thumbnailImage === null) {
            $this->thumbnailImage = $this->getImageFor('useInListing');
        }

        return $this->thumbnailImage;
    }

    /**
     * Get image for different views
     *
     * @param string $propertyName
     *
     * @return null|object|Image
     */
    protected function getImageFor($propertyName)
    {
        if ($this->images->count()) {
            /** @var Image $image */
            foreach ($this->images as $image) {
                if (ObjectAccess::isPropertyGettable($image, $propertyName) && ObjectAccess::getProperty($image, $propertyName) === true) {
                    return $image;
                }
            }

            // use any if no result
            $this->images->rewind();

            return $this->images->current();
        }

        return null;
    }

    /**
     * Adds a File
     *
     * @param Link $link
     */
    public function addLink(Link $link)
    {
        $this->links->attach($link);
    }

    /**
     * Removes a File
     *
     * @param Link $linkToRemove The Link to be removed
     */
    public function removeLink(Link $linkToRemove)
    {
        $this->links->detach($linkToRemove);
    }

    /**
     * Returns the links
     *
     * @return ObjectStorage<Link> links
     */
    public function getLinks(): ObjectStorage
    {
        return $this->links;
    }

    /**
     * Sets the links
     *
     * @param ObjectStorage<Link> $links
     */
    public function setLinks(ObjectStorage $links)
    {
        $this->links = $links;
    }

    /**
     * Returns the boolean state of disableSingleView
     *
     * @return boolean
     */
    public function isDisableSingleView()
    {
        return $this->disableSingleView;
    }

    /**
     * Returns the boolean state of disableSingleView
     *
     * @return boolean
     */
    public function getDisableSingleView()
    {
        return $this->disableSingleView;
    }

    /**
     * Sets the disableSingleView
     *
     * @param boolean $disableSingleView
     *
     * @return void
     */
    public function setDisableSingleView(bool $disableSingleView)
    {
        $this->disableSingleView = $disableSingleView;
    }

    /**
     * Adds a Product
     *
     * @param Product $subProduct
     *
     * @return void
     */
    public function addSubProduct(Product $subProduct)
    {
        $this->subProducts->attach($subProduct);
    }

    /**
     * Removes a Product
     *
     * @param Product $subProductToRemove The Product to be removed
     *
     * @return void
     */
    public function removeSubProduct(Product $subProductToRemove)
    {
        $this->subProducts->detach($subProductToRemove);
    }

    /**
     * Returns the subProducts
     *
     * @return ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product> $subProducts
     */
    public function getSubProducts(): ObjectStorage
    {
        return $this->subProducts;
    }

    /**
     * Sets the subProducts
     *
     * @param ObjectStorage <\Pixelant\PxaProductManager\Domain\Model\Product> $subProducts
     *
     * @return void
     */
    public function setSubProducts(ObjectStorage $subProducts)
    {
        $this->subProducts = $subProducts;
    }

    /**
     * Get alternative title
     *
     * @return \string
     */
    public function getAlternativeTitle(): string
    {
        return $this->alternativeTitle;
    }

    /**
     * Set alternative title
     *
     * @param \string $alternativeTitle
     *
     * @return void
     */
    public function setAlternativeTitle(string $alternativeTitle)
    {
        $this->alternativeTitle = $alternativeTitle;
    }

    /**
     * Get path segment
     *
     * @return \string
     */
    public function getPathSegment(): string
    {
        return $this->pathSegment;
    }

    /**
     * Set path segment
     *
     * @param \string $pathSegment
     *
     * @return void
     */
    public function setPathSegment(string $pathSegment)
    {
        $this->pathSegment = $pathSegment;
    }

    /**
     * Get keywords
     *
     * @return \string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     *
     * @param \string $keywords keywords
     *
     * @return void
     */
    public function setKeywords(string $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription metaDescription
     *
     * @return void
     */
    public function setMetaDescription(string $metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Sorted images
     *
     * @return ObjectStorage
     */
    public function getImagesSorted(): ObjectStorage
    {
        $images = clone $this->getImages();

        if ($images->count() > 1 && ($mainImage = $this->getMainImage())) {
            $sortedImages = new ObjectStorage();

            /** @var Image $image */
            foreach ($images as $image) {
                if ($image->getUid() === $mainImage->getUid()) {
                    $sortedImages->attach($image);
                    $images->detach($image);
                }
            }

            $sortedImages->addAll($images);

            return $sortedImages;
        }

        return $images;
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<Image> $images
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<Image> $images
     *
     * @return void
     */
    public function setImages(ObjectStorage $images)
    {
        $this->images = $images;
    }

    /**
     * getMainProductImage
     *
     * @return Image
     */
    public function getMainImage()
    {
        if ($this->mainImage === null) {
            $this->mainImage = $this->getImageFor('mainImage');
        }

        return $this->mainImage;
    }

    /**
     * Adds a FalLink
     *
     * @param FileReference $falLink
     *
     * @return void
     */
    public function addFalLink(FileReference $falLink)
    {
        $this->falLinks->attach($falLink);
    }

    /**
     * Removes a FalLinks
     *
     * @param FileReference $falLink The FalLink to be removed
     *
     * @return void
     */
    public function removeFalLink(FileReference $falLink)
    {
        $this->falLinks->detach($falLink);
    }

    /**
     * Returns the falLinks
     *
     * @return ObjectStorage<FileReference> $falLinks
     */
    public function getFalLinks(): ObjectStorage
    {
        return $this->falLinks;
    }

    /**
     * Sets the falLinks
     *
     * @param ObjectStorage<FileReference> $falLinks
     *
     * @return void
     */
    public function setFalLinks(ObjectStorage $falLinks)
    {
        $this->falLinks = $falLinks;
    }

    /**
     * Get creation date
     *
     * @return int
     */
    public function getCrdate(): int
    {
        return $this->crdate;
    }

    /**
     * Set Creation Date
     *
     * @param int $crdate crdate
     *
     * @return void
     */
    public function setCrdate(int $crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get Tstamp
     *
     * @return int
     */
    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param int $tstamp tstamp
     *
     * @return void
     */
    public function setTstamp(int $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get Hidden
     *
     * @return boolean
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set Hidden
     *
     * @param boolean $hidden
     *
     * @return void
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get Deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set Deleted
     *
     * @param boolean $deleted
     *
     * @return void
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Get attributesDescription
     *
     * @return string
     */
    public function getAttributesDescription(): string
    {
        return $this->attributesDescription;
    }

    /**
     * Set attributesDescription
     *
     * @param string $attributesDescription attributesDescription
     *
     * @return void
     */
    public function setAttributesDescription(string $attributesDescription)
    {
        $this->attributesDescription = $attributesDescription;
    }

    /**
     * Adds an asset
     *
     * @param FileReference $asset
     *
     * @return void
     */
    public function addAsset(FileReference $asset)
    {
        $this->assets->attach($asset);
    }

    /**
     * Removes an asset
     *
     * @param FileReference $asset The asset to be removed
     *
     * @return void
     */
    public function removeAsset(FileReference $asset)
    {
        $this->assets->detach($asset);
    }

    /**
     * Returns the assets
     *
     * @return ObjectStorage<FileReference> $assets
     */
    public function getAssets(): ObjectStorage
    {
        return $this->assets;
    }

    /**
     * Sets the assets
     *
     * @param ObjectStorage<FileReference> $assets
     *
     * @return void
     */
    public function setAssets(ObjectStorage $assets)
    {
        $this->assets = $assets;
    }

    /**
     * Returns the additionalInformation
     *
     * @return \string $additionalInformation
     */
    public function getAdditionalInformation(): string
    {
        return $this->additionalInformation;
    }

    /**
     * Sets the additionalInformation
     *
     * @param \string $additionalInformation
     *
     * @return void
     */
    public function setAdditionalInformation(string $additionalInformation)
    {
        $this->additionalInformation = $additionalInformation;
    }

    /**
     * Returns the teaser
     *
     * @return \string $teaser
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser
     *
     * @param \string $teaser
     *
     * @return void
     */
    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;
    }

    /**
     * Returns the usp
     *
     * @return \string $usp
     */
    public function getUsp(): string
    {
        return $this->usp;
    }

    /**
     * Sets the usp
     *
     * @param \string $usp
     *
     * @return void
     */
    public function setUsp(string $usp)
    {
        $this->usp = $usp;
    }

    /**
     * Returns the usp as list (f.ex. to use as bullets)
     *
     * @return \array $usp
     */
    public function getUspAsList(): array
    {
        return explode("\n", $this->usp);
    }

    /**
     * Adds a accessory
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Product $accessory
     *
     * @return void
     */
    public function addAccessory(Product $accessory)
    {
        $this->accessories->attach($accessory);
    }

    /**
     * Removes a accessory
     *
     * @param \Pixelant\PxaProductManager\Domain\Model\Product $accessory The accessory to be removed
     *
     * @return void
     */
    public function removeAccessory(Product $accessory)
    {
        $this->accessories->detach($accessory);
    }

    /**
     * Returns the accessories
     *
     * @return ObjectStorage<\Pixelant\PxaProductManager\Domain\Model\Product> $accessories
     */
    public function getAccessories(): ObjectStorage
    {
        return $this->accessories;
    }

    /**
     * Sets the accessories
     *
     * @param ObjectStorage <\Pixelant\PxaProductManager\Domain\Model\Product> $accessories
     *
     * @return void
     */
    public function setAccessories(ObjectStorage $accessories)
    {
        $this->accessories = $accessories;
    }

    /**
     * Get custom sorting
     *
     * @return int
     */
    public function getCustomSorting(): int
    {
        return $this->customSorting;
    }

    /**
     * Set custom sorting
     *
     * @param int $customSorting Custom sorting
     *
     * @return void
     */
    public function setCustomSorting(int $customSorting)
    {
        $this->customSorting = $customSorting;
    }

    /**
     * @return string
     */
    public function getFormatTax(): string
    {
        return ProductUtility::formatPrice($this->getTax());
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->getPrice() * ($this->getTaxRateRecursively() / 100);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getTaxRateRecursively(): float
    {
        // If tax rate is set on product level - return it
        // or it was set from category tax
        if (!empty($this->taxRate)) {
            return $this->taxRate;
        }

        // Else get the tax rate from categories
        $taxRate = 0;

        $categoriesTree = ProductUtility::getProductCategoriesParentsTree($this->getUid());
        /** @var Category $category */
        foreach ($categoriesTree as $category) {
            $taxRate = $category->getTaxRate();
            if ($taxRate > 0) {
                $this->taxRate = $taxRate; // Save value for future calls
                break;
            }
        }

        return $taxRate;
    }

    /**
     * Gets additional classes
     *
     * @param string $prefix If prefix should be added to all additional classes
     *
     * @return string
     */
    public function getAdditionalClasses(string $prefix = ''): string
    {
        $additionalClasses = [];
        $pid = $this->getPid();

        // check if additional category classes are set in plugin ts setup
        // this way we can add custom classes for products based on its categories in templates
        $categoriesAdditionalClasses = ConfigurationUtility::getSettingsByPath('additionalClasses/categories', $pid);
        if (is_array($categoriesAdditionalClasses) && count($categoriesAdditionalClasses) > 0) {
            foreach ($this->getCategories() as $category) {
                $className = $categoriesAdditionalClasses[$category->getUid()];
                if (!empty($className)) {
                    $additionalClasses[] = $className;
                }
            }
        }

        // check if product is considered new, then add class
        // based on ts setup additionalClasses.launched.isNew
        if ($this->getIsNew()) {
            $isNewClass = ConfigurationUtility::getSettingsByPath('additionalClasses/launched/isNewClass', $pid);
            if (!empty($isNewClass)) {
                $additionalClasses[] = $isNewClass;
            }
        }

        // check if product is considered discontinued, then add class
        // based on ts setup additionalClasses.discontinued.isDiscontinuedClass
        if ($this->getIsDiscontinued()) {
            $isDiscontinuedClass = ConfigurationUtility::getSettingsByPath('additionalClasses/discontinued/isDiscontinuedClass', $pid);
            if (!empty($isDiscontinuedClass)) {
                $additionalClasses[] = $isDiscontinuedClass;
            }
        }

        if (is_array($additionalClasses) && count($additionalClasses) > 0) {
            return implode($prefix . ' ', $additionalClasses);
        } else {
            return '';
        }
    }

    /**
     * Returns the categories
     *
     * @return ObjectStorage<Category> $categories
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param ObjectStorage<Category> $categories
     *
     * @return void
     */
    public function setCategories(ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns true if product is considered as new
     * based on product launched date and setup ts launched.daysAsNew
     *
     * @return bool
     */
    public function getIsNew(): bool
    {
        $isNew = false;
        if (!empty($this->getLaunched())) {
            $dateInterval = ConfigurationUtility::getSettingsByPath('launched/dateIntervalAsNew', $this->getPid());
            if (!empty($dateInterval)) {
                try {
                    $newUntil = clone $this->getLaunched();
                    $newUntil->add(new \DateInterval((string)$dateInterval));
                    $isNew = $newUntil->format('U') > time();
                }
                catch (\Exception $e) {
                    // TODO: Log invalid interval_spec
                }
            }
        }

        return $isNew;
    }

    /**
     * Returns launched date
     *
     * @return \DateTime|NULL the launched date
     */
    public function getLaunched()
    {
        return $this->launched;
    }

    /**
     * Sets launched date
     *
     * @param \DateTime|NULL $launched the launched date
     */
    public function setLaunched(\DateTime $launched = null)
    {
        $this->launched = $launched;
    }

    /**
     * Returns true if product is considered as discontinued
     *
     * @return bool
     */
    public function getIsDiscontinued(): bool
    {
        $isDiscontinued = false;
        if (!empty($this->getDiscontinued())) {
            $today = new \DateTime('00:00');
            $isDiscontinued = $this->getDiscontinued()->format('U') <= $today->format('U');
        }

        return $isDiscontinued;
    }

    /**
     * Returns discontinued date
     *
     * @return \DateTime|NULL the discontinued date
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Sets discontinued date
     *
     * @param \DateTime|NULL $discontinued the discontinued date
     */
    public function setDiscontinued(\DateTime $discontinued = null)
    {
        $this->discontinued = $discontinued;
    }

    /**
     * This method will return attribute by identifier
     * Fluid usage
     * {product.attribute.identifier.value}
     *
     * @param string $identifier
     *
     * @return array|Attribute Array of all attributes with identifier or attribute object or null
     */
    public function getAttribute(string $identifier = '')
    {
        // Init attributes if empty
        if ($this->attributes === null) {
            $this->initializeAttributes();
        }

        if (empty($identifier)) {
            return $this->attributesIdentifiersArray;
        } elseif (isset($this->attributesIdentifiersArray[$identifier])) {
            return $this->attributesIdentifiersArray[$identifier];
        }

        return null;
    }
}
