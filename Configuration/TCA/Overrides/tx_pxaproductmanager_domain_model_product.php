<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Pixelant\PxaProductManager\Utility\TCAUtility;
defined('TYPO3') || die;

call_user_func(function() {
    ExtensionManagementUtility::makeCategorizable('pxa_product_manager', 'tx_pxaproductmanager_domain_model_product', // optional: in case the field would need a different name as "categories".
        // The field is mandatory for TCEmain to work internally.
                                                                          'categories', // optional: add TCA options which controls how the field is displayed. e.g position and name of the category tree.
                                                                          [
                                                                              'fieldConfiguration' => [
                                                                                  'foreign_table_where' => TCAUtility::getCategoriesTCAWhereClause() . 'AND sys_category.sys_language_uid IN (-1, 0)'
                                                                              ]
                                                                          ]);

    $columns = &$GLOBALS['TCA']['tx_pxaproductmanager_domain_model_product']['columns'];
    $columns['categories']['onChange'] = 'reload';
});
