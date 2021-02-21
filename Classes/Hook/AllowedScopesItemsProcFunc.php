<?php

namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AllowedScopesItemsProcFunc
{
    public function addItems(array &$configuration)
    {
        $pid = $configuration['row']['pid'];

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pid);
        if ($site instanceof NullSite) {
            return;
        }

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

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
