<?php
use Pixelant\PxaProductManager\Domain\Model\Attribute;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Resource\File;
defined('TYPO3') || die;

return (function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:';
    $llType = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:tx_pxaproductmanager_domain_model_attribute.type_';
    $accessTab = '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime';
    $llCore = 'LLL:EXT:core/Resources/Private/Language/';

    $tx_pxaproductmanager_domain_model_attribute = [
        'ctrl' => [
            'title' => $ll . 'tx_pxaproductmanager_domain_model_attribute',
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

            'type' => 'type',

            'searchFields' => 'name,type,required,show_in_attribute_listing,identifier,options,',
            'iconfile' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/tag.svg'
        ],
        // @codingStandardsIgnoreStart
        'interface' => [
            'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, label, type, required, show_in_attribute_listing, show_in_compare, identifier, icon, default_value, options, label_unchecked, label_checked',
        ],
        'types' => [
            '1' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;' . $ll . 'palette.options;options, identifier, default_value,' . $accessTab],
            '4' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;' . $ll . 'palette.options;options, identifier, default_value, options,' . $accessTab],
            '9' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;' . $ll . 'palette.options;options, identifier, default_value, options,' . $accessTab],
            '5' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;' . $ll . 'palette.checkbox_values;checkbox_values, --palette--;' . $ll . 'palette.options;options, identifier, default_value,' . $accessTab],
        ],
        // @codingStandardsIgnoreEnd
        'palettes' => [
            'core' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --linebreak--, hidden'],
            'common' => ['showitem' => 'name, --linebreak--, label, --linebreak--, type'],
            'options' => ['showitem' => 'required, show_in_attribute_listing, show_in_compare, --linebreak--, icon'],
            'checkbox_values' => ['showitem' => 'label_checked, label_unchecked'],
        ],
        'columns' => [
            'sys_language_uid' => [
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.language',
                'config' => ['type' => 'language']
            ],
            'l10n_parent' => [
                'displayCond' => 'FIELD:sys_language_uid:>:0',
                'exclude' => 1,
                'label' => $llCore . 'locallang_general.xlf:LGL.l18n_parent',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['', 0],
                    ],
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_attribute',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_attribute.pid=###CURRENT_PID###' . ' AND tx_pxaproductmanager_domain_model_attribute.sys_language_uid IN (-1,0)',
                    'default' => 0
                ]
            ],
            'l10n_diffsource' => [
                'config' => [
                    'type' => 'passthrough',
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
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.name',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ]
            ],
            'label' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.label',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim'
                ]
            ],
            'type' => [
                'exclude' => 0,
                'onChange' => 'reload',
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.type',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['-- Label --', 0],
                    ],
                    'size' => 1,
                    'maxitems' => 1,
                    'eval' => 'required'
                ]
            ],
            'required' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.required',
                'config' => [
                    'type' => 'check',
                    'default' => 0
                ]
            ],
            'show_in_attribute_listing' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.show_in_attribute_listing',
                'config' => [
                    'type' => 'check',
                    'default' => 1
                ]
            ],
            'show_in_compare' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.show_in_compare',
                'config' => [
                    'type' => 'check',
                    'default' => 1
                ]
            ],
            'identifier' => [
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.identifier',
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,alphanum,nospace,unique',
                    'fieldControl' => [
                        'attributeIdentifierControl' => [
                            'renderType' => 'attributeIdentifierControl'
                        ]
                    ]
                ]
            ],
            'default_value' => [
                'exclude' => 1,
                'displayCond' => [
                    'AND' => [
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_DROPDOWN,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_CHECKBOX,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_MULTISELECT,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_IMAGE,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_FILE,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_LINK,
                        'FIELD:type:!=:' . Attribute::ATTRIBUTE_TYPE_DATETIME,
                    ]
                ],
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.default_value',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim'
                ]
            ],
            'label_checked' => [
                'exclude' => 1,
                'displayCond' => 'FIELD:type:=:' . Attribute::ATTRIBUTE_TYPE_CHECKBOX,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.label_checked',
                'config' => [
                    'type' => 'input',
                    'size' => 15,
                    'eval' => 'trim'
                ]
            ],
            'label_unchecked' => [
                'exclude' => 1,
                'displayCond' => 'FIELD:type:=:' . Attribute::ATTRIBUTE_TYPE_CHECKBOX,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.label_unchecked',
                'config' => [
                    'type' => 'input',
                    'size' => 15,
                    'eval' => 'trim'
                ]
            ],
            'options' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.options',
                'displayCond' => 'FIELD:type:IN:' . Attribute::ATTRIBUTE_TYPE_DROPDOWN . ',' . Attribute::ATTRIBUTE_TYPE_MULTISELECT . '',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_option',
                    'foreign_field' => 'attribute',
                    'foreign_sortby' => 'sorting',
                    'maxitems' => 9999,
                    'appearance' => [
                        'collapseAll' => 1,
                        'levelLinksPosition' => 'bottom',
                        'showSynchronizationLink' => 1,
                        'showPossibleLocalizationRecords' => 1,
                        'showAllLocalizationLink' => 1,
                        'useSortable' => 1
                    ]
                ]
            ],
            'icon' => [
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_attribute.icon',
                'config' => ExtensionManagementUtility::getFileFieldTCAConfig('icon', [
                                                                                                                'appearance' => [
                                                                                                                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                                                                                                                    'showPossibleLocalizationRecords' => false,
                                                                                                                    'showRemovedLocalizationRecords' => true,
                                                                                                                    'showAllLocalizationLink' => false,
                                                                                                                    'showSynchronizationLink' => false
                                                                                                                ],
                                                                                                                'foreign_match_fields' => [
                                                                                                                    'fieldname' => 'icon',
                                                                                                                    'tablenames' => 'tx_pxaproductmanager_domain_model_attribute',
                                                                                                                    'table_local' => 'sys_file',
                                                                                                                ],
                                                                                                                'maxitems' => 1,
                                                                                                                // @codingStandardsIgnoreStart
                                                                                                                'overrideChildTca' => [
                                                                                                                    'types' => [
                                                                                                                        '0' => [
                                                                                                                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;pxaProductManagerPalette,
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                                                                                                                        ],
                                                                                                                        File::FILETYPE_IMAGE => [
                                                                                                                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;pxaProductManagerPalette,
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                                                                                                                        ]
                                                                                                                    ]
                                                                                                                ],
                                                                                                                // @codingStandardsIgnoreEnd
                                                                                                                'behaviour' => [
                                                                                                                    'allowLanguageSynchronization' => true
                                                                                                                ],
                                                                                                            ], 'svg'),
            ],
        ]
    ];

    $tx_pxaproductmanager_domain_model_attribute['columns']['type']['config']['items'] = [
        [
            $llType . Attribute::ATTRIBUTE_TYPE_INPUT,
            Attribute::ATTRIBUTE_TYPE_INPUT
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_TEXT,
            Attribute::ATTRIBUTE_TYPE_TEXT
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_DATETIME,
            Attribute::ATTRIBUTE_TYPE_DATETIME
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_DROPDOWN,
            Attribute::ATTRIBUTE_TYPE_DROPDOWN
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_MULTISELECT,
            Attribute::ATTRIBUTE_TYPE_MULTISELECT
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_CHECKBOX,
            Attribute::ATTRIBUTE_TYPE_CHECKBOX
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_LINK,
            Attribute::ATTRIBUTE_TYPE_LINK
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_IMAGE,
            Attribute::ATTRIBUTE_TYPE_IMAGE
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_FILE,
            Attribute::ATTRIBUTE_TYPE_FILE
        ],
        [
            $llType . Attribute::ATTRIBUTE_TYPE_LABEL,
            Attribute::ATTRIBUTE_TYPE_LABEL
        ],
    ];

    return $tx_pxaproductmanager_domain_model_attribute;
})();
