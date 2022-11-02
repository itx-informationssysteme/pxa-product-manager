<?php
defined('TYPO3') || die('Access denied.');

return (function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:';
    $llCore = 'LLL:EXT:core/Resources/Private/Language/';

    return [
        'ctrl' => [
            'title' => $ll . 'tx_pxaproductmanager_domain_model_link',
            'label' => 'name',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'sortby' => 'sorting',
            'versioningWS' => true,
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
            'searchFields' => 'name,link',
            'hideTable' => 1,
            'iconfile' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/link.svg'
        ],
        'types' => [
            '1' => ['showitem' => 'hidden, --palette--;;1'],
        ],
        'palettes' => [
            '1' => ['showitem' => 'name, --linebreak--, link, --linebreak--, description'],
        ],
        'columns' => [
            'sys_language_uid' => [
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.language',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'special' => 'languages',
                    'items' => [
                        [
                            $llCore . 'locallang_general.xlf:LGL.allLanguages',
                            -1,
                            'flags-multiple'
                        ],
                    ],
                    'default' => 0,
                ]
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
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_link',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_link.pid=###CURRENT_PID### AND tx_pxaproductmanager_domain_model_link.sys_language_uid IN (-1,0)',
                    'default' => 0
                ],
            ],
            'l10n_diffsource' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],
            't3ver_label' => [
                'label' => $llCore . 'locallang_general.xlf:LGL.versionLabel',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'max' => 255
                ]
            ],
            'hidden' => [
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                ]
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
                'label' => $ll . 'tx_pxaproductmanager_domain_model_link.name',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ]
            ],
            'link' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_link.link',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'max' => 256,
                    'eval' => 'trim,required',
                    'renderType' => 'inputLink',
                    'softref' => 'typolink'
                ],
            ],
            'description' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_link.description',
                'config' => [
                    'type' => 'text',
                    'cols' => 60,
                    'rows' => 5,
                    'eval' => 'trim'
                ],
            ],
            'product' => [
                'config' => [
                    'type' => 'passthrough',
                ]
            ]
        ]
    ];
})();
