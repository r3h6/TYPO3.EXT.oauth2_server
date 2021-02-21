<?php

namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AllowedScopesItemsProcFunc
{
    public function addItems(array &$configuration)
    {
        $pid = $configuration['row']['pid'];

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
