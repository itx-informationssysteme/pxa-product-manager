<?php
defined('TYPO3') || die;
$_EXTKEY = "pxa_product_manager";
call_user_func(
    function ($_EXTKEY) {
        // Register plugin
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'pxa_product_manager',
            'Pi1',
            'Product Manager'
        );

        $tables = [
            'tx_pxaproductmanager_domain_model_product',
            'tx_pxaproductmanager_domain_model_attribute',
            'tx_pxaproductmanager_domain_model_attributeset',
            'tx_pxaproductmanager_domain_model_attributevalue',
            'tx_pxaproductmanager_domain_model_option',
            'tx_pxaproductmanager_domain_model_link',
            'tx_pxaproductmanager_domain_model_filter',
            'tx_pxaproductmanager_domain_model_order',
            'tx_pxaproductmanager_domain_model_orderconfiguration',
            'tx_pxaproductmanager_domain_model_orderformfield'
        ];

        // @codingStandardsIgnoreStart
        foreach ($tables as $table) {
            if ($table !== 'tx_pxaproductmanager_domain_model_attributevalue'
                || $table !== 'tx_pxaproductmanager_domain_model_order'
            ) {
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
                    $table,
                    'EXT:pxa_product_manager/Resources/Private/Language/locallang_csh_' . $table . '.xlf'
                );
            }

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
        }

        // Register hooks
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['pxa_product_manager'] = \Pixelant\PxaProductManager\Hook\TceMain::class;

        // Add plugin to content element wizard
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pxa_product_manager/Configuration/TypoScript/PageTS/contentElementWizard.ts">'
        );
        // @codingStandardsIgnoreEnd

        // Link handler
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pxa_product_manager/Configuration/TypoScript/PageTS/linkHandler.ts">'
        );

        $icons = [
            'ext-pxa-product-manager-wizard-icon' => 'package.svg',
        ];

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );

        foreach ($icons as $identifier => $path) {
            $iconRegistry->registerIcon(
                $identifier,
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:pxa_product_manager/Resources/Public/Icons/Svg/' . $path]
            );
        }

        if (TYPO3_MODE === 'BE') {
            // Register BE module
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'PxaProductManager',
                'web',          // Main area
                'mod1',         // Name of the module
                '',             // Position of the module
                [
                    \Pixelant\PxaProductManager\Controller\BackendManagerController::class => 'index, listCategories, listProducts, listOrders, showOrder, deleteOrder, toggleOrderState'
                ],
                [          // Additional configuration
                    'access' => 'user,group',
                    'icon' => 'EXT:' . 'pxa_product_manager' . '/Resources/Public/Icons/Extension.svg',
                    'labels' => 'LLL:EXT:' . 'pxa_product_manager' . '/Resources/Private/Language/locallang_mod.xml',
                ]
            );
        }
    },
    'pxa_product_manager'
);
