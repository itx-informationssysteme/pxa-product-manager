<?php
use Pixelant\PxaProductManager\Domain\Model\OrderFormField;
defined('TYPO3') || die('Access denied.');

return (function() {
    $ll = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_db.xlf:tx_pxaproductmanager_domain_model_orderformfield';
    $llCore = 'LLL:EXT:core/Resources/Private/Language/';

    return [
        'ctrl' => [
            'title' => $ll,
            'label' => 'name',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
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
            'sortby' => 'sorting',
            'hideTable' => true,
            'searchFields' => 'name',
            'iconfile' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/form-field.svg'
        ],
        'types' => [
            OrderFormField::FIELD_INPUT => ['showitem' => '--palette--;;core, --palette--;;input'],
            OrderFormField::FIELD_TEXTAREA => ['showitem' => '--palette--;;core, --palette--;;textarea'],
            OrderFormField::FIELD_SELECTBOX => ['showitem' => '--palette--;;core, --palette--;;selectbox'],
            OrderFormField::FIELD_CHECKBOX => ['showitem' => '--palette--;;core, --palette--;;checkbox']
        ],
        'palettes' => [
            'core' => ['showitem' => 'hidden, --linebreak--, type'],
            'input' => ['showitem' => 'name, user_email_field, --linebreak--, label, placeholder, --linebreak--, validation_rules'],
            'textarea' => ['showitem' => 'name, --linebreak--, label, placeholder, --linebreak--, validation_rules'],
            'selectbox' => ['showitem' => 'name, --linebreak--, label, --linebreak--, options, --linebreak--, validation_rules'],
            'checkbox' => ['showitem' => 'name, --linebreak--, label, --linebreak--, additional_text, --linebreak--, validation_rules']
        ],
        'columns' => [
            'sys_language_uid' => [
                'exclude' => true,
                'label' => $llCore . 'locallang_general.xlf:LGL.language',
                'config' => ['type' => 'language'],
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
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_orderformfield',
                    'foreign_table_where' => 'AND tx_pxaproductmanager_domain_model_orderformfield.pid=###CURRENT_PID### AND tx_pxaproductmanager_domain_model_orderformfield.sys_language_uid IN (-1,0)',
                    'default' => 0
                ],
            ],
            'l10n_diffsource' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],
            'hidden' => [
                'exclude' => true,
                'label' => $llCore . 'locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'items' => [
                        '1' => [
                            '0' => $llCore . 'locallang_core.xlf:labels.enabled'
                        ]
                    ],
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
                    'eval' => 'trim,required,uniqueInPid,alpha,nospace,Pixelant\\PxaProductManager\\Backend\\Evaluation\\LcFirstEvaluation'
                ]
            ],
            'label' => [
                'exclude' => 0,
                'label' => $ll . '.label',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ]
            ],
            'type' => [
                'exclude' => 0,
                'onChange' => 'reload',
                'label' => $ll . '.type',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        [$ll . '.type.1', OrderFormField::FIELD_INPUT],
                        [$ll . '.type.2', OrderFormField::FIELD_TEXTAREA],
                        [$ll . '.type.3', OrderFormField::FIELD_SELECTBOX],
                        [$ll . '.type.4', OrderFormField::FIELD_CHECKBOX]
                    ]
                ]
            ],
            'placeholder' => [
                'exclude' => 0,
                'label' => $ll . '.placeholder',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim'
                ]
            ],
            'options' => [
                'exclude' => 0,
                'label' => $ll . '.options',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tx_pxaproductmanager_domain_model_option',
                    'foreign_field' => 'order_field',
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
            'validation_rules' => [
                'exclude' => 0,
                'label' => $ll . '.validation_rules',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'items' => [
                        [
                            $ll . '.validation.required',
                            'required'
                        ],
                        [
                            $ll . '.validation.email',
                            'email'
                        ],
                        [
                            $ll . '.validation.url',
                            'url'
                        ]
                    ]
                ]
            ],
            'user_email_field' => [
                'exclude' => 0,
                'label' => $ll . '.user_email_field',
                'config' => [
                    'type' => 'check',
                    'default' => 0
                ],
            ],
            'additional_text' => [
                'exclude' => 0,
                'label' => $ll . '.additional_text',
                'config' => [
                    'type' => 'text',
                    'cols' => 15,
                    'rows' => 5,
                    'enableRichtext' => true
                ]
            ]
        ]
    ];
})();
