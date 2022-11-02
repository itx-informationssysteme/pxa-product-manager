<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die;

ExtensionManagementUtility::addStaticFile('pxa_product_manager', 'Configuration/TypoScript', 'Products Manager');

ExtensionManagementUtility::addStaticFile('pxa_product_manager', 'Configuration/TypoScript/Solr', 'Products Manager: Solr Configuration');

ExtensionManagementUtility::addStaticFile('pxa_product_manager', 'Configuration/TypoScript/Sitemap', 'Products Manager: Xml Sitemap');
