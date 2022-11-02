<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Pixelant\PxaProductManager\Controller\ProductController;
use Pixelant\PxaProductManager\Controller\NavigationController;
use Pixelant\PxaProductManager\Controller\AjaxProductsController;
use Pixelant\PxaProductManager\Controller\AjaxJsonController;
use Pixelant\PxaProductManager\Controller\FilterController;
use Pixelant\PxaProductManager\Hook\PageLayoutView;
use Pixelant\PxaProductManager\Backend\FormDataProvider\ProductEditFormInitialize;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use Pixelant\PxaProductManager\Backend\FormDataProvider\OrderEditFormInitialize;
use Pixelant\PxaProductManager\LinkHandler\LinkHandling;
use Pixelant\PxaProductManager\LinkHandler\ProductLinkBuilder;
use Pixelant\PxaProductManager\LinkHandler\LinkHandlingFormData;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Cache\Backend\FileBackend;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Pixelant\PxaProductManager\Task\ProductCustomSortingUpdateTask;
use Pixelant\PxaProductManager\Backend\FormEngine\FieldControl\AttributeIdentifierControl;
use Pixelant\PxaProductManager\Backend\Evaluation\LcFirstEvaluation;
defined('TYPO3') || die;
$_EXTKEY = "pxa_product_manager";
call_user_func(
    function () {
        // @codingStandardsIgnoreStart
        ExtensionUtility::configurePlugin(
            'PxaProductManager',
            'Pi1',
            [
                ProductController::class => 'list, show, wishList, finishOrder, lazyList, comparePreView, compareView, groupedList, promotionList',
                NavigationController::class => 'show',
                AjaxProductsController::class => 'ajaxLazyList, latestVisited',
                AjaxJsonController::class => 'toggleWishList, toggleCompareList, loadCompareList, emptyCompareList, loadWishList, addLatestVisitedProduct',
                FilterController::class => 'showFilter'
            ],
            // non-cacheable actions
            [
                ProductController::class => 'wishList, finishOrder, comparePreView, compareView',
                AjaxProductsController::class => 'ajaxLazyList, latestVisited',
                AjaxJsonController::class => 'toggleWishList, toggleCompareList, loadCompareList, emptyCompareList, loadWishList, addLatestVisitedProduct'
            ]
        );
        // @codingStandardsIgnoreEnd

        // Register cart as another plugin. Otherwise it has conflict
        // with product single view
        ExtensionUtility::configurePlugin(
            'PxaProductManager',
            'Pi2',
            [
                ProductController::class => 'wishListCart, compareListCart',
            ],
            // non-cacheable actions
            [
            ]
        );

        // @codingStandardsIgnoreStart
        // Page module hook
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['pxaproductmanager_pi1']['pxa_product_manager'] =
            PageLayoutView::class . '->getExtensionSummary';

        // Form data provider hook, to generate attributes TCA
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][ProductEditFormInitialize::class] = [
            'depends' => [
                DatabaseRowInitializeNew::class,
                TcaSelectItems::class
            ]
        ];

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][OrderEditFormInitialize::class] = [
            'depends' => [
                DatabaseRowInitializeNew::class
            ]
        ];

        // LinkHandler
        // t3://pxappm?product=[product_id]
        // t3://pxappm?category=[category_id]
        $linkType = 'pxappm';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler'][$linkType] = LinkHandling::class;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder'][$linkType] = ProductLinkBuilder::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['linkHandler'][$linkType] = LinkHandlingFormData::class;

        // Register cache
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pxa_pm_categories']['frontend'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pxa_pm_categories'] = [
                'frontend' => VariableFrontend::class,
                'backend' => FileBackend::class,
                'options' => [
                    'defaultLifetime' => 0
                ],
                'groups' => ['all']
            ];
        }
        // @codingStandardsIgnoreEnd

        // Include page typoscript
        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pxa_product_manager/Configuration/TypoScript/PageTS/rteTsConfig.ts">'
        );

        $ppmLocalLangBe = 'LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_be.xlf';
        $productCustomSortingUpdateTask = ProductCustomSortingUpdateTask::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][$productCustomSortingUpdateTask] = [
            'extension' => 'pxa_product_manager',
            'title' => $ppmLocalLangBe . ':task.productCustomSortingUpdate.title',
            'description' => $ppmLocalLangBe . ':task.productCustomSortingUpdate.description'
        ];

        // Register field control for identifier
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1534315213786] = [
            'nodeName' => 'attributeIdentifierControl',
            'priority' => 30,
            'class' => AttributeIdentifierControl::class
        ];

        // Register the class to be available in 'eval' of TCA
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][LcFirstEvaluation::class] = '';
    },
    'pxa_product_manager'
);
