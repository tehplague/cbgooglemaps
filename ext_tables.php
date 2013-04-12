<?php 
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


// register plugin
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'quickgooglemap',
	'Quick Google Map'
);


// set locallang file
t3lib_extMgm::addLLrefForTCAdescr('tx_cbgooglemaps', 'EXT:cbgooglemaps/Resources/Private/Language/locallang_csh_tx_cbgooglemaps.xml');


// add static template configuration
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Quick Google Maps');

// include flexform and js extension class
include_once(t3lib_extMgm::extPath($_EXTKEY).'/Classes/class.tx_cbgooglemaps_addFlexformFields.php');
include_once(t3lib_extMgm::extPath($_EXTKEY).'/Classes/class.tx_cbgooglemaps_includeBeJavascript.php');

// set plugin signature
$pluginSignature = str_replace('_','',$_EXTKEY) . '_quickgooglemap';
// add some new fields by flexform definition
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// define flexform file
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_' .quickgooglemap. '.xml');


// exclude some default backend fields, like: layout, select_key, pages and recursive
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';

?>
