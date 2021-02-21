<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ScopesRule implements RuleInterface
{
    private $scopes;

    public function __construct($scopes)
    {
        $this->scopes = is_array($scopes) ? $scopes : GeneralUtility::trimExplode(',', $scopes);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $tokenScopes = $request->getAttribute('oauth_scopes', []);
        if (!empty($this->scopes) && count(array_intersect($this->scopes, $tokenScopes)) !== count($this->scopes)) {
            throw new AccessDeniedException('Require scopes "' . implode('", "', $this->scopes) . '"', 1613596676896);
        }
    }
}
