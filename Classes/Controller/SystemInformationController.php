<?php
namespace DieMedialen\AdditionalSysinfo\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Toolbar\Enumeration\InformationStatus;
use TYPO3\CMS\Backend\SystemInformationDisplayInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Count newest exceptions for the system information menu
 */
class SystemInformationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Modifies the SystemInformation array
     *
     * @param SystemInformationDisplayInterface $systemInformationDisplay
     * @return void
     */
    public function appendMessage(SystemInformationDisplayInterface $systemInformationDisplay)
    {
        $availableUpdates = array();
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'] as $identifier => $className) {
            $updateObject = $this->getUpdateObjectInstance($className, $identifier);
            if ($updateObject->shouldRenderWizard()) {
                $_expl = "";
                $updateObject->checkForUpdate($_expl);
                $availableUpdates[$identifier] = $updateObject->getTitle();
            }
        }

        if (count($availableUpdates)) {
            $systemInformationDisplay->addSystemMessage(
                implode("\n",$availableUpdates).' <a href="'.BackendUtility::getModuleUrl('system_InstallInstall').'">Install Tool</a>',
                InformationStatus::STATUS_WARNING,
                count($availableUpdates),
                'system_InstallInstall'
            );
        }
    }

    /**
     * Creates instance of an Update object
     *
     * @param string $className The class name
     * @param string $identifier The identifier of Update object - needed to fetch user input
     * @return AbstractUpdate Newly instantiated Update object
     */
    protected function getUpdateObjectInstance($className, $identifier) {
        $userInput = NULL;
        $versionAsInt = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($className, $identifier, $versionAsInt, $userInput, $this);
    }
}
