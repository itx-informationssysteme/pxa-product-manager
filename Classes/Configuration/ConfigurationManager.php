<?php

namespace Pixelant\PxaProductManager\Configuration;

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

use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager as ExtbaseConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * A configuration manager following the strategy pattern (GoF315). It hides the concrete
 * implementation of the configuration manager and provides an unified acccess point.
 *
 * Use the shutdown() method to drop the concrete implementation.
 */
class ConfigurationManager extends ExtbaseConfigurationManager
{

    /**
     * Set the current page ID for BackendConfiguration
     *
     * @param integer $currentPageId Current page id
     *
     * @return void
     */
    public function setCurrentPageId(int $currentPageId): void
    {
        if ($this->concreteConfigurationManager instanceof BackendConfigurationManager) {
            $this->concreteConfigurationManager->setCurrentPageId($currentPageId);
        }
    }
}
