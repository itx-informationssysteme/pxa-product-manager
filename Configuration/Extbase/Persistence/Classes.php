<?php

declare(strict_types=1);

use Pixelant\PxaProductManager\Domain\Model\Image;
use Pixelant\PxaProductManager\Domain\Model\AttributeFalFile;
use Pixelant\PxaProductManager\Domain\Model\Category;
use Pixelant\PxaProductManager\Domain\Model\OrderFormField;
use Pixelant\PxaProductManager\Domain\Model\OrderFormFields\InputFormField;
use Pixelant\PxaProductManager\Domain\Model\OrderFormFields\TextAreaFormField;
use Pixelant\PxaProductManager\Domain\Model\OrderFormFields\SelectBoxFormField;
use Pixelant\PxaProductManager\Domain\Model\OrderFormFields\CheckBoxFormField;

return [
    Image::class => [
        'tableName' => 'sys_file_reference',
        'properties' => [
            'useInListing' => [
                'fieldName' => 'pxapm_use_in_listing',
            ],
            'mainImage' => [
                'fieldName' => 'pxapm_main_image',
            ],
        ],
    ],
    AttributeFalFile::class => [
        'tableName' => 'sys_file_reference',
        'properties' => [
            'attribute' => [
                'fieldName' => 'pxa_attribute',
            ],
        ],
    ],
    Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'attributeSets' => [
                'fieldName' => 'pxapm_attributes_sets',
            ],
            'description' => [
                'fieldName' => 'pxapm_description',
            ],
            'image' => [
                'fieldName' => 'pxapm_image',
            ],
            'bannerImage' => [
                'fieldName' => 'pxapm_banner_image',
            ],
            'cardViewTemplate' => [
                'fieldName' => 'pxapm_card_view_template',
            ],
            'singleViewTemplate' => [
                'fieldName' => 'pxapm_single_view_template',
            ],
            'subCategories' => [
                'fieldName' => 'pxapm_subcategories',
            ],
            'taxRate' => [
                'fieldName' => 'pxapm_tax_rate',
            ],
            'slug' => [
                'fieldName' => 'pxapm_slug',
            ],
        ],
    ],
    OrderFormField::class => [
        'subclasses' => [
            InputFormField::class,
            TextAreaFormField::class,
            SelectBoxFormField::class,
            CheckBoxFormField::class
        ],
    ],
    InputFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    TextAreaFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    SelectBoxFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    CheckBoxFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
];
