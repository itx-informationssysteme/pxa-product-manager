<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die;

call_user_func(function() {
    ExtensionUtility::registerPlugin('pxa_product_manager', 'Pi1', 'Product Manager');

    $pluginSignature = 'pxaproductmanager_pi1';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

    ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:pxa_product_manager/Configuration/FlexForms/flexform_pi1.xml');
});
