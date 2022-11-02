<?php

declare(strict_types=1);

return [
    \Pixelant\PxaProductManager\Domain\Model\Image::class => [
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
    \Pixelant\PxaProductManager\Domain\Model\AttributeFalFile::class => [
        'tableName' => 'sys_file_reference',
        'properties' => [
            'attribute' => [
                'fieldName' => 'pxa_attribute',
            ],
        ],
    ],
    \Pixelant\PxaProductManager\Domain\Model\Category::class => [
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
    \Pixelant\PxaProductManager\Domain\Model\OrderFormField::class => [
        'subclasses' => [
            \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\InputFormField::class,
            \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\TextAreaFormField::class,
            \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\SelectBoxFormField::class,
            \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\CheckBoxFormField::class
        ],
    ],
    \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\InputFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\TextAreaFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\SelectBoxFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
    \Pixelant\PxaProductManager\Domain\Model\OrderFormFields\CheckBoxFormField::class => [
        'tableName' => 'tx_pxaproductmanager_domain_model_orderformfield'
    ],
];
