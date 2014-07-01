<?php 
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


// register plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'quickgooglemap',
	'Quick Google Map'
);


// set locallang file
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
		'tx_cbgooglemaps', 'EXT:cbgooglemaps/Resources/Private/Language/locallang_csh_tx_cbgooglemaps.xml');

// add static template configuration
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		$_EXTKEY, 'Configuration/TypoScript', 'Quick Google Maps');

// set plugin signature
$pluginSignature = str_replace('_','',$_EXTKEY) . '_quickgooglemap';
// add some new fields by flexform definition
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// define flexform file
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
		$pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_quickgooglemap.xml');


// exclude some default backend fields, like: layout, select_key, pages and recursive
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';

?>
