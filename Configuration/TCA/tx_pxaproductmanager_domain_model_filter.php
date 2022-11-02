<?php
use Pixelant\PxaProductManager\Domain\Model\Filter;
use Pixelant\PxaProductManager\Domain\Model\Attribute;
defined('TYPO3') || die('Access denied.');

return (function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:';
    $llCore = 'LLL:EXT:core/Resources/Private/Language/';

    return [
        'ctrl' => [
            'title' => $ll . 'tx_pxaproductmanager_domain_model_filter',
            'label' => 'name',
            'label_alt' => 'label',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'versioningWS' => true,

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

            'searchFields' => 'name,parent_category,attribute,',
            'iconfile' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/filter.svg'
        ],
        'types' => [
            '1' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;;categories, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,--palette--;;lang'],
            '2' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;;attributes, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,--palette--;;lang'],
            '3' => ['showitem' => '--palette--;;core, --palette--;;common, --palette--;;attributes, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,--palette--;;lang']
        ],
        'palettes' => [
            'core' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, --linebreak--, hidden'],
            'common' => ['showitem' => 'type, --linebreak--, name, --linebreak--, label'],
            'categories' => ['showitem' => 'inverse_conjunction, --linebreak--, parent_category'],
            'attributes' => ['showitem' => 'inverse_conjunction, --linebreak--, attribute'],
            'lang' => ['showitem' => 'starttime, --linebreak--, endtime']
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
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_filter',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_filter.pid=###CURRENT_PID### AND tx_pxaproductmanager_domain_model_filter.sys_language_uid IN (-1,0)',
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
                    'max' => 255,
                ]
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

            'type' => [
                'exclude' => 1,
                'onChange' => 'reload',
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.type',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['Categories', Filter::TYPE_CATEGORIES],
                        [\Attribute::class, Filter::TYPE_ATTRIBUTES],
                        [
                            'Attribute min-max (if applicable, require only numeric attribute values)',
                            Filter::TYPE_ATTRIBUTES_MINMAX
                        ],
                    ],
                    'size' => 1,
                    'maxitems' => 1,
                    'eval' => ''
                ],
            ],
            'name' => [
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.name',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ],
            ],
            'label' => [
                'exclude' => 0,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.label',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim'
                ]
            ],
            'parent_category' => [
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.parent_category',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectTree',
                    'treeConfig' => [
                        'parentField' => 'parent',
                        'appearance' => [
                            'showHeader' => true,
                            'expandAll' => true,
                            'maxLevels' => 99,
                        ],
                    ],
                    'foreign_table' => 'sys_category',
                    'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
                    'size' => 20,
                    'minitems' => 1,
                    'maxitems' => 1,
                    'default' => 0
                ]
            ],
            'attribute' => [
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.attribute',
                'config' => [
                    'type' => 'select',
                    'disableNoMatchingValueElement' => true,
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_attribute',
                    'foreign_table_where' => ' AND tx_pxaproductmanager_domain_model_attribute.type IN (' . Attribute::ATTRIBUTE_TYPE_DROPDOWN . ',' . Attribute::ATTRIBUTE_TYPE_MULTISELECT . ')' . ' AND (tx_pxaproductmanager_domain_model_attribute.sys_language_uid = 0 OR tx_pxaproductmanager_domain_model_attribute.l10n_parent = 0) ORDER BY tx_pxaproductmanager_domain_model_attribute.sorting',
                    'minitems' => 1,
                    'maxitems' => 1,
                ]
            ],
            'inverse_conjunction' => [
                'displayCond' => 'FIELD:type:!=:3', // hide for range filter
                'exclude' => 1,
                'label' => $ll . 'tx_pxaproductmanager_domain_model_filter.inverse_conjunction',
                'config' => [
                    'type' => 'check',
                    'default' => 0
                ],
            ],
        ]
    ];
})();
