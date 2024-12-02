<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

final class AllowedScopesItemsProcFunc
{
    public function addItems(array &$configuration): void
    {
        $pid = (int)$configuration['row']['pid'];
        if ($pid < 1) {
            return;
        }

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pid);

        $scopes = $site->getConfiguration()['oauth2']['scopes'] ?? [];
        $items = [];
        foreach ($scopes as $scope) {
            $label = $identifier = $scope['identifier'] ?? $scope;
            $description = $scope['description'] ?? '';
            if ($description) {
                $label .= ': ' . $this->getLanguageService()->sL($description);
            }
            $items[] = [$label, $identifier];
        }

        if (!empty($items)) {
            $configuration['items'] = $items;
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
