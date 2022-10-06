<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http\Firewall;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
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

/**
 * ScopeRule
 */
class ScopeRule implements RuleInterface
{
    private $scopes;

    public function __construct($scopes)
    {
        $this->scopes = is_array($scopes) ? $scopes : GeneralUtility::trimExplode('|', $scopes);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $tokenScopes = $request->getAttribute('oauth_scopes', []);
        if (!empty($this->scopes) && empty(array_intersect($this->scopes, $tokenScopes))) {
            throw OAuthServerException::accessDenied('Require any scope "' . implode('", "', $this->scopes) . '"');
        }
    }
}
