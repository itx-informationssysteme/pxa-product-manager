<?php
defined('TYPO3') || die('Access denied.');

return (function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:tx_pxaproductmanager_domain_model_attributeset';
    $llCore = 'LLL:EXT:core/Resources/Private/Language/';

    return [
        'ctrl' => [
            'title' => $ll,
            'label' => 'name',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'sortby' => 'sorting',
            'origUid' => 't3_origuid',
            'languageField' => 'sys_language_uid',
            'transOrigPointerField' => 'l10n_parent',
            'transOrigDiffSourceField' => 'l10n_diffsource',
            'delete' => 'deleted',
            'enablecolumns' => [
                'disabled' => 'hidden',
                'starttime' => 'starttime',
                'endtime' => 'endtime',
            ],
            'searchFields' => 'name,attributes,',
            'iconfile' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/layers.svg'
        ],
        'types' => [
            // @codingStandardsIgnoreStart
            '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, --palette--;;1, name, attributes, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
            // @codingStandardsIgnoreEnd
        ],
        'palettes' => [
            '1' => ['showitem' => ''],
        ],
        'columns' => [
            'sys_language_uid' => [
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.language',
                'config' => ['type' => 'language']
            ],
            'l10n_parent' => [
                'displayCond' => 'FIELD:sys_language_uid:>:0',
                'label' => $llCore . 'locallang_general.xlf:LGL.l18n_parent',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['', 0],
                    ],
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_attributeset',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_attributeset.pid=###CURRENT_PID###' . ' AND tx_pxaproductmanager_domain_model_attributeset.sys_language_uid IN (-1,0)',
                    'default' => 0
                ],
            ],
            'l10n_diffsource' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],
            'hidden' => [
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                ],
            ],
            'starttime' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'eval' => 'datetime,int',
                    'default' => 0
                ],
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly'
            ],
            'endtime' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'eval' => 'datetime,int',
                    'default' => 0,
                    'range' => [
                        'upper' => mktime(0, 0, 0, 1, 1, 2038)
                    ]
                ],
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly'
            ],
            'name' => [
                'exclude' => 0,
                'label' => $ll . '.name',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ],
            ],
            'attributes' => [
                'exclude' => 0,
                'label' => $ll . '.attributes',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_attribute',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_attribute.pid = ###CURRENT_PID###' . ' AND tx_pxaproductmanager_domain_model_attribute.sys_language_uid <= 0',
                    'MM' => 'tx_pxaproductmanager_attributeset_attribute_mm',
                    'size' => 10,
                    'autoSizeMax' => 30,
                    'maxitems' => 9999,
                    'multiple' => 0,
                    'fieldControl' => [
                        'editPopup' => [
                            'disabled' => false
                        ],
                        'addRecord' => [
                            'disabled' => false,
                        ]
                    ]
                ]
            ]
        ],
    ];
})();
