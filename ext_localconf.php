<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'quickgooglemap',
	array(
		'Map' => 'index',		
	),
	// non-cacheable actions
	array()
);

?>