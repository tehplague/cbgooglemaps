<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY,
	'quickgooglemap',
	array(
		'Map' => 'index',		
	),
	// non-cacheable actions
	array()
);

?>