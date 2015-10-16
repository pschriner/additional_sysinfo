<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
	$signalSlotDispatcher->connect(
		\TYPO3\CMS\Backend\Backend\ToolbarItems\SystemInformationToolbarItem::class,
		'loadMessages',
		\DieMedialen\AdditionalSysinfo\Controller\SystemInformationController::class,
		'appendMessage'
	);
}