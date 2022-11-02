<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die;

call_user_func(function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:';

    $newSysFileReferenceColumns = [
        'pxapm_use_in_listing' => [
            'label' => $ll . 'sys_file_reference.pxapm_use_in_listing',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'pxapm_main_image' => [
            'label' => $ll . 'sys_file_reference.pxapm_main_image',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ]
    ];

    $newSysFileReferenceColumnsForAttribute = [
        'pxa_attribute' => [
            'label' => 'pxa_attribute',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => []
            ]
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);

    ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumnsForAttribute);

    // add special product manager palette
    $GLOBALS['TCA']['sys_file_reference']['palettes']['pxaProductManagerPalette'] = [
        'showitem' => 'pxapm_use_in_listing, pxapm_main_image',
        'canNotCollapse' => true
    ];

    $GLOBALS['TCA']['sys_file_reference']['palettes']['pxaProductManagerPaletteAttribute'] = [
        'showitem' => 'pxa_attribute',
        'canNotCollapse' => true
    ];
});
